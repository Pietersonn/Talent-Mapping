<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Lite Insight</title>
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 11px; }
    h1 { font-size: 16px; margin-bottom: 6px; }
    h2 { font-size: 13px; margin-top: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    th, td { border:1px solid #444; padding:5px; }
  </style>
</head>
<body>
  <h1>Lite Insight (ST-30 & SJT)</h1>

  <h2>ST-30 Dominant Typology (Top 10)</h2>
  <table>
    <thead><tr><th>#</th><th>Typology</th><th>Count</th></tr></thead>
    <tbody>
      @php $i=1; @endphp
      @foreach(array_slice($typoCount,0,10,true) as $name => $cnt)
        <tr><td>{{ $i++ }}</td><td>{{ $name }}</td><td>{{ $cnt }}</td></tr>
      @endforeach
    </tbody>
  </table>

  <h2>SJT — Rata-rata Skor Kompetensi (Top 10)</h2>
  <table>
    <thead><tr><th>#</th><th>Kompetensi</th><th>Avg Score</th></tr></thead>
    <tbody>
      @php $i=1; @endphp
      @foreach(array_slice($compAvg,0,10,true) as $name => $avg)
        <tr><td>{{ $i++ }}</td><td>{{ $name }}</td><td>{{ $avg }}</td></tr>
      @endforeach
    </tbody>
  </table>

  <h2>SJT — Frekuensi Kompetensi Masuk Top-3</h2>
  <table>
    <thead><tr><th>#</th><th>Kompetensi</th><th>Frekuensi</th></tr></thead>
    <tbody>
      @php $i=1; @endphp
      @foreach($top3Freq as $name => $cnt)
        <tr><td>{{ $i++ }}</td><td>{{ $name }}</td><td>{{ $cnt }}</td></tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
