@extends('pic.layouts.app')
@section('title','Participants — '.(($event->name ?? 'Event').' ('.($event->event_code ?? '-').')'))

@section('content')
<div class="page-header mb-3">
  <h5 class="mb-1">Participants • {{ $event->name ?? 'Event' }} ({{ $event->event_code ?? '-' }})</h5>
  <p class="text-muted mb-0">Daftar peserta untuk event ini.</p>
</div>

@include('pic.partials._participants_table', ['participants' => $participants ?? collect()])
@endsection
