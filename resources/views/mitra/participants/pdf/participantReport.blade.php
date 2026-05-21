@php
    $logoPath = public_path('assets/public/images/logo-bcti1.png');
    $logoBase64 = '';

    if (file_exists($logoPath)) {
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($logoPath);

        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
@endphp

@php
    $reportTitle = $reportTitle ?? 'Laporan Peserta';
    $generatedBy = $generatedBy ?? 'Mitra';
    $generatedAt = $generatedAt ?? now()->format('d/m/Y H:i');

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

    @page {
        size: A4 portrait;
        margin: 18mm 14mm 16mm 14mm;
    }

    body {
        font-family: "Times New Roman", Times, serif;
        font-size: 11px;
        color: #111;
    }

    /* --- HEADER --- */

    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }

    .header {
        margin-bottom: 8px;
    }

    .h-left {
        float: left;
        width: 38%;
    }

    .h-right {
        float: right;
        width: 60%;
        text-align: right;
    }

    .h-logo {
        height: 72px;
        width: auto;
    }

    .company-name {
        font-weight: 700;
        font-size: 14px;
        letter-spacing: .25px;
    }

    .company-sub {
        font-size: 11px;
        line-height: 1.35;
        color: #222;
    }

    .divider {
        border: 0;
        border-top: 2px solid #000;
        margin: 6px 0 12px;
    }

    /* --- TITLE --- */

    .title-wrap {
        text-align: center;
        margin: 4px 0 10px;
    }

    .title {
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
        margin: 0 0 4px;
    }

    .subtitle {
        font-size: 11px;
        color: #333;
    }

    /* --- TABLE --- */

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        margin-top: 10px;
        table-layout: fixed;
    }

    thead th {
        background: #ededed;
        border: 1px solid #000;
        font-weight: 700;
        font-size: 11px;
        padding: 6px;
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

    tbody td.name-col {
        text-align: left;
        font-weight: normal;
    }

    .text-center {
        text-align: center;
    }

    .footer {
        position: fixed;
        bottom: -10mm;
        left: 0;
        right: 0;
        text-align: right;
        font-size: 11px;
        color: #6b7280;
    }

    .pagenum:before {
        content: counter(page);
    }

</style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header clearfix">

        <div class="h-left">

            @if(!empty($logoBase64))

                <img class="h-logo"
                     src="{{ $logoBase64 }}"
                     alt="BCTI">

            @else

                <strong class="company-name"
                        style="font-size: 24px;">

                    BCTI
                </strong>

            @endif

        </div>

        <div class="h-right">

            <div class="company-name">
                {{ $companyName }}
            </div>

            <div class="company-sub">
                {!! $companyAddr1 !!}<br>
                {!! $companyAddr2 !!}<br>
                {!! $companyContact !!}
            </div>

        </div>

    </div>

    <hr class="divider">

    {{-- TITLE --}}
    <div class="title-wrap">

        <div class="title">
            {{ $reportTitle }}
        </div>

        <div class="subtitle">
            Dicetak oleh:
            {{ $generatedBy }}

            &nbsp;•&nbsp;

            {{ $generatedAt }}
        </div>

    </div>

    {{-- TABLE --}}
    <table>

        <thead>
            <tr>
                <th style="width:5%;">No.</th>
                <th style="width:25%;">Nama Peserta</th>
                <th style="width:15%;">No. Kontak</th>
                <th style="width:20%;">Program</th>
                <th style="width:17%;">Instansi</th>
                <th style="width:18%;">Jabatan</th>
            </tr>
        </thead>

        <tbody>

            @php $no = 1; @endphp

            @forelse($rows as $result)

                <tr>

                    <td class="text-center">
                        {{ $no++ }}
                    </td>

                    <td class="name-col">

                        <strong>
                            {{ $result->name ?? '-' }}
                        </strong>

                        <br>

                        <span style="color:#555;">
                            {{ $result->email ?? '' }}
                        </span>

                    </td>

                    <td class="text-center">
                        {{ $result->phone_number ?? '-' }}
                    </td>

                    <td>
                        {{ $result->program_name ?? '-' }}
                    </td>

                    <td>
                        {{ $result->instansi ?? '-' }}
                    </td>

                    <td>
                        {{ $result->jabatan ?? '-' }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6"
                        class="text-center"
                        style="padding:20px;">

                        Tidak ada data peserta untuk program Anda.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

    {{-- FOOTER --}}
    <div class="footer">
        Halaman <span class="pagenum"></span>
    </div>

</body>
</html>
