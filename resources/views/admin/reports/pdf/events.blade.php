<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Events Summary</title>
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 11px; }
    h1 { font-size: 16px; margin-bottom: 6px; }
    table { width: 100%; border-collapse: collapse; margin-top:10px; }
    th, td { border:1px solid #444; padding:5px; }
  </style>
</head>
<body>
  <h1>Events Summary</h1>
  <table>
    <thead>
      <tr>
        <th>#</th><th>Event</th><th>Periode</th><th>Registered</th>
        <th>ST-30 Started</th><th>SJT Started</th><th>Completed</th><th>Completion %</th><th>Results Sent</th>
      </tr>
    </thead>
    <tbody>
      @foreach($summary as $i => $r)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $r['event_name'] }}</td>
          <td>{{ $r['period'] }}</td>
          <td>{{ $r['registered'] }}</td>
          <td>{{ $r['st30_started'] }}</td>
          <td>{{ $r['sjt_started'] }}</td>
          <td>{{ $r['completed'] }}</td>
          <td>{{ $r['completion_rate'] }}%</td>
          <td>{{ $r['results_sent'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
