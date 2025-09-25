<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Delivery & SLA</title>
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 11px; }
    h1 { font-size: 16px; margin-bottom: 6px; }
    table { width: 100%; border-collapse: collapse; margin-top:10px; }
    th, td { border:1px solid #444; padding:5px; }
  </style>
</head>
<body>
  <h1>Delivery & SLA</h1>
  <table>
    <thead>
      <tr>
        <th>#</th><th>Nama</th><th>Email</th><th>Event</th><th>PDF Path</th>
        <th>Generated At</th><th>Sent At</th><th>SLA Generate (m)</th><th>SLA Send (m)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($mapped as $i => $r)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $r['name'] }}</td>
          <td>{{ $r['email'] ?? '—' }}</td>
          <td>{{ $eventMap[$r['event_id']] ?? $r['event_id'] }}</td>
          <td>{{ $r['pdf_path'] ?: '—' }}</td>
          <td>{{ $r['generated_at'] ?: '—' }}</td>
          <td>{{ $r['sent_at'] ?: '—' }}</td>
          <td>{{ $r['sla_generate'] !== null ? $r['sla_generate'] : '—' }}</td>
          <td>{{ $r['sla_send'] !== null ? $r['sla_send'] : '—' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
    