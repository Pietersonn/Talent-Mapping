<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TestSession;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $testSessions = TestSession::where('id_pengguna', $user->id)
            ->with(['event', 'testResult'])
            ->latest()
            ->get();

        $completedSessions = $testSessions->where('selesai', true);

        $hasResults = \Illuminate\Support\Facades\Schema::hasTable('hasil_tes') &&
            TestResult::whereIn('id_sesi', $completedSessions->pluck('id'))->exists();

        return view('public.profile.index', compact('user', 'testSessions', 'completedSessions', 'hasResults'));
    }

    public function edit(): View
    {
        return view('public.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'nama'          => ['required', 'string', 'max:100'],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update([
            'nama'          => $request->nama,
            'nomor_telepon' => $request->nomor_telepon,
        ]);

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
