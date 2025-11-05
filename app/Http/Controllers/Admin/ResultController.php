<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateAssessmentReport;
use App\Models\Event;
use App\Models\TestResult;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResultController extends Controller
{
    /** List */
    public function index(Request $request): View
    {
        $query = TestResult::with(['session.user', 'session.event'])
            ->orderBy('report_generated_at', 'desc');

        // search by user
        if ($request->filled('search')) {
            $search = (string) $request->search;
            $query->whereHas('session.user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // by event
        if ($request->filled('event_id')) {
            $eventId = (string) $request->event_id;
            $query->whereHas('session', fn ($q) => $q->where('event_id', $eventId));
        }

        // email status
        if ($request->filled('email_status')) {
            $status = (string) $request->email_status;
            if ($status === 'sent')   $query->whereNotNull('email_sent_at');
            if ($status === 'pending')$query->whereNull('email_sent_at');
        }

        // date range
        if ($request->filled('date_from')) $query->whereDate('report_generated_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('report_generated_at', '<=', $request->date_to);

        // typology
        if ($request->filled('typology'))  $query->where('dominant_typology', (string) $request->typology);

        $results = $query->paginate(20)->withQueryString();

        $events = Event::where('is_active', true)->get(['id','name']);
        $typologies = DB::table('typology_descriptions')
            ->select('typology_code','typology_name')
            ->orderBy('typology_name')
            ->get();

        $stats = [
            'total_results'  => TestResult::count(),
            'emails_sent'    => TestResult::whereNotNull('email_sent_at')->count(),
            'emails_pending' => TestResult::whereNull('email_sent_at')->count(),
            'this_month'     => TestResult::whereMonth('report_generated_at', now()->month)->count(),
        ];

        return view('admin.results.index', compact('results','events','typologies','stats'));
    }

    /** Detail */
    public function show(TestResult $testResult): View
    {
        $testResult->load([
            'session.user',
            'session.event',
            'session.st30Responses.questionVersion',
            'session.sjtResponses',
            'dominantTypologyDescription',
        ]);

        $st30Results = $testResult->st30_results ?? [];
        $sjtResults  = $testResult->sjt_results ?? [];

        $st30Details = [
            'strengths'        => $st30Results['strengths'] ?? [],
            'weakness'         => $st30Results['weakness'] ?? [],
            'total_responses'  => $testResult->session->st30Responses()->where('for_scoring', true)->count(),
        ];

        $sjtDetails = [
            'top3'             => $sjtResults['top3'] ?? [],
            'bottom3'          => $sjtResults['bottom3'] ?? [],
            'total_responses'  => $testResult->session->sjtResponses()->count(),
        ];

        $pdfExists = !empty($testResult->pdf_path) && Storage::disk('local')->exists($testResult->pdf_path);

        return view('admin.results.show', compact('testResult','st30Details','sjtDetails','pdfExists'));
    }

    /** Kirim / resend email */
    public function sendResult(Request $request, TestResult $testResult): RedirectResponse
    {
        try {
            $testResult->load('session.user');

            $user = optional($testResult->session)->user;

            // Pastikan PDF tersedia; kalau hilang → regenerate sinkron
            if (empty($testResult->pdf_path) || !Storage::disk('local')->exists($testResult->pdf_path)) {
                if ($testResult->session_id) {
                    GenerateAssessmentReport::dispatchSync($testResult->session_id);
                    $testResult->refresh();
                }
            }

            if (!$user || empty($testResult->pdf_path) || !Storage::disk('local')->exists($testResult->pdf_path)) {
                return back()->with('error', 'User atau file PDF tidak ditemukan. Regenerate report bila perlu.');
            }

            $pdfPath = Storage::disk('local')->path($testResult->pdf_path);

            Mail::raw(
                "Halo {$user->name},\n\nBerikut hasil Talent Assessment Anda terlampir.\n\nTerima kasih.\n\n-- Tim Talent Mapping",
                function ($message) use ($user, $pdfPath) {
                    $message->to($user->email, $user->name)
                        ->subject('Hasil Talent Assessment')
                        ->attach($pdfPath);
                }
            );

            $testResult->update(['email_sent_at' => now()]);

            return back()->with('success', "Email hasil berhasil dikirim ke {$user->email}");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    /** Regenerate PDF */
    public function regeneratePdf(TestResult $testResult): RedirectResponse
    {
        try {
            // Sinkron agar PDF langsung tersedia (penting saat admin mau langsung kirim)
            GenerateAssessmentReport::dispatchSync($testResult->session_id);
            $testResult->refresh();

            return back()->with('success', 'PDF berhasil digenerate ulang.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal me-regenerate PDF: ' . $e->getMessage());
        }
    }

    /** Download PDF */
public function downloadPdf(TestResult $testResult)
    {
        // TestResult Model sudah memiliki relasi ke Session dan User
        $testResult->load('session.user');

        // Cek path PDF dari result
        if (empty($testResult->pdf_path)) {
            return back()->with('error', 'PDF file path is empty.');
        }

        $path = $testResult->pdf_path;
        $sessionId = optional($testResult->session)->id ?? $testResult->session_id;

        // --- 1. Priority: External Public URL (Supabase/S3 Redirect) ---
        // Jika path adalah URL, langsung redirect (atau jika kita konstruksi URL Supabase/S3)
        if (preg_match('~^https?://~i', $path)) {
            return redirect()->away($path);
        }

        // Coba konstruksi URL publik dari env (seperti di GenerateAssessmentReport)
        $base = rtrim(config('filesystems.disks.s3.url') ?? env('AWS_URL', ''), '/');
        $bucket = trim(config('filesystems.disks.s3.bucket') ?? env('AWS_BUCKET', ''), '/');

        if (!empty($base) && !empty($bucket)) {
             // Konstruksi URL publik Supabase/S3 (format: BASE/storage/v1/object/public/BUCKET/PATH)
             $publicUrl = "{$base}/storage/v1/object/public/{$bucket}/{$path}";
             return redirect()->away($publicUrl);
        }

        // --- 2. Fallback: Stream content from Disk ('s3' or 'local') ---
        $fileName = 'result-' . $sessionId . '.pdf';

        // Iterasi melalui disk yang mungkin (S3/Supabase default, local)
        foreach ([config('filesystems.default'), 'local'] as $disk) {
             if (!$disk) continue;
             try {
                 if (Storage::disk($disk)->exists($path)) {
                     $content = Storage::disk($disk)->get($path);

                     return Response::make($content, 200, [
                         'Content-Type'=> 'application/pdf',
                         'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                     ]);
                 }
             } catch (\Throwable $e) {
                 // Log the error and continue to the next disk
                 \Illuminate\Support\Facades\Log::warning("PDF Stream failed from disk {$disk}: " . $e->getMessage());
             }
        }

        // --- 3. Final Fallback (Coba path langsung jika semua gagal) ---
        if (is_file($path)) {
             return Response::make(file_get_contents($path), 200, [
                 'Content-Type'=> 'application/pdf',
                 'Content-Disposition' => 'inline; filename="' . $fileName . '"',
             ]);
        }

        abort(404, 'File PDF tidak ditemukan.');
    }

    /** Bulk action */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action'             => 'required|in:send_email,delete',
            'selected_results'   => 'required|array|min:1',
            'selected_results.*' => 'exists:test_results,id',
        ]);

        $results = TestResult::whereIn('id', $request->selected_results)->get();

        switch ($request->action) {
            case 'send_email':
                $sent = 0;
                foreach ($results as $result) {
                    try {
                        // hanya kirim bila belum pernah terkirim (sesuai logic kamu)
                        if (
                            !$result->email_sent_at &&
                            !empty($result->pdf_path)
                        ) {
                            // Pastikan file ada; kalau hilang regenerate sinkron
                            if (!Storage::disk('local')->exists($result->pdf_path) && $result->session_id) {
                                GenerateAssessmentReport::dispatchSync($result->session_id);
                                $result->refresh();
                            }

                            if (!Storage::disk('local')->exists($result->pdf_path)) {
                                continue;
                            }

                            $result->load('session.user');
                            $user    = optional($result->session)->user;
                            if (!$user) continue;

                            $pdfPath = Storage::disk('local')->path($result->pdf_path);

                            Mail::raw(
                                "Halo {$user->name},\n\nBerikut hasil Talent Assessment Anda terlampir.\n\nTerima kasih.\n\n-- Tim Talent Mapping",
                                function ($message) use ($user, $pdfPath) {
                                    $message->to($user->email, $user->name)
                                        ->subject('Hasil Talent Assessment')
                                        ->attach($pdfPath);
                                }
                            );

                            $result->update(['email_sent_at' => now()]);
                            $sent++;
                        }
                    } catch (\Throwable $e) {
                        continue;
                    }
                }

                return back()->with('success', "Berhasil mengirim {$sent} email dari " . count($results) . " hasil yang dipilih.");

            case 'delete':
                if (auth()->user()->role !== 'admin') {
                    return back()->with('error', 'Hanya admin yang dapat menghapus hasil.');
                }

                foreach ($results as $result) {
                    if (!empty($result->pdf_path)) {
                        Storage::disk('local')->delete($result->pdf_path);
                    }
                    $result->delete();
                }

                return back()->with('success', count($results) . ' hasil berhasil dihapus.');

            default:
                return back()->with('error', 'Aksi tidak valid.');
        }
    }

    /** Export CSV */
    public function export(Request $request)
    {
        $query = TestResult::with(['session.user', 'session.event']);

        if ($request->filled('search')) {
            $search = (string) $request->search;
            $query->whereHas('session.user', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('event_id')) {
            $eventId = (string) $request->event_id;
            $query->whereHas('session', fn ($q) => $q->where('event_id', $eventId));
        }

        $results = $query->get();

        $csvData = [];
        $csvData[] = [
            'Name',
            'Email',
            'Event',
            'Dominant Typology',
            'Report Generated',
            'Email Sent',
            'ST-30 Strengths',
            'SJT Top Competencies',
        ];

        foreach ($results as $result) {
            $st30Strengths = collect($result->st30_results['strengths'] ?? [])->pluck('name')->join(', ');
            $sjtTop        = collect($result->sjt_results['top3'] ?? [])->pluck('competency')->join(', ');

            $csvData[] = [
                optional($result->session)->participant_name,
                optional(optional($result->session)->user)->email,
                optional(optional($result->session)->event)->name ?? 'N/A',
                optional($result->dominantTypologyDescription)->typology_name ?? $result->dominant_typology,
                $result->report_generated_at ? $result->report_generated_at->format('Y-m-d H:i') : 'N/A',
                $result->email_sent_at ? $result->email_sent_at->format('Y-m-d H:i') : 'Not Sent',
                $st30Strengths,
                $sjtTop,
            ];
        }

        $filename = 'talent-assessment-results-' . now()->format('Y-m-d') . '.csv';

        $output = fopen('php://temp', 'r+');
        foreach ($csvData as $row) fputcsv($output, $row);
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /** Stats JSON */
    public function getStatistics(): JsonResponse
    {
        $monthlyResults = TestResult::select(
            DB::raw('MONTH(report_generated_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('report_generated_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->mapWithKeys(fn ($i) => [Carbon::create()->month($i->month)->format('M') => $i->count]);

        $typologyDistribution = TestResult::join('typology_descriptions', 'test_results.dominant_typology', '=', 'typology_descriptions.typology_code')
            ->select('typology_descriptions.typology_name', DB::raw('COUNT(*) as count'))
            ->groupBy('typology_descriptions.typology_name', 'test_results.dominant_typology')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->mapWithKeys(fn ($i) => [$i->typology_name => $i->count]);

        $emailStatus = [
            'sent'    => TestResult::whereNotNull('email_sent_at')->count(),
            'pending' => TestResult::whereNull('email_sent_at')->count(),
        ];

        return response()->json([
            'monthly_results'       => $monthlyResults,
            'typology_distribution' => $typologyDistribution,
            'email_status'          => $emailStatus,
        ]);
    }
}
