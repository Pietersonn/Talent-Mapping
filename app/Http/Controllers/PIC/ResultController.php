<?php

namespace App\Http\Controllers\PIC;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TestResult;
use App\Models\TestSession;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index()
    {
        // Semua hasil dari event milik PIC
        $eventIds = Event::where('pic_id', Auth::id())->pluck('id');

        $results = TestResult::query()
            ->whereHas('session', fn($q) => $q->whereIn('event_id', $eventIds))
            ->with([
                'session:id,user_id,event_id',
                'session.user:id,name,email',
                'session.event:id,name,event_code'
            ])
            ->latest('report_generated_at')
            ->paginate(15);

        return view('pic.result.index', compact('results'));
    }

    public function topPerformers()
    {
        $eventIds = Event::where('pic_id', Auth::id())->pluck('id');

        // Contoh sederhana: urutkan berdasarkan skor total SJT (jika disimpan) lalu tampilkan 20 teratas
        $results = TestResult::query()
            ->whereHas('session', fn($q) => $q->whereIn('event_id', $eventIds))
            ->with(['session.user:id,name,email','session.event:id,name,event_code'])
            ->orderByDesc('sjt_total_score') // ganti sesuai field agregat Anda
            ->limit(20)
            ->get();

        return view('pic.result.top-performers', compact('results'));
    }

    public function show(TestResult $testResult)
    {
        abort_unless(optional($testResult->session)->event?->pic_id === Auth::id(), 403);

        $testResult->load([
            'session:id,user_id,event_id',
            'session.user:id,name,email',
            'session.event:id,name,event_code'
        ]);

        return view('pic.result.show', compact('testResult'));
    }
}
