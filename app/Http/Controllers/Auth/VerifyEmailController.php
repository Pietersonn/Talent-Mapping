<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home')
                ->with('success', 'Email already verified!');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            // Activate user setelah email verified
            $request->user()->update(['is_active' => true]);
        }

        return redirect()->route('home')
            ->with('success', 'Email verified successfully! You now have full access.');
    }
}
