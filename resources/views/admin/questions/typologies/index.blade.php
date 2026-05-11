@extends('admin.layouts.app')

@section('title', 'Manajemen Tipologi')

@push('styles')
<style>
    .btn-tm { background: #22c55e; color: white; padding: 10px 20px; border-radius: 12px; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: none; box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3); transition: all 0.2s; }
    .btn-tm:hover { background: #16a34a; transform: translateY(-1px); color: white; }

    .btn-icon-square { width: 44px; height: 44px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b; cursor: pointer; transition: all 0.2s; text-decoration: none; }
    .btn-icon-square:hover { background: #f0fdf4; color: #15803d; border-color: #22c55e; transform: translateY(-1px); }

    .search-group { position: relative; width: 300px; }
    .search-input { width: 100%; padding: 10px 12px 10px 40px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.875rem; background: white; transition: all 0.2s; }
    .search-input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1); }
    .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .loading-spinner { position: absolute; right: 12px; top: 33%; transform: translateY(-50%); color: #22c55e; display: none; }

    .table-card { background: white; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 1.5rem; }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .custom-table th { text-align: left; padding: 1.25rem; background: #f8fafc; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; vertical-align: top; font-size: 0.9rem; color: #334155; background: white; }
    .custom-table tr:hover td { background-color: #f8fafc; }

    .text-code { font-weight: 800; color: #0f172a; font-family: monospace; font-size: 1rem; }
    .text-name { font-weight: 700; color: #15803d; font-size: 0.95rem; }
    .desc-text { font-size: 0.825rem; color: #64748b; line-height: 1.4; }

    .text-expand-btn { color: #22c55e; border: none; background: none; font-size: 0.75rem; font-weight: 700; cursor: pointer; padding: 0; margin-left: 4px; }
    .text-expand-btn:hover { text-decoration: underline; }

    .action-buttons { display: flex; gap: 6px; justify-content: flex-end; }
    .btn-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: none; transition: 0.2s; }
    .btn-view { background: #ecfdf5; color: #059669; }
    .btn-edit { background: #eff6ff; color: #2563eb; }
    .btn-delete { background: #fef2f2; color: #dc2626; }
    .btn-icon:hover { opacity: 0.8; transform: scale(1.05); }

    .pagination-wrapper { padding: 1rem 1.5rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .btn-paginate { background: white; border: 1px solid #e2e8f0; color: #22c55e; padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 0.85rem; text-decoration: none; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-paginate:hover:not(.disabled) { background: #f0fdf4; border-color: #22c55e; color: #15803d; transform: translateY(-1px); }
    .btn-paginate.disabled { color: #94a3b8; background: #f8fafc; cursor: not-allowed; opacity: 0.7; }
</style>
@endpush

@section('content')
    <div class="header-wrapper" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-fingerprint" style="color: #22c55e; background: #dcfce7; padding: 10px; border-radius: 12px; font-size: 1.1rem;"></i>
                Manajemen Tipologi
            </h1>
            <p style="font-size: 0.9rem; color: #64748b; margin-left: 54px; margin-top: -5px;">Daftar tipologi kepribadian beserta kekuatan dan kelemahannya.</p>
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="search-group">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="typologySearch" class="search-input" placeholder="Cari kode atau nama..." autocomplete="off">
                <i class="fas fa-circle-notch fa-spin loading-spinner"></i>
            </div>

            <button onclick="exportTypologies()" class="btn-icon-square" title="Export PDF">
                <i class="fas fa-print"></i>
            </button>

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.questions.typologies.create') }}" class="btn-tm"><i class="fas fa-plus"></i> Tambah</a>
            @endif
        </div>
    </div>

    <div class="table-card">
        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th width="10%">Kode</th>
                        <th width="15%">Tipologi</th>
                        <th width="30%">Kekuatan</th>
                        <th width="30%">Kelemahan</th>
                        <th width="10%" class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="typologyTableBody">
                    @forelse($typologies as $index => $item)
                        <tr>
                            <td class="text-center"><span style="color: #94a3b8; font-weight: 600;">{{ $typologies->firstItem() + $index }}</span></td>
                            <td><span class="text-code">{{ $item->kode_tipologi }}</span></td>
                            <td><span class="text-name">{{ $item->nama_tipologi }}</span></td>
                            <td>
                                <div class="desc-text">
                                    <span class="short-text">{{ Str::limit(strip_tags($item->deskripsi_kekuatan), 60) }}</span>
                                    @if(strlen(strip_tags($item->deskripsi_kekuatan)) > 60)
                                        <button class="text-expand-btn" onclick="toggleText(this)">Lihat</button>
                                        <span class="full-text" style="display: none;">{{ strip_tags($item->deskripsi_kekuatan) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="desc-text">
                                    <span class="short-text">{{ Str::limit(strip_tags($item->deskripsi_kelemahan), 60) }}</span>
                                    @if(strlen(strip_tags($item->deskripsi_kelemahan)) > 60)
                                        <button class="text-expand-btn" onclick="toggleText(this)">Lihat</button>
                                        <span class="full-text" style="display: none;">{{ strip_tags($item->deskripsi_kelemahan) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.questions.typologies.show', $item->id) }}" class="btn-icon btn-view"><i class="fas fa-eye text-xs"></i></a>
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.questions.typologies.edit', $item->id) }}" class="btn-icon btn-edit"><i class="fas fa-pen text-xs"></i></a>
                                        <button type="button" onclick="confirmDelete('{{ $item->nama_tipologi }}', '{{ route('admin.questions.typologies.destroy', $item->id) }}')" class="btn-icon btn-delete"><i class="fas fa-trash text-xs"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center; padding: 3rem; color: #94a3b8;">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($typologies->hasPages())
        <div class="pagination-wrapper">
            <div style="font-size: 0.85rem; color: #64748b;">Halaman <span style="font-weight: 700; color: #22c55e;">{{ $typologies->currentPage() }}</span></div>
            <div style="display: flex; gap: 10px;">
                @if ($typologies->onFirstPage())
                    <span class="btn-paginate disabled">Sebelumnya</span>
                @else
                    <a href="{{ $typologies->previousPageUrl() }}" class="btn-paginate">Sebelumnya</a>
                @endif
                @if ($typologies->hasMorePages())
                    <a href="{{ $typologies->nextPageUrl() }}" class="btn-paginate">Selanjutnya</a>
                @else
                    <span class="btn-paginate disabled">Selanjutnya</span>
                @endif
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    let debounceTimer;
    $('#typologySearch').on('input', function() {
        const value = $(this).val().toLowerCase();
        $('.loading-spinner').show();
        $('.search-icon').hide();

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            $('#typologyTableBody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
            $('.loading-spinner').hide();
            $('.search-icon').show();
        }, 500);
    });

    function toggleText(btn) {
        const shortText = $(btn).siblings('.short-text');
        const fullText = $(btn).siblings('.full-text');
        if (fullText.is(':visible')) {
            fullText.hide(); shortText.show(); $(btn).text('Lihat');
        } else {
            fullText.show(); shortText.hide(); $(btn).text('Tutup');
        }
    }

    function exportTypologies() {
        let baseUrl = "{{ route('admin.questions.typologies.export') }}";
        let search = $('#typologySearch').val();

        let params = new URLSearchParams();
        if(search) {
            params.append('search', search);
        }
        window.open(baseUrl + "?" + params.toString(), '_blank');
    }

    function confirmDelete(name, url) {
        Swal.fire({
            title: 'Hapus Tipologi?',
            html: `Yakin ingin menghapus <b>${name}</b>?`,
            icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#f1f5f9',
            confirmButtonText: 'Ya, Hapus', cancelButtonText: '<span style="color:black">Batal</span>',
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form'); form.method = 'POST'; form.action = url;
                form.innerHTML = '@csrf @method("DELETE")'; document.body.appendChild(form); form.submit();
            }
        });
    }
</script>
@endpush
