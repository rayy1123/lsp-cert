@extends('layouts.astro')

@section('title', 'Data Peserta - LSP System')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Peserta Sertifikasi</h2>
        <p class="page-desc">Kelola administrasi asesi dan status rekomendasi uji.</p>
    </div>
    <button class="btn btn-primary" onclick="openCreateModal()">
        + Daftarkan Peserta
    </button>
</div>

<!-- Search & Filter bar -->
<div style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('lsp.participants') }}" style="display: flex; gap: 0.75rem; max-width: 480px;">
        <input type="text" name="search" class="form-control" placeholder="Cari nama, no. reg, skema..." value="{{ $search }}">
        <button type="submit" class="btn btn-secondary">Cari</button>
        @if($search)
            <a href="{{ route('lsp.participants') }}" class="btn btn-secondary" style="color: var(--text-dim);">Reset</a>
        @endif
    </form>
</div>

<div class="table-container">
    <table class="astro-table">
        <thead>
            <tr>
                <th>No. Reg</th>
                <th>Nama Peserta</th>
                <th>Kontak</th>
                <th>Skema</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants as $p)
                <tr>
                    <td style="font-family: var(--font-mono); font-size: 0.8rem;">
                        <span class="badge badge-code">{{ $p->registration_number }}</span>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $p->full_name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-dim);">{{ Str::limit($p->address ?: 'Alamat belum diisi', 35) }}</div>
                    </td>
                    <td>
                        <div style="font-size: 0.825rem;">{{ $p->email }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-family: var(--font-mono);">{{ $p->phone_number }}</div>
                    </td>
                    <td>
                        <span style="font-size: 0.825rem; font-weight: 500;">{{ $p->scheme->scheme_name ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="badge {{ str_contains($p->status, 'Kompeten') ? 'badge-kompeten' : 'badge-belum' }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <button class="btn btn-secondary" style="padding: 0.35rem 0.65rem; font-size: 0.75rem;" onclick='openEditModal(@json($p))'>
                            Edit
                        </button>
                        <form action="{{ route('lsp.participants.destroy', $p) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Hapus data peserta ini?')">
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
                    <td colspan="6" style="text-align: center; color: var(--text-dim); padding: 2rem;">
                        Tidak ada data peserta yang ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Form -->
<div class="modal-overlay" id="participantModal">
    <div class="modal-box" style="max-width: 600px;">
        <h3 class="modal-title" id="modalTitle">Daftarkan Peserta</h3>
        <form id="participantForm" method="POST" action="{{ route('lsp.participants.store') }}">
            @csrf
            <div id="methodContainer"></div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Skema Sertifikasi</label>
                    <select name="scheme_id" id="scheme_id" class="form-select" required>
                        <option value="">Pilih Skema</option>
                        @foreach($schemes as $s)
                            <option value="{{ $s->id }}">{{ $s->scheme_code }} - {{ $s->scheme_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">No. Registrasi / NIK</label>
                    <input type="text" name="registration_number" id="registration_number" class="form-control" placeholder="REG-2026-001" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Nama asesi..." required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="asesi@mail.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">No. HP / WA</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="08..." required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Status Asesmen</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="Belum Kompeten">Belum Kompeten</option>
                    <option value="Kompensasi / Kompeten">Kompensasi / Kompeten</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Domisili</label>
                <textarea name="address" id="address" class="form-control" rows="2" placeholder="Alamat lengkap..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Peserta</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('participantModal');
    const form = document.getElementById('participantForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodContainer = document.getElementById('methodContainer');

    function openCreateModal() {
        modalTitle.innerText = 'Daftarkan Peserta Baru';
        form.action = "{{ route('lsp.participants.store') }}";
        methodContainer.innerHTML = '';
        document.getElementById('scheme_id').value = '';
        document.getElementById('registration_number').value = '';
        document.getElementById('full_name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('phone_number').value = '';
        document.getElementById('status').value = 'Belum Kompeten';
        document.getElementById('address').value = '';
        modal.classList.add('active');
    }

    function openEditModal(p) {
        modalTitle.innerText = 'Edit Data Peserta';
        form.action = `/lsp/participants/${p.id}`;
        methodContainer.innerHTML = '@method("PUT")';
        document.getElementById('scheme_id').value = p.scheme_id;
        document.getElementById('registration_number').value = p.registration_number;
        document.getElementById('full_name').value = p.full_name;
        document.getElementById('email').value = p.email;
        document.getElementById('phone_number').value = p.phone_number;
        document.getElementById('status').value = p.status;
        document.getElementById('address').value = p.address || '';
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
