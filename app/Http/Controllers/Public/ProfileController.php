<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use App\Models\ResendRequest;
use App\Models\TestResult;
use Throwable;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil daftar hasil (kalau memang disimpan)
        $results = collect();
        try {
            if (class_exists(TestResult::class) && Schema::hasTable('test_results')) {
                $results = TestResult::query()
                    ->whereHas('session', fn($q) => $q->where('user_id', $user->id))
                    ->with(['session.event'])
                    ->latest('report_generated_at')
                    ->get();
            }
        } catch (Throwable $e) {
            // keep empty
        }

        $resendRequests = ResendRequest::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('public.profile.index', compact('user', 'results', 'resendRequests'));
    }

    public function requestResend(Request $request)
    {
        $user = Auth::user();

        // Validasi minimal: test_result_id wajib (DB punya FK & NOT NULL)
        $validated = $request->validate([
            'test_result_id' => ['required', 'string', Rule::exists('test_results', 'id')],
            // alasan opsional: taruh ke admin_notes
            'reason' => ['nullable', 'string', 'max:500'],
        ], [], [
            'test_result_id' => 'Hasil Tes',
        ]);

        $resultId = $validated['test_result_id'];

        // Pastikan hasil tersebut memang milik user
        $owned = TestResult::query()
            ->where('id', $resultId)
            ->whereHas('session', fn($q) => $q->where('user_id', $user->id))
            ->exists();

        if (! $owned) {
            return back()->withErrors(['general' => 'Hasil yang dipilih bukan milik akun ini.']);
        }

        // Throttle: Cek jika sudah ada PENDING untuk kombinasi (user_id, test_result_id)
        $pendingExists = ResendRequest::query()
            ->where('user_id', $user->id)
            ->where('test_result_id', $resultId)
            ->where('status', 'pending')
            ->exists();

        if ($pendingExists) {
            return back()->withErrors([
                'general' => 'Permintaan sebelumnya masih diproses untuk hasil ini.'
            ]);
        }

        // Cooldown: 24 jam sejak approved/rejected terakhir untuk kombinasi yang sama
        $cooldown = ResendRequest::query()
            ->where('user_id', $user->id)
            ->where('test_result_id', $resultId)
            ->whereIn('status', ['approved', 'rejected'])
            ->where('updated_at', '>=', now()->subDay())
            ->exists();

        if ($cooldown) {
            return back()->withErrors([
                'general' => 'Kamu baru saja mengajukan permintaan serupa. Coba lagi setelah 24 jam.'
            ]);
        }

        // Simpan (email tujuan akan diambil dari tabel users saat proses kirim oleh admin/job)
        ResendRequest::create([
            // id di-generate otomatis oleh model (RRxxx)
            'user_id'        => $user->id,
            'test_result_id' => $resultId,
            'status'         => 'pending',
            'admin_notes'    => $validated['reason'] ?? null, // taruh alasan user di sini
            'request_date'   => now(),
        ]);

        return redirect()->route('profile')
            ->with('success', 'Permintaan kirim ulang hasil sudah dikirim. Admin akan meninjau permintaanmu.');
    }
}
