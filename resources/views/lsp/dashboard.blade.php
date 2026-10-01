@extends('layouts.astro')

@section('title', 'Dashboard - LSP System')

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-title">Overview Ringkasan</h2>
        <p class="page-desc">Monitoring statistik peserta sertifikasi dan master skema LSP.</p>
    </div>
</div>

<div class="stat-grid">
    <div class="astro-card">
        <div class="stat-label">Total Peserta</div>
        <div class="stat-metric">{{ $totalParticipants }}</div>
    </div>
    <div class="astro-card">
        <div class="stat-label" style="color: var(--accent-emerald);">Peserta Kompeten</div>
        <div class="stat-metric" style="color: var(--accent-emerald);">{{ $totalKompeten }}</div>
    </div>
    <div class="astro-card">
        <div class="stat-label" style="color: var(--accent-amber);">Belum Kompeten</div>
        <div class="stat-metric" style="color: var(--accent-amber);">{{ $totalBelum }}</div>
    </div>
    <div class="astro-card">
        <div class="stat-label" style="color: var(--accent-purple);">Skema Aktif</div>
        <div class="stat-metric" style="color: var(--accent-purple);">{{ $totalSchemes }}</div>
    </div>
</div>

<div class="astro-card" style="padding: 0;">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-size: 1rem; font-weight: 700;">Peserta Terdaftar Terkini</h3>
        <a href="{{ route('lsp.participants') }}" class="btn btn-secondary" style="font-size: 0.75rem; padding: 0.35rem 0.75rem;">Lihat Semua</a>
    </div>

    <table class="astro-table">
        <thead>
            <tr>
                <th>No. Registrasi</th>
                <th>Nama Peserta</th>
                <th>Skema</th>
                <th>Status Asesmen</th>
                <th>Tanggal Masuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentParticipants as $p)
                <tr>
                    <td style="font-family: var(--font-mono); font-size: 0.8rem;">
                        <span class="badge badge-code">{{ $p->registration_number }}</span>
                    </td>
                    <td style="font-weight: 600;">{{ $p->full_name }}</td>
                    <td style="color: var(--text-muted);">{{ $p->scheme->scheme_name ?? '-' }}</td>
                    <td>
                        <span class="badge {{ str_contains($p->status, 'Kompeten') ? 'badge-kompeten' : 'badge-belum' }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td style="font-size: 0.8rem; color: var(--text-dim); font-family: var(--font-mono);">
                        {{ $p->created_at->format('d M Y') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-dim); padding: 2rem;">
                        Belum ada peserta terdaftar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
