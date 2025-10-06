@php
  $reportTitle = $reportTitle ?? 'Participants Report';
  $generatedBy = $generatedBy ?? 'PIC';
  $generatedAt = $generatedAt ?? now()->format('d M Y H:i') . ' WIB';

  $companyName   = 'BUSINESS & COMMUNICATION TRAINING INSTITUTE';
  $companyAddr1  = 'kompleks sekolah Global Islamic Boarding School (GIBS)';
  $companyAddr2  = 'Gedung Nurhayati Kampus GIBS, Jl. Trans - Kalimantan Lantai 2, Sungai Lumbah, Kec. Alalak, Kabupaten Barito Kuala, Kalimantan Selatan, Indonesia 70582';
  $companyContact= 'Email : bcti@hasnurcentre.org | website: bcti.id';

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
  .h-logo  { height: 72px; }
  .company-name { font-weight: 700; font-size: 14px; letter-spacing:.25px; }
  .company-sub  { font-size: 11px; line-height:1.35; color:#222; }
  .divider { border:0; border-top:2px solid #000; margin: 6px 0 12px; }

  .title-wrap { text-align:center; margin: 4px 0 10px; }
  .title   { font-size: 18px; font-weight:700; text-transform:uppercase; margin:0 0 4px; }
  .subtitle{ font-size: 11px; color:#333; }

  table { width:100%; border-collapse: collapse; background:#fff; }
  thead th {
    background:#ededed; border:1px solid #000; font-weight:700; font-size: 12px; padding:7px 8px; text-align:left;
  }
  tbody td { border:1px solid #000; padding:7px 8px; vertical-align: top; font-size: 12px; }
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
      {{ $modeText ?? 'All participants — ordered by highest score' }}
      &nbsp;•&nbsp; Printed by: {{ $generatedBy }}
      &nbsp;•&nbsp; {{ $generatedAt }}
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width:28px;" class="text-center">No.</th>
        <th style="width:22%;">Name</th>
        <th style="width:26%;">Email</th>
        <th style="width:18%;">Organization</th>
        <th>Event</th>
        <th style="width:55px;" class="text-center">Score</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1; @endphp
      @forelse(($rows ?? []) as $r)
        <tr>
          <td class="text-center">{{ $no++ }}</td>
          <td>{{ $r->name }}</td>
          <td>{{ $r->email ?? '—' }}</td>
          <td>{{ $r->instansi ?: '—' }}</td>
          <td>
            {{ $r->event_name ?: '—' }}
          </td>
          <td class="text-center"><strong>{{ (int)($r->sum_top3 ?? 0) }}</strong></td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center muted" style="padding:14px;">No data.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="footer">Page <span class="pagenum"></span></div>

</body>
</html>
