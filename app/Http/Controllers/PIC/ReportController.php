<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Event;

class ReportController extends Controller
{
    /* ============================================================
     | Ambil daftar ID event yang dimiliki PIC login.
     | Sesuaikan dengan skema DB-mu:
     | - Skema A: kolom events.pic_user_id
     | - Skema B: pivot event_user(role='pic')
     * ============================================================ */
    private function picEventIds(int $userId): array
    {
        $ids = [];

        // Skema A: kolom langsung di tabel events
        if (DB::getSchemaBuilder()->hasTable('events') && Schema::hasColumn('events', 'pic_user_id')) {
            $ids = array_merge($ids, Event::where('pic_user_id', $userId)->pluck('id')->all());
        }

        // Skema B: pivot event_user (role='pic')
        if (DB::getSchemaBuilder()->hasTable('event_user')) {
            $ids = array_merge($ids, DB::table('event_user')
                ->where('user_id', $userId)
                ->when(Schema::hasColumn('event_user', 'role'), fn($q) => $q->where('role', 'pic'))
                ->pluck('event_id')->all());
        }

        return array_values(array_unique($ids));
    }

    /* ============================================================
     | 1) My Events — daftar event milik PIC
     * ============================================================ */
    public function myEvents(Request $request)
    {
        $eventIds = $this->picEventIds(Auth::id());

        $events = collect();
        if ($eventIds) {
            $events = Event::query()
                ->whereIn('id', $eventIds)
                ->orderByDesc('start_date')
                ->get(['id', 'name', 'event_code', 'start_date', 'end_date']);
        }

        return view('pic.my_events', compact('events'));
    }

    /* ============================================================
     | Base query Participants (ambil pdf_path & skor total top3)
     | NOTE: bila ada kolom persisted `tr.sum_top3`, pakai itu (lebih cepat)
     * ============================================================ */
    private function baseParticipantsQuery(array $filters, array $allowedEventIds)
    {
        // Ekspresi sum top-3 dari JSON (fallback bila tidak ada kolom agregat)
        $sumExpr = "
            COALESCE(CAST(JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results,'$.top3[0].score')) AS DECIMAL(10,2)),0) +
            COALESCE(CAST(JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results,'$.top3[1].score')) AS DECIMAL(10,2)),0) +
            COALESCE(CAST(JSON_UNQUOTE(JSON_EXTRACT(tr.sjt_results,'$.top3[2].score')) AS DECIMAL(10,2)),0)
        ";

        $q = DB::table('test_sessions as ts')
            ->join('users as u', 'u.id', '=', 'ts.user_id')
            ->leftJoin('events as e', 'e.id', '=', 'ts.event_id')
            ->leftJoin('test_results as tr', 'tr.session_id', '=', 'ts.id')
            ->when($allowedEventIds, fn($qq) => $qq->whereIn('ts.event_id', $allowedEventIds))
            ->selectRaw("
                ts.id as session_id,
                u.name, u.email,
                ts.participant_background as instansi,
                e.id as event_id,
                e.name as event_name, e.event_code,
                tr.pdf_path,
                {$sumExpr} as sum_top3
            ");

        // Filters
        if (!empty($filters['event_id'])) {
            $q->where('ts.event_id', $filters['event_id']);
        }
        if (($filters['instansi'] ?? '') !== '') {
            $q->where('ts.participant_background', 'like', '%' . $filters['instansi'] . '%');
        }
        if (($filters['q'] ?? '') !== '') {
            $term = $filters['q'];
            $q->where(function ($w) use ($term) {
                $w->where('u.name', 'like', "%{$term}%")
                  ->orWhere('u.email', 'like', "%{$term}%");
            });
        }

        return [$q, $sumExpr];
    }

    /* ============================================================
     | 2) Participants — UI seperti Admin (tanpa tanggal)
     | Mode:
     |  - all      : semua peserta (skor 0 jika belum ada hasil), paginate
     |  - top/bottom: hanya yang punya hasil (tr.sjt_results not null), limit N
     * ============================================================ */
    public function participants(Request $req)
    {
        $validated = $req->validate([
            'mode'     => 'nullable|in:all,top,bottom',
            'n'        => 'nullable|integer|min:1|max:5000',
            'event_id' => 'nullable|string',
            'instansi' => 'nullable|string|max:255',
            'q'        => 'nullable|string|max:255',
        ]);

        $mode = $validated['mode'] ?? 'all';
        $n    = (int) ($validated['n'] ?? 10);

        $filters = [
            'event_id' => $validated['event_id'] ?? null,
            'instansi' => $validated['instansi'] ?? null,
            'q'        => $validated['q'] ?? null,
        ];

        $allowed = $this->picEventIds(Auth::id());

        // Dropdown Event hanya milik PIC
        $events = collect();
        if ($allowed) {
            $events = Event::query()
                ->whereIn('id', $allowed)
                ->orderByDesc('start_date')
                ->get(['id', 'name', 'event_code']);
        }

        [$q, $sumExpr] = $this->baseParticipantsQuery($filters, $allowed);

        // Mode
        if ($mode === 'all') {
            // semua (termasuk yang belum ada hasil), urut skor desc → nama → id
            $q->orderByRaw("{$sumExpr} DESC")->orderBy('u.name')->orderBy('ts.id');
            $pagination = $q->paginate(25)->withQueryString();
            $rows = collect($pagination->items());
        } else {
            // Top/Bottom: hanya yang punya hasil
            $q->whereNotNull('tr.sjt_results');
            if ($mode === 'top') {
                $q->orderByRaw("{$sumExpr} DESC")->orderBy('u.name')->orderBy('ts.id');
            } else {
                $q->orderByRaw("{$sumExpr} ASC")->orderBy('u.name')->orderBy('ts.id');
            }
            $rows = $q->limit($n)->get();
            $pagination = null;
        }

        return view('pic.participants', [
            'events'     => $events,
            'mode'       => $mode,
            'n'          => $n,
            'rows'       => $rows,
            'pagination' => $pagination,
            'filters'    => $filters,
        ]);
    }

    /* ============================================================
     | 3) Action: buka/stream Result PDF (aman)
     | - verifikasi session.event_id ∈ event milik PIC
     | - dukung URL/storage/local file
     * ============================================================ */
    public function participantResultPdf(int $sessionId)
    {
        $allowed = $this->picEventIds(Auth::id());

        $row = DB::table('test_sessions as ts')
            ->leftJoin('test_results as tr', 'tr.session_id', '=', 'ts.id')
            ->where('ts.id', $sessionId)
            ->when($allowed, fn($q) => $q->whereIn('ts.event_id', $allowed))
            ->select(['ts.id as session_id', 'ts.event_id', 'tr.pdf_path'])
            ->first();

        if (!$row || empty($row->pdf_path)) {
            abort(404, 'Result PDF tidak ditemukan / akses tidak diizinkan.');
        }

        $path = $row->pdf_path;

        // URL eksternal → redirect
        if (preg_match('~^https?://~i', $path)) {
            return redirect()->away($path);
        }
        // Storage disk
        if (Storage::exists($path)) {
            return Response::make(Storage::get($path), 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="result-' . $row->session_id . '.pdf"',
            ]);
        }
        // Path lokal
        if (is_file($path)) {
            return Response::make(file_get_contents($path), 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="result-' . $row->session_id . '.pdf"',
            ]);
        }
        abort(404, 'File PDF tidak ditemukan.');
    }
}
