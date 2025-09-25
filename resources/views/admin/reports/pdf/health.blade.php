<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Health Dashboard</title>
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; }
    h1 { font-size: 18px; margin-bottom: 6px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #444; padding: 6px; text-align: left; }
    .muted { color: #666; font-size: 11px; }
  </style>
</head>
<body>
  <h1>Health Dashboard</h1>
  <div class="muted">
    Filter:
    @if(($f['event_id'] ?? null))
      Event ID: {{ $f['event_id'] }};
    @endif
    Instansi: {{ $f['instansi'] ?: '—' }};
    Periode: {{ $f['date_from'] ?: '—' }} s/d {{ $f['date_to'] ?: '—' }}
  </div>

  <table>
    <tbody>
      <tr><th>Total Registered</th><td>{{ $metrics['total_registered'] }}</td></tr>
      <tr><th>SJT Started</th><td>{{ $metrics['sjt_started'] }}</td></tr>
      <tr><th>Completed</th><td>{{ $metrics['completed'] }}</td></tr>
      <tr><th>Completion Rate</th><td>{{ $metrics['conversion_rate'] }}%</td></tr>
      <tr><th>Median SLA Generate PDF</th><td>{{ $metrics['median_sla_generate'] !== null ? $metrics['median_sla_generate'].' menit' : '—' }}</td></tr>
      <tr><th>Median SLA Send Email</th><td>{{ $metrics['median_sla_send'] !== null ? $metrics['median_sla_send'].' menit' : '—' }}</td></tr>
    </tbody>
  </table>
</body>
</html>
