<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

// PDF (barryvdh/laravel-dompdf)
use Barryvdh\DomPDF\Facade\Pdf;

class EventController extends Controller
{
    /**
     * Display events listing with search and filtering
     */
    public function index(Request $request)
    {
        $query = Event::with(['pic', 'participants']);

        // Search: name, event_code, description, company, PIC name
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('event_code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('pic', function ($picQuery) use ($search) {
                        $picQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter (menerima '1'/'0' atau 'active'/'inactive')
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'active' || $status === '1' || $status === 1) {
                $query->where('is_active', true);
            } elseif ($status === 'inactive' || $status === '0' || $status === 0) {
                $query->where('is_active', false);
            }
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

        $events = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());

        // Dropdown PIC
        $pics = User::where('role', 'pic')->where('is_active', true)->orderBy('name')->get();

        // Statistics (dashboard mini)
        $stats = [
            'total'    => Event::count(),
            'active'   => Event::where('is_active', true)->count(),
            'ongoing'  => Event::where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())->count(),
            'upcoming' => Event::where('is_active', true)
                ->where('start_date', '>', now())->count(),
        ];

        return view('admin.events.index', compact('events', 'pics', 'stats'));
    }

    /**
     * Show create event form
     */
    public function create()
    {
        $pics = User::where('role', 'pic')->where('is_active', true)->orderBy('name')->get();
        return view('admin.events.create', compact('pics'));
    }

    /**
     * Store new event
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'company'          => ['nullable', 'string', 'max:100'], // NEW
            'description'      => ['nullable', 'string', 'max:1000'],
            'event_code'       => ['required', 'string', 'max:15', 'unique:events,event_code'],
            'start_date'       => ['required', 'date', 'after_or_equal:today'],
            'end_date'         => ['required', 'date', 'after_or_equal:start_date'],
            'pic_id'           => ['nullable', 'exists:users,id'],
            'max_participants' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'is_active'        => ['boolean'],
        ], [
            'name.required'          => 'Event name is required.',
            'name.max'               => 'Event name cannot exceed 100 characters.',
            'event_code.required'    => 'Event code is required.',
            'event_code.unique'      => 'Event code already exists.',
            'start_date.required'    => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date cannot be in the past.',
            'end_date.required'      => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'pic_id.exists'          => 'Selected PIC is invalid.',
            'max_participants.min'   => 'Maximum participants must be at least 1.',
            'max_participants.max'   => 'Maximum participants cannot exceed 1000.',
        ]);

        $eventId = $this->generateEventId();

        $event = Event::create([
            'id'               => $eventId,
            'name'             => $request->name,
            'company'          => $request->company,   // NEW
            'description'      => $request->description,
            'event_code'       => strtoupper($request->event_code),
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'pic_id'           => $request->pic_id,
            'max_participants' => $request->max_participants,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', "Event '{$event->name}' created successfully!");
    }

    /**
     * Show event details
     */
    public function show(Event $event)
    {
        $event->load(['pic', 'participants']);

        $stats = [
            'total_participants' => $event->participants()->count(),
            'completed_tests'    => $event->participants()->where('test_completed', true)->count(),
            'pending_tests'      => $event->participants()->where('test_completed', false)->count(),
            'results_sent'       => $event->participants()->where('results_sent', true)->count(),
        ];

        return view('admin.events.show', compact('event', 'stats'));
    }

    /**
     * Show edit event form
     */
    public function edit(Event $event)
    {
        $pics = User::where('role', 'pic')->where('is_active', true)->orderBy('name')->get();
        return view('admin.events.edit', compact('event', 'pics'));
    }

    /**
     * Update event
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'company'          => ['nullable', 'string', 'max:100'], // NEW
            'description'      => ['nullable', 'string', 'max:1000'],
            'event_code'       => ['required', 'string', 'max:15', Rule::unique('events', 'event_code')->ignore($event->id)],
            'start_date'       => ['required', 'date'],
            'end_date'         => ['required', 'date', 'after_or_equal:start_date'],
            'pic_id'           => ['nullable', 'exists:users,id'],
            'max_participants' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'is_active'        => ['boolean'],
        ]);

        $event->update([
            'name'             => $request->name,
            'company'          => $request->company,   // NEW
            'description'      => $request->description,
            'event_code'       => strtoupper($request->event_code),
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'pic_id'           => $request->pic_id,
            'max_participants' => $request->max_participants,
            'is_active'        => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.events.show', $event)
            ->with('success', "Event '{$event->name}' updated successfully!");
    }

    /**
     * Delete event
     */
    public function destroy(Event $event)
    {
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
        $event->update(['is_active' => ! $event->is_active]);

        $status = $event->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Event '{$event->name}' has been {$status}.");
    }

    /**
     * Export current filtered list to PDF (A4 Landscape)
     * Respect filter yang sama dengan index()
     */
    public function exportPdf(Request $request)
    {
        // Gunakan withCount untuk menghitung jumlah relasi 'participants'
        // Hasilnya akan disimpan dalam properti 'participants_count' secara default
        $query = Event::with(['pic'])->withCount('participants') // <-- TAMBAHKAN withCount INI
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = trim($request->search);
                $q->where(function ($w) use ($s) {
                    $w->where('name', 'like', "%{$s}%")
                        ->orWhere('company', 'like', "%{$s}%")
                        ->orWhere('event_code', 'like', "%{$s}%")
                        ->orWhere('description', 'like', "%{$s}%")
                        ->orWhereHas('pic', function ($picQuery) use ($s) {
                            $picQuery->where('name', 'like', "%{$s}%");
                        });
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $status = $request->status;
                if ($status === 'active' || $status === '1' || $status === 1) {
                    $q->where('is_active', true);
                } elseif ($status === 'inactive' || $status === '0' || $status === 0) {
                    $q->where('is_active', false);
                }
            })
            ->when($request->filled('date_from'), fn($q) => $q->where('start_date', '>=', request('date_from')))
            ->when($request->filled('date_to'),   fn($q) => $q->where('end_date',   '<=', request('date_to')))
            ->when($request->filled('pic_id'),    fn($q) => $q->where('pic_id', request('pic_id')))
            ->orderBy('start_date', 'asc'); // Urutkan query jika perlu

        $rows = $query->get(); // Jalankan query

        // Ubah nama properti 'participants_count' menjadi 'total_participants' agar sesuai view
        // Jika Anda mau, bisa langsung pakai 'participants_count' di view
        $rows->each(function ($row) {
            $row->total_participants = $row->participants_count;
        });


        $pdf = Pdf::loadView('admin.events.pdf.list', [ // Pastikan nama view PDF sudah benar
            'reportTitle' => 'Events Report',
            'generatedBy' => $request->user()?->name ?? 'System',
            'generatedAt' => now('Asia/Makassar')->format('d M Y H:i') . ' WITA', // Sesuaikan timezone
            'rows'        => $rows, // Kirim data $rows yang sudah ada total pesertanya
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Report Event.pdf');
    }

    /**
     * Generate unique event ID (EVT00..EVT99)
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
        $lastMonth    = now()->subMonth()->format('Y-m');

        return response()->json([
            'total_events'   => Event::count(),
            'active_events'  => Event::where('is_active', true)->count(),
            'ongoing_events' => Event::where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())->count(),
            'upcoming_events' => Event::where('is_active', true)
                ->where('start_date', '>', now())->count(),
            'this_month'     => Event::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])->count(),
            'last_month'     => Event::whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$lastMonth])->count(),
        ]);
    }
}
