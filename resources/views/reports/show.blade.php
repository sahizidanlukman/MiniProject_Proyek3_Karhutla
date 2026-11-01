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
            <strong>Tingkat Kondisi Indikasi:</strong><br>
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
        @if(\Illuminate\Support\Str::endsWith($report->evidence_path, '.pdf'))
            <a href="{{ asset('storage/' . $report->evidence_path) }}" target="_blank" class="btn btn-secondary" style="margin-top:8px;">
                Lihat Dokumen Bukti (PDF)
            </a>
        @else
            <div style="margin-top:8px;">
                <img src="{{ asset('storage/' . $report->evidence_path) }}" alt="Bukti Laporan" style="max-width:420px; border-radius:6px; border:1px solid #cbd5e1;">
            </div>
        @endif
    </div>

    {{-- Tombol Edit khusus Pelapor pemilik laporan jika status masih menunggu_tinjauan (BR-01) --}}
    @if(auth()->user()->role === 'pelapor' && $report->user_id === auth()->id() && $report->status === 'menunggu_tinjauan')
        <div style="margin-top:16px;">
            <a href="{{ route('reports.edit', $report) }}" class="btn btn-warning">Perbaiki Laporan</a>
        </div>
    @endif
</div>

<!-- Bagian Informasi Tinjauan & Tindak Lanjut -->
<div class="card">
    <h3 style="margin-top:0;">Hasil Peninjauan & Tindak Lanjut</h3>
    <p style="font-size:14px; margin:6px 0;">
        <strong>Peninjau (Relawan):</strong> {{ $report->reviewer?->name ?? 'Belum ditinjau' }}<br>
        <strong>Catatan Relawan:</strong> {{ $report->review_notes ?? '-' }}<br>
        <strong>Rekomendasi Diteruskan:</strong> {{ $report->needs_forwarding ? 'Ya, Perlu Diteruskan ke Pihak Terkait' : 'Tidak / Belum Ditandai' }}
    </p>
    <p style="font-size:14px; margin:6px 0;">
        <strong>Diteruskan Kepada (Pihak Eksternal):</strong> {{ $report->forwarded_to ?? '-' }}<br>
        <strong>Waktu Diteruskan:</strong> {{ $report->forwarded_at ? $report->forwarded_at->format('d M Y H:i') : '-' }}
    </p>
</div>

<!-- Panel Aksi untuk Relawan Pemantau (dan Koordinator) selama status belum final (BR-03) -->
@if(auth()->user()->role === 'relawan' && !in_array($report->status, ['selesai', 'ditolak']))
<div class="card">
    <h3 style="margin-top:0;">Form Peninjauan Relawan Pemantau</h3>
    <form action="{{ route('reports.review', $report) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="review_notes">Catatan Kelengkapan & Kewajaran Laporan</label>
            <textarea id="review_notes" name="review_notes" rows="3" placeholder="Tuliskan hasil pemeriksaan koordinat, bukti foto, dan kewajaran laporan..." required>{{ old('review_notes', $report->review_notes) }}</textarea>
        </div>

        <div class="form-group">
            <label style="font-weight:normal; cursor:pointer;">
                <input type="checkbox" name="needs_forwarding" value="1" {{ old('needs_forwarding', $report->needs_forwarding) ? 'checked' : '' }}>
                Tandai laporan ini perlu diteruskan kepada pihak terkait (<strong>{{ $report->area->contact_agency }}</strong>)
            </label>
        </div>

        <div class="form-group">
            <label for="status_review">Keputusan Tinjauan</label>
            <select id="status_review" name="status" required>
                <option value="ditinjau" {{ $report->status === 'ditinjau' ? 'selected' : '' }}>Valid / Ditinjau</option>
                <option value="ditolak" {{ $report->status === 'ditolak' ? 'selected' : '' }}>Ditolak (Informasi Tidak Wajar / Duplikat)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Hasil Tinjauan</button>
    </form>
</div>
@endif

<!-- Panel Aksi Khusus Koordinator Relawan selama status belum final -->
@if(auth()->user()->role === 'koordinator' && !in_array($report->status, ['selesai', 'ditolak']))
<div class="card">
    <h3 style="margin-top:0;">Form Koordinasi & Penerusan Pihak Terkait (Khusus Koordinator)</h3>
    <p style="font-size:13px; color:#475569;">
        Catatan: Laporan hanya dapat diteruskan jika sudah berstatus <strong>ditinjau</strong> oleh Relawan Pemantau dan memiliki catatan tinjauan.
    </p>

    <form action="{{ route('reports.coordinate', $report) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="status_coord">Tindakan Koordinator</label>
            <select id="status_coord" name="status" required>
                <option value="diteruskan" {{ $report->status === 'diteruskan' ? 'selected' : '' }}>Catat Diteruskan ke Pihak Terkait</option>
                <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>Tutup / Selesai Dipantau di Komunitas</option>
            </select>
        </div>

        <div class="form-group">
            <label for="forwarded_to">Nama Instansi / Pihak Terkait Penerima Laporan</label>
            <input type="text" id="forwarded_to" name="forwarded_to" value="{{ old('forwarded_to', $report->forwarded_to ?? $report->area->contact_agency) }}" placeholder="Contoh: BPBD Kab. Kubu Raya & Manggala Agni">
        </div>

        <button type="submit" class="btn btn-success">Simpan Tindak Lanjut Koordinator</button>
    </form>
</div>
@endif

<!-- Daftar Laporan Lain pada Area yang Sama (Subbab 5.7 - Khusus Relawan & Koordinator) -->
@if(in_array(auth()->user()->role, ['relawan', 'koordinator']))
<div class="card">
    <h3 style="margin-top:0;">Laporan Lain pada Area yang Sama ({{ $report->area->name }})</h3>
    @if($relatedReports->isEmpty())
        <p style="font-size:14px; color:#475569;">Tidak ada laporan lain di area ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Waktu Kejadian</th>
                    <th>Tingkat Kondisi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($relatedReports as $item)
                <tr>
                    <td>#{{ $item->id }}</td>
                    <td><a href="{{ route('reports.show', $item) }}">{{ $item->title }}</a></td>
                    <td>{{ $item->incident_time->format('d M Y H:i') }}</td>
                    <td>{{ strtoupper($item->severity) }}</td>
                    <td>{{ strtoupper(str_replace('_', ' ', $item->status)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endif

<!-- Riwayat Perubahan Status dari Observer (Akan terisi otomatis setelah Step 7) -->
<div class="card">
    <h3 style="margin-top:0;">Riwayat Perubahan Status (Dicatat oleh Observer)</h3>
    @if($report->statusLogs->isEmpty())
        <p style="font-size:14px; color:#475569;">Belum ada perubahan status sejak laporan dibuat.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Waktu Perubahan</th>
                    <th>Status Sebelum</th>
                    <th>Status Sesudah</th>
                    <th>Diubah Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report->statusLogs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                    <td>{{ $log->old_status }}</td>
                    <td><strong>{{ $log->new_status }}</strong></td>
                    <td>{{ $log->user?->name ?? 'Sistem' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection