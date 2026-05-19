@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Carbon;

    // PERBAIKAN UTAMA: Ambil kolom 'nama' dari tabel program bawaan DB baru
    $eventName = optional(optional($r->testSession)->program)->nama;

    // PERBAIKAN TANGGAL: Menggunakan properti laporan_dibuat_pada atau fallback ke created_at
    $rawGenAt = $r->laporan_dibuat_pada ?? $r->created_at;
    $genAt = $rawGenAt ? Carbon::parse($rawGenAt) : null;

    // Cek keberadaan file fisik PDF menggunakan kolom baru pdf_path
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
                    @php
                       // Sinkronisasi kolom email terkirim baru
                       $rawEmailTime = $r->email_sent_at;
                       $emailTime = $rawEmailTime ? Carbon::parse($rawEmailTime) : null;
                    @endphp
                    {{ $emailTime ? $emailTime->setTimezone('Asia/Makassar')->format('d M Y H:i') . ' WITA' : '—' }}
                </strong>
            </div>
        </div>
    </div>
</div>
