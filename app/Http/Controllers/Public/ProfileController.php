<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display user profile and test history
     */
    public function index(): View
    {
        $user = Auth::user();

        // Get user's test history (when test system is implemented)
        $testHistory = []; // This will be populated from database later

        return view('public.profile.index', compact('user', 'testHistory'));
    }

    /**
     * Request to resend test results
     */
    public function requestResend(Request $request): RedirectResponse
    {
        $request->validate([
            'test_session_id' => 'required|exists:test_sessions,id'
        ]);

        // Implementation for resend request
        // This will create a resend request in database for admin approval

        return redirect()->back()
            ->with('success', 'Resend request submitted successfully. Admin will review your request.');
    }
}
