<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Participants</title>
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 11px; }
    h1 { font-size: 16px; margin-bottom: 6px; }
    table { width: 100%; border-collapse: collapse; margin-top:10px; }
    th, td { border:1px solid #444; padding:5px; }
  </style>
</head>
<body>
  <h1>Participants</h1>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Instansi</th>
        <th>Event</th>
        <th>Skor (SumTop3)</th>
      </tr>
    </thead>
    <tbody>
      @foreach($mapped as $i => $r)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $r['name'] }}</td>
          <td>{{ $r['email'] ?? '—' }}</td>
          <td>{{ $r['instansi'] ?: '—' }}</td>
          <td>{{ $eventMap[$r['event_id']] ?? $r['event_id'] }}</td>
          <td><strong>{{ $r['sum_top3'] }}</strong></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
