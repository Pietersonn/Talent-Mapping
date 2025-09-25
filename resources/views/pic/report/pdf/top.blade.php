<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Top 10 Report</title>
  <style>
    body{font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; color:#222}
    table{width:100%; border-collapse:collapse}
    th,td{border:1px solid #ddd; padding:6px 8px}
    th{background:#f5f5f5; text-align:left}
    .right{text-align:right}
    .muted{color:#666}
  </style>
</head>
<body>
  <h3>Top 10 — SJT (Sum of Top 3 Competencies)</h3>
  <div class="muted">{{ $generated_at->format('d M Y H:i') }}</div>

  <table>
    <thead>
      <tr>
        <th style="width:30px;">#</th>
        <th>Participant</th>
        <th>Instansi</th>
        <th>Event</th>
        <th class="right">Skor</th>
      </tr>
    </thead>
    <tbody>
    @forelse($rows as $i => $r)
      <tr>
        <td class="right">{{ $i+1 }}</td>
        <td>
          <div>{{ $r->name }}</div>
          <div class="muted">{{ $r->email }}</div>
        </td>
        <td>{{ $r->instansi }}</td>
        <td>{{ $r->event_name }} ({{ $r->event_code }})</td>
        <td class="right"><strong>{{ $r->score }}</strong></td>
      </tr>
    @empty
      <tr><td colspan="5" class="muted">No data.</td></tr>
    @endforelse
    </tbody>
  </table>
</body>
</html>
