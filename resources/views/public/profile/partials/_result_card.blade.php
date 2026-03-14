 @php
     use Illuminate\Support\Facades\Storage;

     // Event & tanggal
     $eventName = optional($r->session->event)->name;
     $genAt = optional($r->report_generated_at);

     // Cek PDF
     $pdfExists = $r->pdf_path ? Storage::disk('public')->exists($r->pdf_path) : false;
 @endphp

 <div class="tmprof-card">
     <div class="tmprof-card__head">
         <h3 class="tmprof-card__title">{{ $eventName ?? 'Hasil Mandiri' }}</h3>
         @if ($genAt)
             <span class="tmprof-card__date">{{ $genAt->format('d M Y') }}</span>
         @endif
     </div>

     <div class="tmprof-card__body">
         <div class="tmprof-meta">
             <div class="tmprof-meta__row">
                 <span>Dikirim via Email</span>
                 <strong>
                     {{ $r->email_sent_at ? $r->email_sent_at->setTimezone('Asia/Makassar')->format('d M Y H:i') . ' WITA' : '—' }}
                 </strong>
             </div>
         </div>
     </div>
 </div>
