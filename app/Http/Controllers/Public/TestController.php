<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Helpers\QuestionHelper;
use App\Models\Program;
use App\Models\QuestionVersion;
use App\Models\ST30Question;
use App\Models\ST30Response;
use App\Models\TalentCompetencyQuestion;
use App\Models\TalentCompetencyResponse;
use App\Models\TestSession;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TestController extends Controller
{
    // ─── HALAMAN AWAL: Input kode program ─────────────────────────────────────
    public function showCodeForm(): View
    {
        return view('public.test.enter-code');
    }

    public function submitCode(Request $request): RedirectResponse
    {
        $request->validate(['kode_program' => 'required|string|max:20']);

        $Program = Program::where('kode_program', trim(strtoupper($request->kode_program)))
            ->where('aktif', true)
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now())
            ->first();

        if (!$Program) {
            return back()->withErrors(['kode_program' => 'Kode program tidak valid atau program sudah tidak aktif.'])->withInput();
        }

        // Cek kapasitas
        if ($Program->maks_peserta && $Program->participants()->count() >= $Program->maks_peserta) {
            return back()->with('error', 'Program ini sudah mencapai batas maksimum peserta.');
        }

        return redirect()->route('test.register', ['Program' => $Program->id]);
    }

    // ─── REGISTRASI PESERTA ────────────────────────────────────────────────────
    public function showRegister(Program $Program): View|RedirectResponse
    {
        if (!$Program->aktif || now()->lt($Program->tanggal_mulai) || now()->gt($Program->tanggal_selesai)) {
            return redirect()->route('test.code')->with('error', 'Program ini sudah tidak aktif.');
        }

        $user = Auth::user();

        // Cek apakah sudah punya sesi aktif/selesai di Program ini
        $existingSession = TestSession::where('id_pengguna', $user->id)
            ->where('id_program', $Program->id)
            ->latest()
            ->first();

        if ($existingSession && $existingSession->selesai) {
            return redirect()->route('test.result', ['session' => $existingSession->id]);
        }

        if ($existingSession && !$existingSession->selesai) {
            return redirect()->route('test.resume', ['session' => $existingSession->id]);
        }

        return view('public.test.register', compact('Program'));
    }

    public function storeRegister(Request $request, Program $Program): RedirectResponse
    {
        $request->validate([
            'nama_peserta'    => 'required|string|max:100',
            'latar_belakang'  => 'nullable|string|max:255',
            'jabatan'         => 'nullable|string|max:100',
        ]);

        $user = Auth::user();

        // Cek duplikasi
        $exists = TestSession::where('id_pengguna', $user->id)->where('id_program', $Program->id)->exists();
        if ($exists) {
            return redirect()->route('test.register', $Program->id)->with('error', 'Anda sudah terdaftar di program ini.');
        }

        $st30Version = QuestionVersion::getActive('st30');
        $tkVersion   = QuestionVersion::getActive('tk');

        if (!$st30Version || !$tkVersion) {
            return back()->with('error', 'Sistem asesmen belum dikonfigurasi. Hubungi administrator.');
        }

        DB::transaction(function () use ($request, $user, $Program, $st30Version) {
            $session = TestSession::create([
                'id_pengguna'   => $user->id,
                'id_program'    => $Program->id,
                'token_sesi'    => Str::random(40),
                'langkah_saat_ini' => 'st30_start',
                'id_versi_st30' => $st30Version->id,
                'nama_peserta'  => $request->nama_peserta,
                'latar_belakang'=> $request->latar_belakang,
                'jabatan'       => $request->jabatan,
                'selesai'       => false,
            ]);

            // Daftarkan ke pivot peserta_program
            $Program->participants()->syncWithoutDetaching([
                $user->id => ['tes_selesai' => false, 'hasil_terkirim' => false],
            ]);
        });

        $session = TestSession::where('id_pengguna', $user->id)->where('id_program', $Program->id)->latest()->first();
        return redirect()->route('test.start', ['session' => $session->id]);
    }

    // ─── MULAI / RESUME TES ───────────────────────────────────────────────────
    public function start(TestSession $session): View|RedirectResponse
    {
        $this->authorizeSession($session);

        if ($session->selesai) return redirect()->route('test.result', $session->id);

        $session->load('Program');
        return view('public.test.start', compact('session'));
    }

    public function resume(TestSession $session): RedirectResponse
    {
        $this->authorizeSession($session);

        if ($session->selesai) return redirect()->route('test.result', $session->id);

        $step = $session->langkah_saat_ini;

        if (str_starts_with($step, 'st30')) return redirect()->route('test.st30', $session->id);
        if (str_starts_with($step, 'sjt'))  return redirect()->route('test.tk', $session->id);

        return redirect()->route('test.start', $session->id);
    }

    // ─── ST-30 ────────────────────────────────────────────────────────────────
    public function showST30(TestSession $session): View|RedirectResponse
    {
        $this->authorizeSession($session);
        if ($session->selesai) return redirect()->route('test.result', $session->id);

        $versionId = $session->id_versi_st30;
        $questions = ST30Question::where('id_versi', $versionId)->orderBy('nomor')->get();

        // Jawaban yang sudah diisi
        $answered = ST30Response::where('id_sesi', $session->id)
            ->pluck('skor_dipilih', 'id_soal');

        return view('public.test.st30', compact('session', 'questions', 'answered'));
    }

    public function saveST30(Request $request, TestSession $session): JsonResponse
    {
        $this->authorizeSession($session);

        $request->validate([
            'jawaban'               => 'required|array',
            'jawaban.*.id_soal'     => 'required|string',
            'jawaban.*.skor'        => 'required|integer|min:1|max:5',
        ]);

        DB::transaction(function () use ($request, $session) {
            foreach ($request->jawaban as $item) {
                ST30Response::updateOrCreate(
                    ['id_sesi' => $session->id, 'id_soal' => $item['id_soal']],
                    ['id_versi_soal' => $session->id_versi_st30, 'skor_dipilih' => $item['skor']]
                );
            }
        });

        return response()->json(['success' => true]);
    }

    public function finishST30(Request $request, TestSession $session): RedirectResponse
    {
        $this->authorizeSession($session);

        $totalST30 = ST30Question::where('id_versi', $session->id_versi_st30)->count();
        $answered  = ST30Response::where('id_sesi', $session->id)->count();

        if ($answered < $totalST30) {
            return back()->with('error', "Harap jawab semua pertanyaan. Baru {$answered} dari {$totalST30} dijawab.");
        }

        $session->update(['langkah_saat_ini' => 'sjt_page1']);

        return redirect()->route('test.tk', $session->id);
    }

    // ─── TALENTA KOMPETENSI (TK) ──────────────────────────────────────────────
    public function showTK(TestSession $session, int $page = 1): View|RedirectResponse
    {
        $this->authorizeSession($session);
        if ($session->selesai) return redirect()->route('test.result', $session->id);

        $tkVersion = QuestionVersion::getActive('tk');
        if (!$tkVersion) return back()->with('error', 'Versi soal TK tidak aktif.');

        $perPage   = 10;
        $questions = TalentCompetencyQuestion::where('id_versi', $tkVersion->id)
            ->orderBy('nomor')
            ->forPage($page, $perPage)
            ->get();

        $totalPages  = (int) ceil(TalentCompetencyQuestion::where('id_versi', $tkVersion->id)->count() / $perPage);
        $answered    = TalentCompetencyResponse::where('id_sesi', $session->id)
            ->whereIn('id_soal', $questions->pluck('id'))
            ->pluck('pilihan_dipilih', 'id_soal');

        $session->update(['langkah_saat_ini' => "sjt_page{$page}"]);

        return view('public.test.tk', compact('session', 'questions', 'answered', 'page', 'totalPages', 'tkVersion'));
    }

    public function saveTK(Request $request, TestSession $session): JsonResponse
    {
        $this->authorizeSession($session);

        $request->validate([
            'jawaban'                 => 'required|array',
            'jawaban.*.id_soal'       => 'required|string',
            'jawaban.*.pilihan'       => 'required|string|in:a,b,c,d,e',
            'jawaban.*.nomor_halaman' => 'required|integer|min:1',
            'id_versi_soal'           => 'required|string',
        ]);

        DB::transaction(function () use ($request, $session) {
            foreach ($request->jawaban as $item) {
                TalentCompetencyResponse::upsertAnswer(
                    $session->id,
                    $item['id_soal'],
                    $request->id_versi_soal,
                    $item['nomor_halaman'],
                    $item['pilihan']
                );
            }
        });

        return response()->json(['success' => true]);
    }

    public function finishTK(Request $request, TestSession $session): RedirectResponse
    {
        $this->authorizeSession($session);

        $tkVersion   = QuestionVersion::getActive('tk');
        $totalTK     = TalentCompetencyQuestion::where('id_versi', $tkVersion->id)->count();
        $answeredTK  = TalentCompetencyResponse::where('id_sesi', $session->id)->count();

        if ($answeredTK < $totalTK) {
            return back()->with('error', "Harap jawab semua pertanyaan TK. Baru {$answeredTK} dari {$totalTK} dijawab.");
        }

        DB::transaction(function () use ($session) {
            $session->update([
                'selesai'          => true,
                'selesai_pada'     => now(),
                'langkah_saat_ini' => 'selesai',
            ]);

            // Update pivot
            $session->Program->participants()->updateExistingPivot($session->id_pengguna, ['tes_selesai' => true]);
        });

        // Dispatch job generate laporan
        \App\Jobs\GenerateAssessmentReport::dispatch($session->id);

        return redirect()->route('test.result', $session->id);
    }

    // ─── HASIL ────────────────────────────────────────────────────────────────
    public function result(TestSession $session): View|RedirectResponse
    {
        $this->authorizeSession($session);

        if (!$session->selesai) return redirect()->route('test.resume', $session->id);

        $session->load(['Program', 'testResult']);
        return view('public.test.result', compact('session'));
    }

    // ─── HELPER ───────────────────────────────────────────────────────────────
    private function authorizeSession(TestSession $session): void
    {
        abort_unless($session->id_pengguna === Auth::id(), 403, 'Akses ditolak.');
    }
}
