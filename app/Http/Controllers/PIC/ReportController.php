<?php

namespace App\Http\Controllers\PIC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /* ========================= PARTICIPANTS ========================= */

    // Halaman Participants: nama, email, instansi (filter: event, instansi)
    public function participants(Request $request)
    {
        $picId     = Auth::id();
        $eventId   = $request->integer('event_id') ?: null;
        $instansiQ = trim((string)$request->get('instansi', ''));

        $events = Event::where('pic_id', $picId)
            ->orderByDesc('start_date')
            ->get(['id','name','event_code']);

        $participants = DB::table('test_sessions as ts')
            ->join('events as e', 'e.id', '=', 'ts.event_id')
            ->join('users as u', 'u.id', '=', 'ts.user_id')
            ->where('e.pic_id', $picId)
            ->when($eventId, fn($q) => $q->where('ts.event_id', $eventId))
            ->when($instansiQ !== '', fn($q) => $q->where('ts.participant_background', 'like', '%'.$instansiQ.'%'))
            ->select([
                'u.name as name', 'u.email as email',
                'ts.participant_background', // ← langsung string (instansi)
                'e.name as event_name', 'e.event_code',
                'ts.created_at',
            ])
            ->orderByDesc('ts.created_at')
            ->paginate(50)
            ->appends($request->query());

        // inject kolom instansi hasil trim string
        $participants->getCollection()->transform(function ($row) {
            $row->instansi = $this->extractInstansi($row->participant_background);
            return $row;
        });

        return view('pic.report.participants', [
            'events'       => $events,
            'eventId'      => $eventId,
            'instansiQ'    => $instansiQ,
            'participants' => $participants,
        ]);
    }

    // Export PDF Participants (menghormati filter yang sama)
    public function exportParticipantsPdf(Request $request)
    {
        $picId     = Auth::id();
        $eventId   = $request->integer('event_id') ?: null;
        $instansiQ = trim((string)$request->get('instansi', ''));

        $rows = DB::table('test_sessions as ts')
            ->join('events as e', 'e.id', '=', 'ts.event_id')
            ->join('users as u', 'u.id', '=', 'ts.user_id')
            ->where('e.pic_id', $picId)
            ->when($eventId, fn($q) => $q->where('ts.event_id', $eventId))
            ->when($instansiQ !== '', fn($q) => $q->where('ts.participant_background', 'like', '%'.$instansiQ.'%'))
            ->select([
                'u.name as name', 'u.email as email',
                'ts.participant_background',
                'e.name as event_name', 'e.event_code',
            ])
            ->orderBy('u.name')
            ->get();

        $rows->transform(function ($r) {
            $r->instansi = $this->extractInstansi($r->participant_background);
            return $r;
        });

        $pdf = Pdf::loadView('pic.report.pdf.participants', [
            'rows'         => $rows,
            'generated_at' => now(),
        ])->setPaper('a4','portrait');

        return $pdf->download('participants-report-'.now()->format('Ymd_His').'.pdf');
    }

    /* ============================== TOP ============================== */

    // Halaman Top 10: jumlah 3 kompetensi SJT tertinggi (filter: event, instansi)
    public function top(Request $request)
    {
        $picId     = Auth::id();
        $eventId   = $request->integer('event_id') ?: null;
        $instansiQ = trim((string)$request->get('instansi', ''));

        $events = Event::where('pic_id', $picId)
            ->orderByDesc('start_date')
            ->get(['id','name','event_code']);

        $rows = DB::table('test_results as tr')
            ->join('test_sessions as ts', 'ts.id', '=', 'tr.session_id')
            ->join('events as e', 'e.id', '=', 'ts.event_id')
            ->join('users as u', 'u.id', '=', 'ts.user_id')
            ->where('e.pic_id', $picId)
            ->when($eventId, fn($q) => $q->where('ts.event_id', $eventId))
            ->when($instansiQ !== '', fn($q) => $q->where('ts.participant_background', 'like', '%'.$instansiQ.'%'))
            ->select([
                'tr.sjt_results',                 // JSON berisi top3
                'u.name as name','u.email as email',
                'ts.participant_background',      // string instansi
                'e.name as event_name','e.event_code',
            ])
            ->get();

        $rows = $rows->map(function ($r) {
            return (object)[
                'name'        => $r->name,
                'email'       => $r->email,
                'instansi'    => $this->extractInstansi($r->participant_background),
                'event_name'  => $r->event_name,
                'event_code'  => $r->event_code,
                'score'       => $this->sjtTop3Sum($r->sjt_results), // jumlah score dari top3
            ];
        })
        ->sortByDesc('score')
        ->take(10)
        ->values();

        return view('pic.report.top', [
            'events'   => $events,
            'eventId'  => $eventId,
            'instansiQ'=> $instansiQ,
            'rows'     => $rows,
        ]);
    }

    // Export PDF Top 10
    public function exportTopPdf(Request $request)
    {
        $picId     = Auth::id();
        $eventId   = $request->integer('event_id') ?: null;
        $instansiQ = trim((string)$request->get('instansi', ''));

        $rows = DB::table('test_results as tr')
            ->join('test_sessions as ts', 'ts.id', '=', 'tr.session_id')
            ->join('events as e', 'e.id', '=', 'ts.event_id')
            ->join('users as u', 'u.id', '=', 'ts.user_id')
            ->where('e.pic_id', $picId)
            ->when($eventId, fn($q) => $q->where('ts.event_id', $eventId))
            ->when($instansiQ !== '', fn($q) => $q->where('ts.participant_background', 'like', '%'.$instansiQ.'%'))
            ->select([
                'tr.sjt_results',
                'u.name as name','u.email as email',
                'ts.participant_background',
                'e.name as event_name','e.event_code',
            ])
            ->get();

        $rows = $rows->map(function ($r) {
            return (object)[
                'name'        => $r->name,
                'email'       => $r->email,
                'instansi'    => $this->extractInstansi($r->participant_background),
                'event_name'  => $r->event_name,
                'event_code'  => $r->event_code,
                'score'       => $this->sjtTop3Sum($r->sjt_results),
            ];
        })
        ->sortByDesc('score')
        ->take(10)
        ->values();

        $pdf = Pdf::loadView('pic.report.pdf.top', [
            'rows'         => $rows,
            'generated_at' => now(),
        ])->setPaper('a4','portrait');

        return $pdf->download('top10-report-'.now()->format('Ymd_His').'.pdf');
    }

    /* ============================ HELPERS ============================ */

    // Instansi = langsung dari kolom VARCHAR participant_background (trim, fallback '-')
    private function extractInstansi($background): string
    {
        $s = is_string($background) ? trim($background) : '';
        return $s !== '' ? $s : '-';
    }

    // Jumlahkan nilai SJT dari array top3 di sjt_results JSON
    private function sjtTop3Sum($sjtJson): float
    {
        if (empty($sjtJson)) return 0.0;

        $data = is_array($sjtJson) ? $sjtJson : json_decode((string)$sjtJson, true);
        if (!is_array($data) || empty($data['top3']) || !is_array($data['top3'])) {
            return 0.0;
        }

        $sum = 0.0;
        foreach ($data['top3'] as $row) {
            if (is_array($row) && isset($row['score']) && is_numeric($row['score'])) {
                $sum += (float)$row['score'];
            }
        }
        return $sum;
    }
}
