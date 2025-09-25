<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Resend Requests</title>
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 11px; }
    h1 { font-size: 16px; margin-bottom: 6px; }
    table { width: 100%; border-collapse: collapse; margin-top:10px; }
    th, td { border:1px solid #444; padding:5px; }
  </style>
</head>
<body>
  <h1>Resend Requests</h1>
  <table>
    <thead>
      <tr>
        <th>#</th><th>Nama</th><th>Email</th><th>Instansi</th><th>Event</th>
        <th>Alasan</th><th>Status</th><th>Requested At</th><th>Approved By</th><th>Approved At</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $i => $r)
        @php $evId = data_get($r,'result.session.event_id'); @endphp
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ data_get($r,'result.session.participant_name') }}</td>
          <td>{{ data_get($r,'result.session.participant_email') ?? '—' }}</td>
          <td>{{ data_get($r,'result.session.participant_background') ?: '—' }}</td>
          <td>{{ $eventMap[$evId] ?? $evId }}</td>
          <td>{{ $r->reason ?: '—' }}</td>
          <td>{{ ucfirst($r->status) }}</td>
          <td>{{ optional($r->request_date)?->toDateTimeString() }}</td>
          <td>{{ $r->approved_by ?: '—' }}</td>
          <td>{{ optional($r->approved_at)?->toDateTimeString() ?: '—' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
