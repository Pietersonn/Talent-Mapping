<?php

namespace App\Http\Controllers;

class ThanksController extends Controller
{
    public function show(string $sessionId)
    {
        // simple view: “Terima kasih! Hasil kamu sedang diproses & akan dikirim ke email.”
        return view('thanks', ['sessionId' => $sessionId]);
    }
}
