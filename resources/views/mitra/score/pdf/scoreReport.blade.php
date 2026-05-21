@php
    $logoFile = public_path('assets/public/images/logo-bcti1.png');
    $logoBase64 = '';
    if (file_exists($logoFile)) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoFile));
    }
@endphp

@php
    $reportTitle = $reportTitle ?? 'Laporan Kompetensi Peserta';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        body { font-family: "Times New Roman", Times, serif; font-size: 11px; color: #111; }
        .clearfix:after { content: ""; display: table; clear: both; }

        .header { margin-bottom: 10px; }
        .h-left { float: left; width: 30%; }
        .h-right { float: right; width: 65%; text-align: right; }
        .h-logo { height: 70px; width: auto; }

        .company-name { font-weight: 700; font-size: 14px; margin-bottom: 2px; }
        .company-sub { font-size: 10px; line-height: 1.3; color: #333; }

        .divider { border: 0; border-top: 2px solid #000; margin: 5px 0 10px; }

        .title-wrap { text-align: center; margin-bottom: 15px; }
        .title { font-size: 16px; font-weight: 700; text-transform: uppercase; margin: 0 0 5px; }
        .subtitle { font-size: 11px; color: #444; }

        table { width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 20px; }
        thead th { background: #ededed; border: 1px solid #000; font-weight: 700; font-size: 10px; padding: 6px 4px; text-align: center; vertical-align: middle; }
        tbody td { border: 1px solid #000; padding: 6px 4px; vertical-align: middle; text-align: center; font-size: 10px; }

        tbody td.name { text-align: left; }
        .footer { position: fixed; bottom: -10mm; left: 0; right: 0; text-align: right; font-size: 10px; color: #666; }
        .pagenum:before { content: counter(page); }
    </style>
</head>
<body>

    <div class="header clearfix">
        <div class="h-left">
            @if(!empty($logoBase64))
                <img class="h-logo" src="{{ $logoBase64 }}" alt="Logo">
            @else
                <strong class="company-name" style="font-size: 20px;">BCTI</strong>
            @endif
        </div>
        <div class="h-right">
            <div class="company-name">BUSINESS & COMMUNICATION TRAINING INSTITUTE</div>
            <div class="company-sub">
                Kompleks Sekolah Global Islamic Boarding School (GIBS)<br>
                Gedung Nurhayati Kampus GIBS, Jl. Trans - Kalimantan Lantai 2, Sungai Lumbah, Kec. Alalak, Kab. Barito Kuala, Kalimantan Selatan 70582<br>
                Email : bcti@hasnurcentre.org | Website: bcti.id
            </div>
        </div>
    </div>

    <hr class="divider">

    <div class="title-wrap">
        <div class="title">{{ $reportTitle }}</div>
        <div class="subtitle">
            {!! $modeText ?? '' !!}<br>
            Dicetak oleh: {{ $generatedBy ?? '-' }} • {{ $generatedAt ?? '' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No.</th>
                <th style="width: 180px; text-align:left;">Nama Peserta</th>
                <th style="width: 90px;">No. Telp</th>
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
                    <td class="name">
                        <b>{{ $r->name ?? '-' }}</b><br>
                        <span style="color:#555; font-size:9px;">{{ $r->instansi ?? 'Umum' }}</span>
                    </td>
                    <td>{{ $r->phone_number ?? '-' }}</td>
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
                    <td style="font-weight:bold;">{{ $r->total_score ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" style="padding:15px; color:#777;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Halaman <span class="pagenum"></span></div>

</body>
</html>
