@php
    $logoFile = public_path('assets/public/images/logo-bcti1.png');
    $logoBase64 = '';
    if (file_exists($logoFile)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoFile));
    }
@endphp

@php
  // Default values disalin dari participants PDF
  $reportTitle    = $reportTitle ?? 'Events Report'; // Judul default tetap Events Report
  $generatedBy    = $generatedBy ?? (auth()->user()->name ?? 'Admin');
  $generatedAt    = $generatedAt ?? now('Asia/Makassar')->format('d M Y H:i') . ' WITA'; // Sesuaikan timezone jika perlu

  // Info Perusahaan disalin dari participants PDF
  $companyName    = 'BUSINESS & COMMUNICATION TRAINING INSTITUTE';
  $companyAddr1   = 'kompleks sekolah Global Islamic Boarding School (GIBS)';
  $companyAddr2   = 'Gedung Nurhayati Kampus GIBS, Jl. Trans - Kalimantan Lantai 2, Sungai Lumbah, Kec. Alalak, Kabupaten Barito Kuala, Kalimantan Selatan, Indonesia 70582';
  $companyContact = 'Email : bcti@hasnurcentre.org | website: bcti.id';


@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>{{ $reportTitle }}</title>
<style>
  /* CSS Styles disalin dari participants PDF, dengan penyesuaian ukuran font/padding jika perlu */
  @page { size: A4 landscape; margin: 18mm 14mm 16mm 14mm; }
  body { font-family: "Times New Roman", Times, serif; font-size: 11px; color: #111; } /* Ukuran font base sedikit lebih kecil agar muat */

  .clearfix:after { content:""; display: table; clear: both; }

  /* Header styles disalin dari participants PDF */
  .header { margin-bottom: 8px; }
  .h-left  { float:left;  width:38%; }
  .h-right { float:right; width:60%; text-align:right; }
  .h-logo  { height: 72px; } /* Ukuran logo disamakan */
  .company-name { font-weight: 700; font-size: 14px; letter-spacing:.25px; } /* Font disamakan */
  .company-sub  { font-size: 11px; line-height:1.35; color:#222; } /* Font disamakan */
  .divider { border:0; border-top:2px solid #000; margin: 6px 0 12px; }

  /* Title styles disalin dari participants PDF */
  .title-wrap { text-align:center; margin: 4px 0 10px; }
  .title   { font-size: 18px; font-weight:700; text-transform:uppercase; margin:0 0 4px; } /* Font disamakan */
  .subtitle{ font-size: 11px; color:#333; } /* Font disamakan */

  /* Table styles disesuaikan untuk Events Report */
  table { width:100%; border-collapse: collapse; background:#fff; }
  thead th {
    background:#ededed; border:1px solid #000; font-weight:700; font-size: 11px; padding: 5px 6px; text-align:center; vertical-align: middle; /* Text align center, font kecil */
  }
  tbody td { border:1px solid #000; padding: 5px 6px; vertical-align: top; font-size: 11px; } /* Font kecil */
  tbody td.name-col { text-align:left; } /* Kolom nama event rata kiri */
  .text-right { text-align:right; }
  .text-center{ text-align:center; }
  .muted { color:#6b7280; }

  /* Footer styles disalin dari participants PDF */
  .footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: right; font-size: 11px; color:#6b7280; }
  .pagenum:before { content: counter(page); }
</style>
</head>
<body>

  {{-- Header HTML disalin dari participants PDF --}}
  <div class="header clearfix">
    <div class="h-left">
      @if(file_exists($logoPath))
        <img class="h-logo" src="{{ $logoBase64 }}" alt="BCTI">
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

  {{-- Judul HTML disalin dari participants PDF, $modeText dihapus karena tidak relevan --}}
  <div class="title-wrap">
    <div class="title">{{ $reportTitle }}</div>
    <div class="subtitle">
       Printed by: {{ $generatedBy }}
       &nbsp;•&nbsp; {{ $generatedAt }}
    </div>
  </div>

  {{-- Tabel disesuaikan untuk Events Report --}}
  <table>
    <thead>
      <tr>
        <th style="width:26px;">No.</th>
        <th>Nama Event</th>
        <th style="width:18%;">Company</th>
        <th style="width:15%;">PIC</th>
        <th style="width:18%;">Date Range</th>
        <th style="width:10%;">Total Peserta</th>
        <th style="width:8%;">Status</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1; @endphp
      @forelse(($rows ?? []) as $ev)
        <tr>
          <td class="text-center">{{ $no++ }}</td>
          <td class="name-col">{{ $ev->name }}</td> {{-- Rata kiri --}}
          <td>{{ $ev->company ?? '—' }}</td>
          <td>
            @if($ev->pic)
              {{ $ev->pic->name }}
            @else
              <span class="muted">—</span>
            @endif
          </td>
          <td class="text-center">{{ optional($ev->start_date)->format('d M Y') }} - {{ optional($ev->end_date)->format('d M Y') }}</td>
          <td class="text-center">
             {{-- Menggunakan participants_count dari withCount atau properti yg sudah dimapping --}}
             {{ $ev->participants_count ?? ($ev->total_participants ?? 0) }}
          </td>
          <td class="text-center">
            <strong>{{ $ev->is_active ? 'Active' : 'Inactive' }}</strong>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center muted" style="padding:14px;">No data.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- Footer HTML disalin dari participants PDF --}}
  <div class="footer">Page <span class="pagenum"></span></div>

</body>
</html>
