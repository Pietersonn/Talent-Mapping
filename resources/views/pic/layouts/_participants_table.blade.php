<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Name</th><th>Email</th><th>Completed</th><th>Result Sent</th><th>Joined</th>
        </tr>
      </thead>
      <tbody>
      @forelse($participants ?? [] as $p)
        <tr>
          <td>{{ $p->user->name ?? $p->name ?? '-' }}</td>
          <td>{{ $p->user->email ?? $p->email ?? '-' }}</td>
          <td>{!! ($p->test_completed ?? false) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-warning text-dark">No</span>' !!}</td>
          <td>{!! ($p->results_sent ?? false) ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
          <td>{{ optional($p->created_at)->format('d M Y H:i') }}</td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center text-muted">No participants.</td></tr>
      @endforelse
      </tbody>
    </table>

    @if(isset($participants) && method_exists($participants,'links'))
      <div class="mt-3">{{ $participants->links() }}</div>
    @endif
  </div>
</div>
