@extends('public.layouts.app')

@section('title', 'My Profile')


@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/public/css/profile.css') }}">
@endpush

@section('content')
    <div class="tmprof-wrapper">
        <div class="tmprof-header">
            <h1 class="tmprof-title">Hai, {{ auth()->user()->name }}</h1>
            <p class="tmprof-sub">Riwayat Tes & Permintaan Kirim Ulang Hasil</p>
        </div>

        @if (session('success'))
            <div class="tmprof-alert tmprof-alert--success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="tmprof-alert tmprof-alert--error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="tmprof-grid">
            <section class="tmprof-section">
                <div class="tmprof-section__head">
                    <h2 class="tmprof-section__title">Hasil Tes Kamu</h2>
                </div>

                @if ($results->isEmpty())
                    <div class="tmprof-empty">
                        Belum ada hasil ditampilkan di profil. Kamu bisa ajukan kirim ulang hasil melalui formulir di kanan.
                    </div>
                @else
                    <div class="tmprof-cards">
                        @foreach ($results as $r)
                            @include('public.profile.partials._result_card', ['r' => $r])
                        @endforeach
                    </div>
                @endif
            </section>

            <aside class="tmprof-aside tmprof-section">
                <div class="tmprof-section__head">
                    <h2 class="tmprof-section__title">Permintaan Kirim Ulang</h2>
                </div>

                <div class="tmprof-resend">
                    <form class="tmprof-form" method="POST" action="{{ route('profile.resend.request') }}">
                        @csrf

                        <label class="tmprof-label">Pilih Hasil Tes</label>
                        <select name="test_result_id" class="tmprof-select" required>
                            <option value="" disabled selected>— Pilih salah satu —</option>
                            @foreach ($results as $r)
                                <option value="{{ $r->id }}">
                                    {{ optional($r->session->event)->name ? $r->session->event->name . ' — ' : '' }}
                                    {{ $r->dominant_typology ?? 'Hasil' }}
                                    ({{ optional($r->report_generated_at)->format('d M Y') ?? '—' }})
                                </option>
                            @endforeach
                        </select>

                        <label class="tmprof-label">Catatan (opsional)</label>
                        <textarea name="reason" class="tmprof-textarea" rows="3"
                            placeholder="Contoh: minta dikirim ulang ke email akun saya / butuh salinan terbaru">{{ old('reason') }}</textarea>

                        <button type="submit" class="tmprof-button">Ajukan Resend</button>
                    </form>

                    <div class="tmprof-requests">
                        <h3 class="tmprof-h3">Riwayat Permintaan</h3>
                        @forelse ($resendRequests as $req)
                            <div class="tmprof-request">
                                <div class="tmprof-request__meta">
                                    <span
                                        class="tmprof-chip tmprof-chip--{{ $req->status }}">{{ ucfirst($req->status) }}</span>
                                    <span
                                        class="tmprof-request__date">{{ optional($req->updated_at)->format('d M Y H:i') }}</span>
                                </div>
                                <div class="tmprof-request__email">
                                    Tujuan: {{ optional($req->user)->email ?? '-' }}
                                </div>
                                @if ($req->admin_notes)
                                    <div class="tmprof-request__reason">Catatan: “{{ $req->admin_notes }}”</div>
                                @endif
                                @if ($req->approved_at)
                                    <div class="tmprof-request__reason">Disetujui:
                                        {{ $req->approved_at->format('d M Y H:i') }}</div>
                                @endif
                                @if ($req->rejection_reason)
                                    <div class="tmprof-request__reason">Ditolak: “{{ $req->rejection_reason }}”</div>
                                @endif
                            </div>
                        @empty
                            <div class="tmprof-empty">Belum ada permintaan kirim ulang.</div>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>


    @push('styles')
        <style>
            .tmprof-wrapper {
                padding-top: calc(var(--navbar-height, 64px) ) !important;
            }

            .tmprof-aside {
                top: calc(var(--navbar-height, 64px) ) !important;
            }
            .tmprof-section__title,
            .tmprof-title {
                scroll-margin-top: calc(var(--navbar-height, 64px));
            }
        </style>
    @endpush
@endsection
