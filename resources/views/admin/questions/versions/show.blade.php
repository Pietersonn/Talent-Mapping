@extends('admin.layouts.app')

@section('title', $questionVersion->name)

@push('styles')
<style>
    :root {
        /* TEMA: HIJAU TALENT MAPPING */
        --theme-color: #10b981; /* Emerald-500 */
        --theme-bg: #ecfdf5;    /* Emerald-50 */
        --theme-hover: #059669; /* Emerald-600 */
    }

    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
    .page-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 12px; }

    .btn-back { background: white; border: 1px solid #e2e8f0; color: #64748b; padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s; }
    .btn-back:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; }

    /* --- TEKNIK GRID DARI REFERENSI ANDA --- */
    .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
    /* Mobile Responsive: Kalau layar kecil baru turun ke bawah */
    @media (max-width: 992px) { .dashboard-grid { grid-template-columns: 1fr; } }

    /* Cards */
    .detail-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); height: 100%; display: flex; flex-direction: column; }
    .section-title { font-size: 0.85rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; }

    /* Info Items */
    .info-group { margin-bottom: 1rem; }
    .info-label { font-size: 0.75rem; color: #64748b; margin-bottom: 2px; }

    .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; }
    .badge-active { background: #d1fae5; color: #047857; border: 1px solid #6ee7b7; }
    .badge-inactive { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

    /* Progress & Stats */
    .progress-wrapper { background: #f1f5f9; height: 8px; border-radius: 4px; overflow: hidden; margin: 10px 0; }
    .progress-bar { height: 100%; background: var(--theme-color); border-radius: 4px; transition: width 0.5s ease; }

    .stat-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: auto; }
    .stat-box { background: var(--theme-bg); padding: 1rem; border-radius: 12px; text-align: center; border: 1px solid #d1fae5; }
    .stat-num { font-size: 1.5rem; font-weight: 800; color: var(--theme-color); line-height: 1; }
    .stat-desc { font-size: 0.75rem; color: #64748b; font-weight: 600; margin-top: 4px; }

    /* Table */
    .table-container { border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table th { background: #f8fafc; padding: 12px 16px; text-align: left; font-size: 0.75rem; font-weight: 700; color: #64748b; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; color: #334155; vertical-align: middle; }

    .tag { padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; background: #f1f5f9; color: #475569; }
    .tag-typology { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .tag-competency { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }

    /* Action Buttons */
    .action-btn { background: white; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; color: #475569; cursor: pointer; transition: all 0.2s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
    .action-btn:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; }
    .btn-primary-action { background: var(--theme-color); color: white; border: none; }
    .btn-primary-action:hover { background: var(--theme-hover); color: white; transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
</style>
@endpush

@section('header')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="fas fa-layer-group" style="color: var(--theme-color);"></i>
            Detail Versi
        </h1>
        <div class="text-sm text-slate-500 mt-1 ml-1">
            Bank Soal &rsaquo; {{ $questionVersion->type === 'st30' ? 'ST-30' : 'SJT' }} &rsaquo; <span class="font-semibold text-slate-700">{{ $questionVersion->version }}</span>
        </div>
    </div>
    <a href="{{ route('admin.questions.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection

@section('content')

{{--
    IMPLEMENTASI CSS GRID (Sesuai Referensi)
    Menggunakan class .dashboard-grid (didefinisikan di style atas)
    Layout ini akan memaksa Kiri dan Kanan bersebelahan dengan rasio 2:1
--}}
<div class="dashboard-grid mb-4">

    {{-- BAGIAN KIRI: Detail Versi --}}
    <div>
        <div class="detail-card">
            {{-- Header Card --}}
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800 mb-1">{{ $questionVersion->name }}</h2>
                    <div class="flex items-center gap-2 mt-2">
                        @if($questionVersion->is_active)
                            <span class="status-badge badge-active"><i class="fas fa-check-circle"></i> SEDANG DIGUNAKAN</span>
                        @else
                            <span class="status-badge badge-inactive"><i class="fas fa-pause-circle"></i> TIDAK AKTIF</span>
                        @endif
                        <span class="text-sm text-slate-400 border-l pl-2 border-slate-200">Dibuat {{ $questionVersion->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex gap-2">
                    @if(Auth::user()->role === 'admin')
                        @if(!$questionVersion->is_active && $statistics['total_questions'] >= ($questionVersion->type === 'st30' ? 30 : 50))
                            <form action="{{ route('admin.questions.activate', $questionVersion->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="action-btn btn-primary-action" onclick="return confirm('Aktifkan versi ini?')">
                                    <i class="fas fa-power-off"></i> Gunakan
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.questions.edit', $questionVersion->id) }}" class="action-btn">
                            <i class="fas fa-pen text-orange-500"></i> Edit
                        </a>
                    @endif
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 mb-4 flex-grow">
                <div class="info-label mb-2"><i class="fas fa-align-left mr-1"></i> Deskripsi / Catatan</div>
                <div class="text-slate-700 text-sm leading-relaxed">
                    {{ $questionVersion->description ?: 'Tidak ada deskripsi tambahan.' }}
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="mt-auto pt-2">
                @php
                    $target = $questionVersion->type === 'st30' ? 30 : 50;
                    $percent = min(100, ($statistics['total_questions'] / $target) * 100);
                @endphp
                <div class="flex justify-between items-end mb-2">
                    <span class="text-sm font-bold text-slate-700">Kelengkapan Soal</span>
                    <span class="text-sm font-mono font-bold text-slate-500">{{ $statistics['total_questions'] }} / {{ $target }}</span>
                </div>
                <div class="progress-wrapper">
                    <div class="progress-bar" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN KANAN: Statistik --}}
    <div>
        <div class="detail-card">
            <div class="section-title"><i class="fas fa-chart-pie mr-2"></i> Statistik Penggunaan</div>

            <div class="stat-grid mb-4">
                <div class="stat-box">
                    <div class="stat-num">{{ $statistics['usage_stats']['total_usage'] ?? 0 }}</div>
                    <div class="stat-desc">Kali Digunakan</div>
                </div>
                <div class="stat-box">
                    <div class="stat-num">{{ $questionVersion->type === 'st30' ? '30' : '50' }}</div>
                    <div class="stat-desc">Target Soal</div>
                </div>
            </div>

            <div class="flex-grow flex flex-col">
                <div class="section-title mb-2 mt-2">Distribusi Soal</div>
                <div class="overflow-y-auto pr-1" style="max-height: 200px;">
                    @if($questionVersion->type === 'st30')
                        <div class="flex flex-wrap gap-2">
                            @foreach($typologyStats as $code => $count)
                                <span class="tag tag-typology">{{ $code }}: <b>{{ $count }}</b></span>
                            @endforeach
                        </div>
                    @else
                        <ul class="list-unstyled text-sm m-0">
                            @foreach($competencyStats as $comp => $count)
                                <li class="flex justify-between border-b border-slate-100 py-2 last:border-0">
                                    <span class="text-slate-600 truncate w-2/3" title="{{ $comp }}">{{ $comp }}</span>
                                    <span class="font-bold text-sky-600 bg-sky-50 px-2 rounded">{{ $count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ROW 2: Preview Soal (Full Width di Bawah) --}}
{{-- Bagian ini menggunakan .row standar bootstrap agar full width --}}
<div class="row">
    <div class="col-12">
        <div class="detail-card" style="padding: 0; overflow: hidden;">
            <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 class="font-bold text-slate-700 m-0"><i class="fas fa-list mr-2 text-slate-400"></i> Preview Soal</h3>
                <div class="flex gap-2">
                    @if(Auth::user()->role === 'admin')
                        @php
                            $indexRoute = $questionVersion->type === 'st30'
                                ? route('admin.questions.st30.index', ['version' => $questionVersion->id])
                                : route('admin.questions.sjt.index', ['version' => $questionVersion->id]);
                        @endphp
                    @endif

                    <a href="{{ $indexRoute ?? '#' }}" class="action-btn btn-primary-action text-xs">
                        Kelola Semua Soal <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="table-container border-0 rounded-none">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Pertanyaan / Pernyataan</th>
                            <th width="150">{{ $questionVersion->type === 'st30' ? 'Tipologi' : 'Kompetensi' }}</th>
                            <th width="100">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $questions = $questionVersion->type === 'st30'
                                ? $questionVersion->st30Questions->take(10)
                                : $questionVersion->sjtQuestions->take(10);
                        @endphp

                        @forelse($questions as $q)
                        <tr>
                            <td class="text-center font-mono font-bold text-slate-500">{{ $q->number }}</td>
                            <td>
                                @if($questionVersion->type === 'st30')
                                    {{ Str::limit($q->statement, 80) }}
                                @else
                                    {{ Str::limit($q->question_text, 80) }}
                                    <div class="text-xs text-slate-400 mt-1">
                                        {{ $q->options->count() }} Opsi Jawaban
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($questionVersion->type === 'st30')
                                    <span class="tag tag-typology">{{ $q->typology_code }}</span>
                                @else
                                    <span class="tag tag-competency">{{ Str::limit($q->competency, 15) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($q->is_active)
                                    <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded">Aktif</span>
                                @else
                                    <span class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded">Non-Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-slate-400">
                                <i class="fas fa-folder-open text-2xl mb-2 opacity-50"></i><br>
                                Belum ada soal ditambahkan pada versi ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($statistics['total_questions'] > 10)
                    <div class="p-3 text-center bg-slate-50 border-t border-slate-100 text-xs text-slate-500">
                        Menampilkan 10 dari {{ $statistics['total_questions'] }} soal.
                        <a href="{{ $indexRoute ?? '#' }}" class="font-bold text-green-600 hover:underline">Lihat Semua</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
