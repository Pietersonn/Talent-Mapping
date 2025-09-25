<?php

namespace App\Http\Controllers\PIC;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        // Semua event yang dikelola PIC login
        $events = Event::query()
            ->where('pic_id', Auth::id())
            ->orderByDesc('start_date')
            ->paginate(10, [
                'id','name','event_code','start_date','end_date','is_active','max_participants','pic_id'
            ]);

        return view('pic.event.index', compact('events'));
    }

    public function show(Event $event)
    {
        // Batasi akses ke PIC pemilik event
        abort_unless($event->pic_id === Auth::id(), 403);

        // Ambil peserta event tanpa bergantung pada relasi di model (lebih aman)
        $participants = DB::table('event_participants as ep')
            ->join('users as u', 'u.id', '=', 'ep.user_id')
            ->where('ep.event_id', $event->id)
            ->select([
                'ep.id',
                'u.name as name',
                'u.email as email',
                'ep.test_completed',
                'ep.results_sent',
                'ep.created_at'
            ])
            ->orderByDesc('ep.created_at')
            ->paginate(15);

        return view('pic.event.show', compact('event','participants'));
    }
}
