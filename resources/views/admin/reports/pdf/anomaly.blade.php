<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Anomaly (Fast Duration)</title>
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 11px; }
    h1 { font-size: 16px; margin-bottom: 6px; }
    .muted { color:#666; font-size:10px; }
    table { width: 100%; border-collapse: collapse; margin-top:10px; }
    th, td { border:1px solid #444; padding:5px; }
  </style>
</head>
<body>
  <h1>Anomaly (Fast Duration)</h1>
  <div class="muted">Threshold: {{ $thresholdMin }} menit</div>
  <table>
    <thead>
      <tr>
        <th>#</th><th>Nama</th><th>Email</th><th>Instansi</th><th>Event</th><th>Durasi (m)</th><th>Flag</th>
      </tr>
    </thead>
    <tbody>
      @foreach($flagged as $i => $r)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $r['name'] }}</td>
          <td>{{ $r['email'] ?? '—' }}</td>
          <td>{{ $r['instansi'] ?: '—' }}</td>
          <td>{{ $eventMap[$r['event_id']] ?? $r['event_id'] }}</td>
          <td>{{ $r['duration_m'] }}</td>
          <td>{{ $r['flag'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
