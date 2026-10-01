@extends('layouts.astro')

@section('title', 'Skema Sertifikasi - LSP System')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Skema Sertifikasi</h2>
        <p class="page-desc">Master data skema standar kompetensi LSP.</p>
    </div>
    <button class="btn btn-primary" onclick="openCreateModal()">
        + Tambah Skema
    </button>
</div>

<div class="table-container">
    <table class="astro-table">
        <thead>
            <tr>
                <th>Kode Skema</th>
                <th>Nama Skema Sertifikasi</th>
                <th>Deskripsi Singkat</th>
                <th>Peserta</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schemes as $scheme)
                <tr>
                    <td style="font-family: var(--font-mono); font-size: 0.8rem;">
                        <span class="badge badge-code">{{ $scheme->scheme_code }}</span>
                    </td>
                    <td style="font-weight: 600;">{{ $scheme->scheme_name }}</td>
                    <td style="color: var(--text-muted); font-size: 0.825rem; max-width: 320px;">
                        {{ $scheme->description ?: '-' }}
                    </td>
                    <td style="font-family: var(--font-mono); font-size: 0.85rem;">
                        {{ $scheme->participants_count }}
                    </td>
                    <td style="text-align: right;">
                        <button class="btn btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.75rem;" onclick='openEditModal(@json($scheme))'>
                            Edit
                        </button>
                        <form action="{{ route('lsp.schemes.destroy', $scheme) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Hapus skema sertifikasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.35rem 0.65rem; font-size: 0.75rem;">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-dim); padding: 2rem;">
                        Belum ada skema sertifikasi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Form -->
<div class="modal-overlay" id="schemeModal">
    <div class="modal-box">
        <h3 class="modal-title" id="modalTitle">Tambah Skema Baru</h3>
        <form id="schemeForm" method="POST" action="{{ route('lsp.schemes.store') }}">
            @csrf
            <div id="methodContainer"></div>

            <div class="form-group">
                <label class="form-label">Kode Skema</label>
                <input type="text" name="scheme_code" id="scheme_code" class="form-control" placeholder="MISAL: JWD-2026" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Skema</label>
                <input type="text" name="scheme_name" id="scheme_name" class="form-control" placeholder="Contoh: Junior Web Developer" required>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Uraian ringkas skema..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Skema</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('schemeModal');
    const form = document.getElementById('schemeForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');

    function openCreateModal() {
        modalTitle.innerText = 'Tambah Skema Baru';
        form.action = "{{ route('lsp.schemes.store') }}";
        methodContainer.innerHTML = '';
        document.getElementById('scheme_code').value = '';
        document.getElementById('scheme_name').value = '';
        document.getElementById('description').value = '';
        modal.classList.add('active');
    }

    function openEditModal(scheme) {
        modalTitle.innerText = 'Edit Skema';
        form.action = `/lsp/schemes/${scheme.id}`;
        methodContainer.innerHTML = '@method("PUT")';
        document.getElementById('scheme_code').value = scheme.scheme_code;
        document.getElementById('scheme_name').value = scheme.scheme_name;
        document.getElementById('description').value = scheme.description || '';
        modal.classList.add('active');
    }

    function closeModal() {
        modal.classList.remove('active');
    }

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
</script>
@endpush
@endsection
