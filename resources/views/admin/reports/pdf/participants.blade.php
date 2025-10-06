@php
    /**
     * INPUT dari controller:
     * - $rows : iterable (Collection/array) berisi objek/array dengan:
     *           name, email, instansi, event_name (opsional), event_code (opsional), sum_top3
     * - $mode : 'all'|'top'|'bottom' (opsional; default ambil dari query)
     * - $n    : integer untuk Top/Bottom (opsional; default ambil dari query)
     * - $reportTitle, $generatedBy, $generatedAt (opsional)
     */

    $reportTitle = $reportTitle ?? 'Laporan Peserta';
    $generatedBy = $generatedBy ?? (auth()->user()->name ?? 'System');
    $generatedAt = $generatedAt ?? now()->format('d M Y H:i') . ' WIB';

    $mode = $mode ?? request('mode','all');                 // 'all'|'top'|'bottom'
    $n    = isset($n) ? (int)$n : (int) request('n', 10);

    if ($mode === 'top') {
        $modeText = "Top ({$n}) peserta";
    } elseif ($mode === 'bottom') {
        $modeText = "Bottom ({$n}) peserta";
    } else {
        $modeText = "Semua peserta, urut dari skor tertinggi";
    }

    // Header perusahaan
    $logoPath       = public_path('assets/public/images/logo-bcti.png');
    $companyName    = 'BUSINESS & COMMUNICATION TRAINING INSTITUTE';
    $companyAddr1   = 'kompleks sekolah Global Islamic Boarding School (GIBS)';
    $companyAddr2   = 'Gedung Nurhayati Kampus GIBS, Jl. Trans - Kalimantan Lantai 2, Sungai Lumbah, Kec. Alalak, Kabupaten Barito Kuala, Kalimantan Selatan, indonesia 70582';
    $companyContact = 'Email :bcti@hasnurcentre.org | website: bcti.id';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>{{ $reportTitle }}</title>
<style>
  /* ===== Halaman A4 & font Times ===== */
  @page { size: A4; margin: 20mm 15mm 18mm 15mm; }
  body { font-family: "Times New Roman", Times, serif; font-size: 12px; color: #111; }
  .clearfix:after { content:""; display: table; clear: both; }

  /* Header */
  .header   { margin-bottom: 8px; }
  .h-left   { float:left;  width:38%; }
  .h-right  { float:right; width:60%; text-align:right; }
  .h-logo   { height: 68px; } /* logo lebih besar */
  .company-name { font-weight: 700; font-size: 14px; letter-spacing:.2px; }
  .company-sub  { font-size: 11px; line-height:1.35; color:#222; }
  .divider { border:0; border-top:2px solid #000; margin: 6px 0 12px; } /* garis pembatas header hitam */

  /* Title */
  .title-wrap { text-align:center; margin: 4px 0 10px; }
  .title   { font-size: 18px; font-weight:700; text-transform:uppercase; margin:0 0 4px; }
  .subtitle{ font-size: 11px; color:#333; }

  /* Table: putih, garis hitam, header abu */
  table { width:100%; border-collapse: collapse; background:#fff; }
  thead th {
    background:#ededed;          /* abu muda header */
    border:1px solid #000;       /* garis hitam */
    font-weight:700;
    font-size: 12px;
    padding:7px 8px;
    text-align:left;
  }
  tbody td {
    background:#fff;             /* putih */
    border:1px solid #000;       /* garis hitam */
    padding:7px 8px;
    vertical-align: top;
    font-size: 12px;
  }
  .text-right { text-align:right; }
  .text-center{ text-align:center; }
  .muted { color:#6b7280; }

  /* Footer nomor halaman */
  .footer {
    position: fixed;
    bottom: -10mm;
    left: 0; right: 0;
    text-align: right;
    font-size: 11px;
    color:#6b7280;
  }
  .pagenum:before { content: counter(page); }
</style>
</head>
<body>

  {{-- HEADER --}}
  <div class="header clearfix">
    <div class="h-left">
      @if(is_string($logoPath) && file_exists($logoPath))
        <img class="h-logo" src="{{ $logoPath }}" alt="BCTI">
      @else
        <strong class="company-name">BCTI</strong>
      @endif
    </div>
    <div class="h-right">
      <div class="company-name">{{ $companyName }}</div>
      <div class="company-sub">
        {{ $companyAddr1 }}<br>
        {{ $companyAddr2 }}<br>
        {{ $companyContact }}
      </div>
    </div>
  </div>

  <hr class="divider">

  {{-- JUDUL --}}
  <div class="title-wrap">
    <div class="title">{{ $reportTitle }}</div>
    <div class="subtitle">
      {{ $modeText }} &nbsp;•&nbsp; Dicetak oleh: {{ $generatedBy }} &nbsp;•&nbsp; {{ $generatedAt }}
    </div>
  </div>

  {{-- TABEL --}}
  <table>
    <thead>
      <tr>
        <th style="width:28px;" class="text-center">No.</th>
        <th style="width:22%;">Nama</th>
        <th style="width:26%;">Email</th>
        <th style="width:18%;">Instansi</th>
        <th>Event</th>
        <th style="width:55px;" class="text-center">Skor</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1; @endphp
      @forelse($rows ?? [] as $r)
        <tr>
          <td class="text-center">{{ $no++ }}</td>
          <td>{{ is_array($r) ? ($r['name'] ?? '—') : ($r->name ?? '—') }}</td>
          <td class="muted">
            {{ is_array($r) ? ($r['email'] ?? '—') : ($r->email ?? '—') }}
          </td>
          <td>{{ is_array($r) ? ($r['instansi'] ?? '—') : ($r->instansi ?? '—') }}</td>
          <td>
            @php
              $eventName = is_array($r) ? ($r['event_name'] ?? null) : ($r->event_name ?? null);
              $eventCode = is_array($r) ? ($r['event_code'] ?? null) : ($r->event_code ?? null);
            @endphp
            {{ $eventName ?: ($eventCode ?: '—') }}
            @if(!empty($eventCode))
              <div class="muted" style="font-size:11px;">{{ $eventCode }}</div>
            @endif
          </td>
          <td class="text-center">
            @php $score = (int) (is_array($r) ? ($r['sum_top3'] ?? 0) : ($r->sum_top3 ?? 0)); @endphp
            <strong>{{ $score }}</strong>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center muted" style="padding:14px;">Tidak ada data.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- FOOTER --}}
  <div class="footer">Halaman <span class="pagenum"></span></div>

</body>
</html>
