<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\TestSession;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['pic'])->withCount('participants');

        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('nama', 'like', "%{$term}%")
                    ->orWhere('kode_acara', 'like', "%{$term}%")
                    ->orWhere('perusahaan', 'like', "%{$term}%")
                    ->orWhere('deskripsi', 'like', "%{$term}%")
                    ->orWhereHas('pic', fn($p) => $p->where('nama', 'like', "%{$term}%"));
            });
        }

        $events = $query->latest()->paginate(10)->appends($request->query());

        if ($request->ajax()) {
            $events->getCollection()->transform(function ($event) {
                return [
                    'id'                 => $event->id,
                    'name'               => $event->nama,
                    'company'            => $event->perusahaan ?? '-',
                    'event_code'         => $event->kode_acara,
                    'pic_name'           => $event->pic->nama ?? 'Belum ada Mitra',
                    'participants_count' => $event->participants_count,
                    'max_participants'   => $event->maks_peserta,
                    'is_active'          => $event->aktif,
                    'date_range'         => $event->tanggal_mulai->format('d M') . ' - ' . $event->tanggal_selesai->format('d M Y'),
                    'show_url'           => route('admin.events.show', $event->id),
                    'edit_url'           => route('admin.events.edit', $event->id),
                    'delete_url'         => route('admin.events.destroy', $event->id),
                    'toggle_url'         => route('admin.events.toggle-status', $event->id),
                ];
            });

            return response()->json([
                'events'   => $events,
                'is_admin' => Auth::user()->peran === 'admin',
            ]);
        }

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $mitras = User::where('peran', 'mitra')->where('aktif', true)->orderBy('nama')->get();
        return view('admin.events.create', compact('mitras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'             => ['required', 'string', 'max:100'],
            'kode_acara'       => ['required', 'string', 'max:15', 'unique:acara,kode_acara'],
            'perusahaan'       => ['nullable', 'string', 'max:100'],
            'tanggal_mulai'    => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai'  => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'id_pic'           => ['nullable', 'exists:pengguna,id'],
            'maks_peserta'     => ['nullable', 'integer', 'min:1'],
            'deskripsi'        => ['nullable', 'string', 'max:1000'],
            'aktif'            => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request) {
            $last = DB::table('acara')
                ->lockForUpdate()
                ->select(DB::raw("CAST(SUBSTRING(id, 4) AS UNSIGNED) as num"))
                ->orderByDesc('num')
                ->first();

            $nextNumber = $last ? $last->num + 1 : 1;
            $eventId    = 'EVT' . $nextNumber;

            Event::create([
                'id'              => $eventId,
                'nama'            => $request->nama,
                'kode_acara'      => strtoupper($request->kode_acara),
                'perusahaan'      => $request->perusahaan,
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'id_pic'          => $request->id_pic,
                'maks_peserta'    => $request->maks_peserta,
                'deskripsi'       => $request->deskripsi,
                'aktif'           => $request->has('aktif'),
            ]);
        });

        return redirect()->route('admin.events.index')->with('success', 'Acara berhasil dibuat.');
    }

    public function show(Event $event)
    {
        $event->load(['pic', 'participants.testSessions' => function ($q) use ($event) {
            $q->where('id_acara', $event->id);
        }]);

        $totalParticipants = $event->participants->count();

        $completedTests = TestSession::where('id_acara', $event->id)
            ->where('selesai', true)->count();

        $resultsSent = TestResult::whereHas('testSession', fn($q) => $q->where('id_acara', $event->id))
            ->whereNotNull('email_terkirim_pada')->count();

        $stats = [
            'total_participants' => $totalParticipants,
            'completed_tests'    => $completedTests,
            'pending_tests'      => $totalParticipants - $completedTests,
            'results_sent'       => $resultsSent,
        ];

        return view('admin.events.show', compact('event', 'stats'));
    }

    public function edit(Event $event)
    {
        $mitras = User::where('peran', 'mitra')->where('aktif', true)->orderBy('nama')->get();
        return view('admin.events.edit', compact('event', 'mitras'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'nama'            => ['required', 'string', 'max:100'],
            'kode_acara'      => ['required', 'string', 'max:15', Rule::unique('acara', 'kode_acara')->ignore($event->id)],
            'perusahaan'      => ['nullable', 'string', 'max:100'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'id_pic'          => ['nullable', 'exists:pengguna,id'],
            'maks_peserta'    => ['nullable', 'integer', 'min:1'],
            'deskripsi'       => ['nullable', 'string', 'max:1000'],
        ]);

        $event->update([
            'nama'            => $request->nama,
            'kode_acara'      => strtoupper($request->kode_acara),
            'perusahaan'      => $request->perusahaan,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'id_pic'          => $request->id_pic,
            'maks_peserta'    => $request->maks_peserta,
            'deskripsi'       => $request->deskripsi,
            'aktif'           => $request->has('aktif'),
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Acara berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        if ($event->participants()->count() > 0) {
            return back()->with('error', 'Gagal menghapus: Acara memiliki peserta terdaftar.');
        }
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Acara berhasil dihapus.');
    }

    public function toggleStatus(Event $event)
    {
        $event->update(['aktif' => !$event->aktif]);
        $status = $event->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Acara berhasil {$status}.");
    }

    public function exportPdf(Request $request)
    {
        $query = Event::with('pic')->withCount('participants');
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(fn($q) => $q->where('nama', 'like', "%{$term}%")->orWhere('kode_acara', 'like', "%{$term}%"));
        }
        $events = $query->latest()->get();

        $pdf = Pdf::loadView('admin.events.pdf.eventReport', [
            'reportTitle' => 'Laporan Daftar Acara',
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $events,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan-Acara.pdf');
    }
}
