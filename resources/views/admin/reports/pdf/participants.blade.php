@php
    $logoFile = public_path('assets/public/images/logo-bcti1.png');
    $logoBase64 = '';
    if (file_exists($logoFile)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoFile));
    }
@endphp


@php
    $reportTitle = $reportTitle ?? 'Participants Competency Report';
    $generatedBy = $generatedBy ?? (auth()->user()->name ?? 'Admin');
    $generatedAt = $generatedAt ?? now()->format('d M Y H:i') . ' WIB';

    $companyName = 'BUSINESS & COMMUNICATION TRAINING INSTITUTE';
    $companyAddr1 = 'kompleks sekolah Global Islamic Boarding School (GIBS)';
    $companyAddr2 =
        'Gedung Nurhayati Kampus GIBS, Jl. Trans - Kalimantan Lantai 2, Sungai Lumbah, Kec. Alalak, Kabupaten Barito Kuala, Kalimantan Selatan, Indonesia 70582';
    $companyContact = 'Email : bcti@hasnurcentre.org | website: bcti.id';
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 18mm 14mm 16mm 14mm;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            color: #111;
        }

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

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        thead th {
            background: #ededed;
            border: 1px solid #000;
            font-weight: 700;
            font-size: 12px;
            padding: 7px 8px;
            text-align: center;
        }

        tbody td {
            border: 1px solid #000;
            padding: 7px 8px;
            vertical-align: middle;
            font-size: 12px;
            text-align: center;
        }

        tbody td.name {
            text-align: left;
        }

        .muted {
            color: #6b7280;
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

    <div class="header clearfix">
        <div class="h-left">
            <img class="h-logo" src="{{ $logoBase64 }}" alt="BCTI">
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
                <th style="width:25px;">No.</th>
                <th style="width:18%;">Nama</th>
                <th style="width:12%;">No. Telepon</th>
                <th>SM</th>
                <th>CIA</th>
                <th>TS</th>
                <th>WWO</th>
                <th>CA</th>
                <th>L</th>
                <th>SE</th>
                <th>PS</th>
                <th>PE</th>
                <th>GH</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse(($rows ?? []) as $r)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td class="name">{{ $r->name ?? '—' }}</td>
                    <td>{{ $r->phone_number ?? '—' }}</td>
                    <td>{{ $r->SM ?? 0 }}</td>
                    <td>{{ $r->CIA ?? 0 }}</td>
                    <td>{{ $r->TS ?? 0 }}</td>
                    <td>{{ $r->WWO ?? 0 }}</td>
                    <td>{{ $r->CA ?? 0 }}</td>
                    <td>{{ $r->L ?? 0 }}</td>
                    <td>{{ $r->SE ?? 0 }}</td>
                    <td>{{ $r->PS ?? 0 }}</td>
                    <td>{{ $r->PE ?? 0 }}</td>
                    <td>{{ $r->GH ?? 0 }}</td>

                    <td><strong>{{ $r->total_score ?? 0 }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" class="muted" style="padding:14px;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Page <span class="pagenum"></span></div>

</body>

</html>
