<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    /**
     * Display events listing with search and filtering
     */
    public function index(Request $request)
    {
        $query = Event::with(['pic', 'participants']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('event_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('pic', function ($picQuery) use ($search) {
                      $picQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }

        // PIC filter
        if ($request->filled('pic_id')) {
            $query->where('pic_id', $request->pic_id);
        }

        $events = $query->orderBy('created_at', 'desc')->paginate(15);
        $pics = User::where('role', 'pic')->where('is_active', true)->get();

        // Statistics for dashboard
        $stats = [
            'total' => Event::count(),
            'active' => Event::where('is_active', true)->count(),
            'ongoing' => Event::where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count(),
            'upcoming' => Event::where('is_active', true)
                ->where('start_date', '>', now())
                ->count(),
        ];

        return view('admin.events.index', compact('events', 'pics', 'stats'));
    }

    /**
     * Show create event form
     */
    public function create()
    {
        // Get available PICs (users with role 'pic')
        $pics = User::where('role', 'pic')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.events.create', compact('pics'));
    }

    /**
     * Store new event
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'event_code' => ['required', 'string', 'max:15', 'unique:events,event_code'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'pic_id' => ['nullable', 'exists:users,id'],
            'max_participants' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'is_active' => ['boolean']
        ], [
            'name.required' => 'Event name is required.',
            'name.max' => 'Event name cannot exceed 100 characters.',
            'event_code.required' => 'Event code is required.',
            'event_code.unique' => 'Event code already exists.',
            'start_date.required' => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date cannot be in the past.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'pic_id.exists' => 'Selected PIC is invalid.',
            'max_participants.min' => 'Maximum participants must be at least 1.',
            'max_participants.max' => 'Maximum participants cannot exceed 1000.',
        ]);

        // Generate event ID (5 characters)
        $eventId = $this->generateEventId();

        $event = Event::create([
            'id' => $eventId,
            'name' => $request->name,
            'description' => $request->description,
            'event_code' => strtoupper($request->event_code),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'pic_id' => $request->pic_id,
            'max_participants' => $request->max_participants,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', "Event '{$event->name}' created successfully!");
    }

    /**
     * Show event details
     */
    public function show(Event $event)
    {
        $event->load(['pic', 'participants.user']);

        $stats = [
            'total_participants' => $event->participants()->count(),
            'completed_tests' => $event->participants()->where('test_completed', true)->count(),
            'pending_tests' => $event->participants()->where('test_completed', false)->count(),
            'results_sent' => $event->participants()->where('results_sent', true)->count(),
        ];

        return view('admin.events.show', compact('event', 'stats'));
    }

    /**
     * Show edit event form
     */
    public function edit(Event $event)
    {
        $pics = User::where('role', 'pic')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.events.edit', compact('event', 'pics'));
    }

    /**
     * Update event
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'event_code' => ['required', 'string', 'max:15', Rule::unique('events', 'event_code')->ignore($event->id)],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'pic_id' => ['nullable', 'exists:users,id'],
            'max_participants' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'is_active' => ['boolean']
        ]);

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'event_code' => strtoupper($request->event_code),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'pic_id' => $request->pic_id,
            'max_participants' => $request->max_participants,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.events.show', $event)
            ->with('success', "Event '{$event->name}' updated successfully!");
    }

    /**
     * Delete event
     */
    public function destroy(Event $event)
    {
        // Check if event has participants
        if ($event->participants()->count() > 0) {
            return redirect()->back()
                ->with('error', "Cannot delete event '{$event->name}' because it has participants.");
        }

        $eventName = $event->name;
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', "Event '{$eventName}' deleted successfully!");
    }

    /**
     * Toggle event status
     */
    public function toggleStatus(Event $event)
    {
        $event->update([
            'is_active' => !$event->is_active
        ]);

        $status = $event->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Event '{$event->name}' has been {$status}.");
    }

    /**
     * Generate unique event ID
     */
    private function generateEventId(): string
    {
        do {
            $id = 'EVT' . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
        } while (Event::where('id', $id)->exists());

        return $id;
    }

    /**
     * Get events statistics for dashboard
     */
    public function getStatistics()
    {
        $currentMonth = now()->format('Y-m');
        $lastMonth = now()->subMonth()->format('Y-m');

        return response()->json([
            'total_events' => Event::count(),
            'active_events' => Event::where('is_active', true)->count(),
            'ongoing_events' => Event::where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count(),
            'upcoming_events' => Event::where('is_active', true)
                ->where('start_date', '>', now())
                ->count(),
            'this_month' => Event::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])->count(),
            'last_month' => Event::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$lastMonth])->count(),
        ]);
    }
}
