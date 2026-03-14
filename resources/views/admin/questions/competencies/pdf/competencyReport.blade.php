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
    $versionName    = $versionName ?? 'Semua Versi'; // Default jika tidak ada filter versi

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
    /* Menggunakan A4 Landscape */
    @page { size: A4 landscape; margin: 20mm 15mm 15mm 15mm; }
    body { font-family: "Times New Roman", Times, serif; font-size: 10px; color: #000; line-height: 1.3; }

    .clearfix:after { content: ""; display: table; clear: both; }

    /* Header Style */
    .header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
    .h-left { float: left; width: 12%; }
    .h-right { float: right; width: 88%; text-align: right; }
    .h-logo { height: 65px; width: auto; }

    .company-name { font-weight: bold; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; }
    .company-sub { font-size: 10px; }

    /* Title Style */
    .title-wrap { text-align: center; margin-bottom: 25px; }
    .title { font-size: 16px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; text-decoration: underline; }
    .subtitle { font-size: 11px; }

    /* Table Style */
    table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }

    thead th {
        background: #e0e0e0;
        border: 1px solid #000;
        font-weight: bold;
        font-size: 10px;
        padding: 8px 4px;
        text-align: center;
        vertical-align: middle;
    }

    tbody td {
        border: 1px solid #000;
        padding: 6px;
        vertical-align: top;
        font-size: 10px;
        word-wrap: break-word;
    }

    /* Helper Classes */
    .text-center { text-align: center; }
    .font-bold { font-weight: bold; }

    /* Signature Style */
    .signature-table { width: 100%; margin-top: 30px; border: none; page-break-inside: avoid; }
    .signature-table td { border: none; padding: 0; vertical-align: top; text-align: center; }

    /* Footer Style */
    .footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: right; font-size: 9px; font-style: italic; }
    .pagenum:before { content: counter(page); }
</style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header clearfix">
        <div class="h-left">
            @if(!empty($logoBase64))
                <img src="{{ $logoBase64 }}" class="h-logo" alt="BCTI">
            @else
                <strong>BCTI</strong>
            @endif
        </div>
        <div class="h-right">
            <div class="company-name">{{ $companyName }}</div>
            <div class="company-sub">{{ $companyAddr1 }}<br>{{ $companyAddr2 }}<br>{{ $companyAddr3 }}<br>{{ $companyContact }}</div>
        </div>
    </div>

    {{-- JUDUL LAPORAN --}}
    <div class="title-wrap">
        <div class="title">LAPORAN KOMPETENSI</div>
        {{-- UPDATE: Menambahkan Versi --}}
        <div class="subtitle">Versi: {{ $versionName }} • Dicetak oleh: {{ $generatedBy }} • {{ $generatedAt }}</div>
    </div>

    {{-- TABEL DATA --}}
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 6%;">Kode</th>
                <th style="width: 14%;">Nama Kompetensi</th>
                <th style="width: 19%;">Kekuatan (Strength)</th>
                <th style="width: 19%;">Kelemahan (Weakness)</th>
                <th style="width: 19%;">Pengembangan</th>
                <th style="width: 19%;">Rekomendasi Training</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-bold">{{ $item->competency_code }}</td>
                    <td class="font-bold">{{ $item->competency_name }}</td>
                    <td>{{ $item->strength_description ?? '-' }}</td>
                    <td>{{ $item->weakness_description ?? '-' }}</td>
                    <td>{{ $item->improvement_activity ?? '-' }}</td>
                    <td>{{ $item->training_recommendations ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center" style="padding:15px;">Tidak ada data kompetensi.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- TANDA TANGAN --}}
    <table class="signature-table">
        <tr>
            <td style="width: 65%;"></td>
            <td style="width: 35%;">
                <div style="margin-bottom: 5px;">{{ $cityLocation }}, {{ $currentDate }}</div>
                <div style="margin-bottom: 60px;">Mengetahui, Pimpinan Unit</div>
                <div style="font-weight: bold; text-decoration: underline;">Muhammad Zain Mahbuby, B.Eng</div>
                <div>Koordinator BCTI</div>
            </td>
        </tr>
    </table>

    {{-- FOOTER NOMOR HALAMAN --}}
    <div class="footer">Halaman <span class="pagenum"></span></div>
</body>
</html>
