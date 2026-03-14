@php
    use Carbon\Carbon;

    // --- 1. Setup Logo ---
    $logoPath = public_path('assets/public/images/logo-bcti1.png');
    $logoBase64 = '';
    if (file_exists($logoPath)) {
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($logoPath);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    // --- 2. Setup Tanggal & Lokasi ---
    Carbon::setLocale('id');
    $currentDate  = Carbon::now()->isoFormat('D MMMM Y'); // Untuk Tanda Tangan
    $generatedAt  = Carbon::now()->isoFormat('D MMMM Y'); // Untuk Header
    $cityLocation = 'Barito Kuala';

    // --- 3. Data Default ---
    $generatedBy    = $generatedBy ?? 'Admin';
    $companyName    = 'BUSINESS & COMMUNICATION TRAINING INSTITUTE';
    $companyAddr1   = 'Kompleks Sekolah Global Islamic Boarding School (GIBS)';
    $companyAddr2   = 'Gedung Nurhayati Kampus GIBS, Jl. Trans - Kalimantan Lantai 2,';
    $companyAddr3   = 'Sungai Lumbah, Kec. Alalak, Kab. Barito Kuala, Kalimantan Selatan 70582';
    $companyContact = 'Email : bcti@hasnurcentre.org | Website: bcti.id';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>{{ $reportTitle }}</title>
<style>
  @page { size: A4 portrait; margin: 20mm 15mm 15mm 15mm; }
  body { font-family: "Times New Roman", Times, serif; font-size: 11px; color: #000; line-height: 1.3; }

  .clearfix:after { content:""; display: table; clear: both; }
  .text-center{ text-align:center; }

  /* Header */
  .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
  .h-left  { float:left;  width:12%; }
  .h-right { float:right; width:88%; text-align:right; }
  .h-logo  { height: 65px; width: auto; }
  .company-name { font-weight: bold; font-size: 14px; text-transform:uppercase; letter-spacing:0.5px; }
  .company-sub  { font-size: 10px; }

  /* Title */
  .title-wrap { text-align:center; margin-bottom: 25px; }
  .title   { font-size: 16px; font-weight:700; text-transform:uppercase; margin-bottom: 5px; text-decoration: underline; }
  .subtitle{ font-size: 11px; }

  /* Table */
  table { width:100%; border-collapse: collapse; margin-bottom: 10px; }
  thead th { background:#e0e0e0; border:1px solid #000; font-weight:700; font-size: 11px; padding: 8px 5px; text-align:center; }
  tbody td { border:1px solid #000; padding: 6px 5px; vertical-align: top; }

  /* Footer */
  .footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: right; font-size: 9px; font-style: italic; }
  .pagenum:before { content: counter(page); }

  /* Signature */
  .signature-table { width: 100%; margin-top: 40px; border: none; page-break-inside: avoid; }
  .signature-table td { border: none; padding: 0; vertical-align: top; text-align: center; }
</style>
</head>
<body>

  <div class="header clearfix">
    <div class="h-left">
      @if(!empty($logoBase64)) <img class="h-logo" src="{{ $logoBase64 }}" alt="BCTI"> @else <strong>BCTI</strong> @endif
    </div>
    <div class="h-right">
      <div class="company-name">{{ $companyName }}</div>
      <div class="company-sub">{{ $companyAddr1 }}<br>{{ $companyAddr2 }}<br>{{ $companyAddr3 }}<br>{{ $companyContact }}</div>
    </div>
  </div>

  <div class="title-wrap">
    <div class="title">{{ $reportTitle }}</div>
    <div class="subtitle">Versi: {{ $versionName }} • Dicetak oleh: {{ $generatedBy }} • {{ $generatedAt }}</div>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width:30px;">No.</th>
        <th>Pernyataan (Statement)</th>
        <th style="width:15%;">Tipologi</th>
        <th style="width:10%;">Kode</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $q)
        <tr>
          <td class="text-center">{{ $q->number }}</td>
          <td>{{ $q->statement }}</td>
          <td>{{ $q->typologyDescription->typology_name ?? '-' }}</td>
          <td class="text-center">{{ $q->typology_code }}</td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center" style="padding:15px;">Tidak ada data soal pada versi ini.</td></tr>
      @endforelse
    </tbody>
  </table>

  <table class="signature-table">
    <tr>
      <td style="width: 60%;"></td>
      <td style="width: 40%;">
        <div style="margin-bottom: 5px;">{{ $cityLocation }}, {{ $currentDate }}</div>
        <div style="margin-bottom: 60px;">Mengetahui, Pimpinan Unit</div>
        <div style="font-weight: bold; text-decoration: underline;">Muhammad Zain Mahbuby, B.Eng</div>
        <div>Koordinator BCTI</div>
      </td>
    </tr>
  </table>

  <div class="footer">Halaman <span class="pagenum"></span></div>
</body>
</html>
