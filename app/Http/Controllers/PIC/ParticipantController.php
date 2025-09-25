<?php

namespace App\Http\Controllers\PIC;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParticipantController extends Controller
{
    public function index()
    {
        // Daftar semua peserta dari event milik PIC
        $eventIds = Event::where('pic_id', Auth::id())->pluck('id');

        $participants = DB::table('event_participants as ep')
            ->join('users as u', 'u.id', '=', 'ep.user_id')
            ->join('events as e', 'e.id', '=', 'ep.event_id')
            ->select('ep.id','u.name','u.email','e.name as event_name','e.event_code','ep.test_completed','ep.results_sent','ep.created_at')
            ->whereIn('ep.event_id', $eventIds)
            ->orderByDesc('ep.created_at')
            ->paginate(15);

        return view('pic.participants.index', compact('participants'));
    }

    public function show(Event $event)
    {
        abort_unless($event->pic_id === Auth::id(), 403);

        $participants = $event->participants()
            ->with(['user:id,name,email'])
            ->orderBy('created_at','desc')
            ->paginate(20);

        return view('pic.participants.show', compact('event','participants'));
    }
}
