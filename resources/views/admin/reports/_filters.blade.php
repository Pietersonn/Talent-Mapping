<form method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-3">
            <label class="small text-muted">Event</label>
            <select name="event_id" class="form-control">
                <option value="">— All Events —</option>
                @foreach($events as $ev)
                    <option value="{{ $ev->id }}" {{ ($f['event_id'] ?? '') == $ev->id ? 'selected' : '' }}>
                        {{ $ev->name }} @if($ev->start_date) ({{ $ev->start_date }} → {{ $ev->end_date }}) @endif
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="small text-muted">Instansi</label>
            <input type="text" class="form-control" name="instansi" value="{{ $f['instansi'] ?? '' }}" placeholder="Ketik instansi">
        </div>
        <div class="col-md-2">
            <label class="small text-muted">Dari</label>
            <input type="date" class="form-control" name="date_from" value="{{ $f['date_from'] ?? '' }}">
        </div>
        <div class="col-md-2">
            <label class="small text-muted">Sampai</label>
            <input type="date" class="form-control" name="date_to" value="{{ $f['date_to'] ?? '' }}">
        </div>
        <div class="col-md-2">
            <label class="small text-muted">Cari</label>
            <input type="text" class="form-control" name="search" value="{{ $f['search'] ?? '' }}" placeholder="Nama">
        </div>
    </div>
    <div class="mt-2">
        <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ url()->current() }}" class="btn btn-default btn-sm">Reset</a>
    </div>
</form>
