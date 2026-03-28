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

}
