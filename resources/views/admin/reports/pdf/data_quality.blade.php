<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Data Quality</title>
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 11px; }
    h1 { font-size: 16px; margin-bottom: 6px; }
    h2 { font-size: 13px; margin-top: 12px; }
    ul { margin: 6px 0; padding-left: 18px; }
    li { margin-bottom: 4px; }
  </style>
</head>
<body>
  <h1>Data Quality</h1>

  <h2>Instansi Kosong</h2>
  <ul>
    @forelse($missingInstansi as $s)
      <li>{{ $s->participant_name }}</li>
    @empty
      <li><em>Tidak ada</em></li>
    @endforelse
  </ul>

  <h2>Session Tanpa Result</h2>
  <ul>
    @forelse($noResult as $s)
      <li>{{ $s->participant_name }}</li>
    @empty
      <li><em>Tidak ada</em></li>
    @endforelse
  </ul>

  <h2>PDF Path Bermasalah</h2>
  <ul>
    @forelse($invalidPdf as $r)
      <li>{{ data_get($r,'session.participant_name') }} — (empty)</li>
    @empty
      <li><em>Tidak ada</em></li>
    @endforelse
  </ul>
</body>
</html>
