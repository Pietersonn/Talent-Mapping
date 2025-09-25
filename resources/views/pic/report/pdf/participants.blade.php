<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Participants Report</title>
  <style>
    body{font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; color:#222}
    table{width:100%; border-collapse:collapse}
    th,td{border:1px solid #ddd; padding:6px 8px}
    th{background:#f5f5f5; text-align:left}
    .muted{color:#666}
  </style>
</head>
<body>
  <h3>Participants Report</h3>
  <div class="muted">{{ $generated_at->format('d M Y H:i') }}</div>

  <table>
    <thead>
      <tr><th>Name</th><th>Email</th><th>Instansi</th><th>Event</th></tr>
    </thead>
    <tbody>
    @forelse($rows as $r)
      <tr>
        <td>{{ $r->name }}</td>
        <td>{{ $r->email }}</td>
        <td>{{ $r->instansi }}</td>
        <td>{{ $r->event_name ?? '' }} {{ isset($r->event_code) ? '(' . $r->event_code . ')' : '' }}</td>
      </tr>
    @empty
      <tr><td colspan="4" class="muted">No data.</td></tr>
    @endforelse
    </tbody>
  </table>
</body>
</html>
