<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TestSession;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::where('id_pic', Auth::id())->withCount('participants');

        if ($request->filled('search')) {
            $term = trim($request->search);
            $query->where(function ($q) use ($term) {
                $q->where('nama', 'like', "%{$term}%")
                    ->orWhere('kode_acara', 'like', "%{$term}%")
                    ->orWhere('perusahaan', 'like', "%{$term}%")
                    ->orWhere('deskripsi', 'like', "%{$term}%");
            });
        }

        $events = $query->latest('tanggal_mulai')->paginate(10)->appends($request->query());

        if ($request->ajax()) {
            $events->getCollection()->transform(function ($event) {
                return [
                    'id'                 => $event->id,
                    'name'               => $event->nama,
                    'company'            => $event->perusahaan ?? '-',
                    'event_code'         => $event->kode_acara,
                    'participants_count' => $event->participants_count,
                    'max_participants'   => $event->maks_peserta,
                    'is_active'          => $event->aktif,
                    'date_range'         => $event->tanggal_mulai->format('d M') . ' - ' . $event->tanggal_selesai->format('d M Y'),
                    'show_url'           => route('mitra.events.show', $event->id),
                ];
            });

            return response()->json(['events' => $events]);
        }

        return view('mitra.event.index', compact('events'));
    }

    public function show(Event $event)
    {
        abort_unless($event->id_pic === Auth::id(), 403);

        $event->load(['participants.testSessions' => function ($q) use ($event) {
            $q->where('id_acara', $event->id);
        }]);

        $totalParticipants = $event->participants->count();
        $completedTests    = TestSession::where('id_acara', $event->id)->where('selesai', true)->count();
        $resultsSent       = TestResult::whereHas('testSession', fn($q) => $q->where('id_acara', $event->id))
            ->whereNotNull('email_terkirim_pada')->count();

        $stats = [
            'total_participants' => $totalParticipants,
            'completed_tests'    => $completedTests,
            'pending_tests'      => $totalParticipants - $completedTests,
            'results_sent'       => $resultsSent,
        ];

        return view('mitra.event.show', compact('event', 'stats'));
    }

    public function exportPdf(Request $request)
    {
        $events = Event::where('id_pic', Auth::id())
            ->withCount('participants')
            ->latest('tanggal_mulai')
            ->get();

        $pdf = Pdf::loadView('mitra.event.pdf.eventReport', [
            'reportTitle' => 'Laporan Acara Saya',
            'generatedBy' => Auth::user()->nama,
            'generatedAt' => now()->format('d/m/Y H:i'),
            'rows'        => $events,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Acara_Mitra.pdf');
    }
}
