@php
  $reportTitle   = $reportTitle ?? 'Events Report';
  $generatedBy   = $generatedBy ?? 'Admin';
  $generatedAt   = $generatedAt ?? now()->format('d M Y H:i') . ' WIB';

  $companyName   = 'BUSINESS & COMMUNICATION TRAINING INSTITUTE';
  $companyAddr1  = 'Kompleks Sekolah Global Islamic Boarding School (GIBS)';
  $companyAddr2  = 'Gedung Nurhayati Kampus GIBS, Jl. Trans-Kalimantan, Sungai Lumbah, Alalak, Barito Kuala, Kalimantan Selatan, 70582';
  $companyContact= 'Email: bcti@hasnurcentre.org | website: bcti.id';

  $logoPath = public_path('assets/public/images/logo-bcti.png');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>{{ $reportTitle }}</title>
<style>
  @page { size: A4 landscape; margin: 18mm 14mm 16mm 14mm; }
  body { font-family: "Times New Roman", Times, serif; font-size: 12px; color: #111; }

  .clearfix:after { content:""; display: table; clear: both; }

  .header { margin-bottom: 8px; }
  .h-left  { float:left;  width:38%; }
  .h-right { float:right; width:60%; text-align:right; }
  .h-logo  { height: 64px; } /* sedikit lebih kecil */
  .company-name { font-weight: 700; font-size: 13px; letter-spacing:.25px; } /* turun dari 14 → 13 */
  .company-sub  { font-size: 9.5px; line-height:1.35; color:#222; }          /* diperkecil dari 11 → 9.5 */
  .divider { border:0; border-top:2px solid #000; margin: 6px 0 12px; }

  .title-wrap { text-align:center; margin: 4px 0 10px; }
  .title   { font-size: 17px; font-weight:700; text-transform:uppercase; margin:0 0 4px; }
  .subtitle{ font-size: 11px; color:#333; }

  table { width:100%; border-collapse: collapse; background:#fff; }
  thead th {
    background:#ededed; border:1px solid #000; font-weight:700; font-size: 12px; padding:6px 7px; text-align:left;
  }
  tbody td { border:1px solid #000; padding:6px 7px; vertical-align: top; font-size: 12px; }
  .text-right { text-align:right; }
  .text-center{ text-align:center; }
  .muted { color:#6b7280; }

  .footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: right; font-size: 11px; color:#6b7280; }
  .pagenum:before { content: counter(page); }
</style>
</head>
<body>

  <div class="header clearfix">
    <div class="h-left">
      @if(file_exists($logoPath))
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

  <div class="title-wrap">
    <div class="title">{{ $reportTitle }}</div>
    <div class="subtitle">
      Printed by: {{ $generatedBy }} • {{ $generatedAt }}
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width:26px;" class="text-center">No.</th>
        <th style="width:24%;">Event Name</th>
        <th style="width:18%;">Company</th>
        <th style="width:12%;">Code</th>
        <th style="width:18%;">Date Range</th>
        <th style="width:14%;">PIC</th>
        <th style="width:80px;" class="text-center">Status</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1; @endphp
      @forelse(($rows ?? []) as $ev)
        <tr>
          <td class="text-center">{{ $no++ }}</td>
          <td>{{ $ev->name }}</td>
          <td>{{ $ev->company ?? '—' }}</td>
          <td>{{ $ev->event_code }}</td>
          <td>
            {{ optional($ev->start_date)->format('d M Y') }} - {{ optional($ev->end_date)->format('d M Y') }}
          </td>
          <td>
            @if($ev->pic)
              {{ $ev->pic->name }}
              @if($ev->pic->email)
                <div class="muted">{{ $ev->pic->email }}</div>
              @endif
            @else
              <span class="muted">—</span>
            @endif
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

  <div class="footer">Page <span class="pagenum"></span></div>

</body>
</html>
