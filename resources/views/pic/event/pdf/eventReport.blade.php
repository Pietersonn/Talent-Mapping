@php
    // Definisikan path logo
    $logoPath = public_path('assets/public/images/logo-bcti1.png');
    $logoBase64 = '';

    // Cek apakah file ada, lalu encode ke base64
    if (file_exists($logoPath)) {
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($logoPath);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
@endphp

@php
  $reportTitle    = $reportTitle ?? 'Laporan Event';
  $generatedBy    = $generatedBy ?? (auth()->user()->name ?? 'PIC');
  $generatedAt    = $generatedAt ?? now('Asia/Makassar')->format('d/m/Y H:i') . ' WITA';

  $companyName    = 'BUSINESS & COMMUNICATION TRAINING INSTITUTE';
  $companyAddr1   = 'Kompleks Sekolah Global Islamic Boarding School (GIBS)';
  $companyAddr2   = 'Gedung Nurhayati Kampus GIBS, Jl. Trans - Kalimantan Lantai 2, Sungai Lumbah, Kec. Alalak, Kab. Barito Kuala, Kalimantan Selatan 70582';
  $companyContact = 'Email : bcti@hasnurcentre.org | Website: bcti.id';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>{{ $reportTitle }}</title>
<style>
  @page { size: A4 potrait; margin: 18mm 14mm 16mm 14mm; }
  body { font-family: "Times New Roman", Times, serif; font-size: 11px; color: #111; }

  .clearfix:after { content:""; display: table; clear: both; }

  /* Header styles */
  .header { margin-bottom: 8px; }
  .h-left  { float:left;  width:38%; }
  .h-right { float:right; width:60%; text-align:right; }
  .h-logo  { height: 72px; width: auto; }
  .company-name { font-weight: 700; font-size: 14px; letter-spacing:.25px; }
  .company-sub  { font-size: 11px; line-height:1.35; color:#222; }
  .divider { border:0; border-top:2px solid #000; margin: 6px 0 12px; }

  /* Title styles */
  .title-wrap { text-align:center; margin: 4px 0 10px; }
  .title   { font-size: 18px; font-weight:700; text-transform:uppercase; margin:0 0 4px; }
  .subtitle{ font-size: 11px; color:#333; }

  /* Table styles */
  table { width:100%; border-collapse: collapse; background:#fff; margin-top: 10px; }
  thead th {
    background:#ededed; border:1px solid #000; font-weight:700; font-size: 11px; padding: 6px; text-align:center; vertical-align: middle;
  }
  tbody td { border:1px solid #000; padding: 6px; vertical-align: top; font-size: 11px; }

  /* Hapus bold di kolom nama event */
  tbody td.name-col { text-align:left; font-weight: normal; }

  .text-right { text-align:right; }
  .text-center{ text-align:center; }
  .muted { color:#6b7280; }

  /* Footer styles */
  .footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: right; font-size: 11px; color:#6b7280; }
  .pagenum:before { content: counter(page); }
</style>
</head>
<body>

  {{-- Header HTML --}}
  <div class="header clearfix">
    <div class="h-left">
      @if(!empty($logoBase64))
        <img class="h-logo" src="{{ $logoBase64 }}" alt="BCTI">
      @else
        <strong class="company-name" style="font-size: 24px;">BCTI</strong>
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

  {{-- Judul Laporan --}}
  <div class="title-wrap">
    <div class="title">{{ $reportTitle }}</div>
    <div class="subtitle">
       Dicetak oleh: {{ $generatedBy }}
       &nbsp;•&nbsp; {{ $generatedAt }}
    </div>
  </div>

  {{-- Tabel Data --}}
  <table>
    <thead>
      <tr>
        <th style="width:30px;">No.</th>
        <th>Nama Event</th>
        <th style="width:20%;">Instansi</th>
        {{-- Kolom PIC tetap ada agar layout sama persis, meski isinya nama PIC yang login --}}
        <th style="width:15%;">PIC</th>
        <th style="width:18%;">Tanggal Pelaksanaan</th>
        <th style="width:10%;">Peserta</th>
        <th style="width:8%;">Status</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1; @endphp
      @forelse($rows as $ev)
        <tr>
          <td class="text-center">{{ $no++ }}</td>
          <td class="name-col">
              {{ $ev->name }}
          </td>
          <td>{{ $ev->company ?? '-' }}</td>
          <td>
            {{ $ev->pic->name ?? '-' }}
          </td>
          <td class="text-center">
              {{ \Carbon\Carbon::parse($ev->start_date)->format('d/m/Y') }} -
              {{ \Carbon\Carbon::parse($ev->end_date)->format('d/m/Y') }}
          </td>
          <td class="text-center">
             {{ $ev->participants_count ?? 0 }}
             @if($ev->max_participants)
                <span>/ {{ $ev->max_participants }}</span>
             @endif
          </td>
          <td class="text-center">
            @if($ev->is_active)
                Aktif
            @else
                Tidak Aktif
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center muted" style="padding:20px;">Tidak ada event yang ditemukan.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- Footer --}}
  <div class="footer">Halaman <span class="pagenum"></span></div>

</body>
</html>
