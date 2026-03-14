@php
  $progress = isset($progress) ? (int) $progress : 0;
  $isRegister = $progress >= 0;
  $isST30     = $progress >= 10;
  $isSJT      = $progress >= 50;
  $isDone     = $progress >= 100;
@endphp

<link rel="stylesheet" href="{{ asset('assets/public/css/components/stepper.css') }}">

<div class="tm-stepper">
  <!-- garis dasar -->
  <div class="tm-stepper-line"></div>
  <!-- garis terisi sesuai % -->
  <div class="tm-stepper-fill" style="width: {{ max(0, min(100, $progress)) }}%"></div>

  <div class="tm-stepper-nodes">
    <div class="tm-node {{ $isRegister ? 'active' : '' }} {{ $isST30 ? 'done' : '' }}">
      <span class="tm-dot">{{ $isST30 ? '✓' : '1' }}</span>
      <span class="tm-label">Register</span>
    </div>

    <div class="tm-node {{ $isST30 ? 'active' : '' }} {{ $isSJT ? 'done' : '' }}">
      <span class="tm-dot">{{ $isSJT ? '✓' : '2' }}</span>
      <span class="tm-label">ST-30</span>
    </div>

    <div class="tm-node {{ $isSJT ? 'active' : '' }} {{ $isDone ? 'done' : '' }}">
      <span class="tm-dot">{{ $isDone ? '✓' : '3' }}</span>
      <span class="tm-label">Talent Competency</span>
    </div>

    <div class="tm-node {{ $isDone ? 'active done' : '' }}">
      <span class="tm-dot">{{ $isDone ? '✓' : '4' }}</span>
      <span class="tm-label">Completed</span>
    </div>
  </div>
</div>
