@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:flex-start;">
        <div>
            <h2 style="margin:0 0 6px 0;">Laporan #{{ $report->id }}: {{ $report->title }}</h2>
            <div style="font-size:14px; color:#475569;">
                Dilaporkan oleh <strong>{{ $report->reporter->name }}</strong> pada {{ $report->created_at->format('d M Y H:i') }}
            </div>
        </div>
        <div>
            <span class="badge" style="background:#e2e8f0; color:#0f172a; font-size:14px;">
                Status: {{ strtoupper(str_replace('_', ' ', $report->status)) }}
            </span>
        </div>
    </div>

    <hr style="border:none; border-top:1px solid #e2e8f0; margin:16px 0;">

    <div class="grid-3">
        <div>
            <strong>Area / Wilayah:</strong><br>
            {{ $report->area->name }}<br>
            <small style="color:#475569;">{{ $report->area->district }}</small>
        </div>
        <div>
            <strong>Koordinat Lokasi:</strong><br>
            Lat: {{ $report->latitude }}, Lng: {{ $report->longitude }}<br>
            <small style="color:#475569;">Waktu Kejadian: {{ $report->incident_time->format('d M Y H:i') }}</small>
        </div>
        <div>
            <strong>Tingkat Kondisi:</strong><br>
            <span style="font-weight:700; color:{{ $report->severity === 'tinggi' ? '#b91c1c' : ($report->severity === 'sedang' ? '#d97706' : '#15803d') }};">
                {{ strtoupper($report->severity) }}
            </span>
        </div>
    </div>

    <div style="margin-top:16px;">
        <strong>Deskripsi Kejadian:</strong>
        <p style="background:#f8fafc; padding:12px; border-radius:6px; border:1px solid #e2e8f0;">{{ $report->description }}</p>
    </div>

    <div style="margin-top:16px;">
        <strong>Bukti Pendukung:</strong><br>
        @if(Str::endsWith($report->evidence_path, '.pdf'))
            <a href="{{ asset('storage/' . $report->evidence_path) }}" target="_blank" class="btn btn-secondary" style="margin-top:8px;">
                Lihat Dokumen Bukti (PDF)
            </a>
        @else
            <div style="margin-top:8px;">
                <img src="{{ asset('storage/' . $report->evidence_path) }}" alt="Bukti Laporan" style="max-width:420px; border-radius:6px; border:1px solid #cbd5e1;">
            </div>
        @endif
    </div>

    @if(auth()->user()->role === 'pelapor' && $report->user_id === auth()->id() && $report->status === 'menunggu_tinjauan')
        <div style="margin-top:20px;">
            <a href="{{ route('reports.edit', $report) }}" class="btn btn-warning">Perbaiki Laporan</a>
        </div>
    @endif
</div>

<!-- Informasi Hasil Tinjauan Relawan & Koordinator -->
<div class="card">
    <h3 style="margin-top:0;">Hasil Peninjauan & Tindak Lanjut</h3>
    <p style="font-size:14px; margin:6px 0;">
        <strong>Peninjau (Relawan):</strong> {{ $report->reviewer?->name ?? 'Belum ditinjau' }}<br>
        <strong>Catatan Relawan:</strong> {{ $report->review_notes ?? '-' }}<br>
        <strong>Rekomendasi Diteruskan:</strong> {{ $report->needs_forwarding ? 'Ya, Perlu Diteruskan' : 'Tidak / Belum Ditandai' }}
    </p>
    <p style="font-size:14px; margin:6px 0;">
        <strong>Diteruskan Kepada:</strong> {{ $report->forwarded_to ?? '-' }}<br>
        <strong>Waktu Diteruskan:</strong> {{ $report->forwarded_at ? $report->forwarded_at->format('d M Y H:i') : '-' }}
    </p>
</div>
@endsection