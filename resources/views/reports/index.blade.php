@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
        <h2 style="margin:0;">Daftar Laporan Indikasi Karhutla</h2>
        @if(auth()->user()->role === 'pelapor')
            <a href="{{ route('reports.create') }}" class="btn btn-primary">+ Buat Laporan Baru</a>
        @endif
    </div>

    <form action="{{ route('reports.index') }}" method="GET" style="background:#f8fafc; padding:14px; border-radius:6px; border:1px solid #e2e8f0; margin-bottom:16px;">
        <div class="grid-3">
            <div class="form-group" style="margin-bottom:0;">
                <label for="search">Cari Judul / Deskripsi</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Kata kunci...">
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label for="area_id">Filter Wilayah</label>
                <select id="area_id" name="area_id">
                    <option value="">-- Semua Wilayah --</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label for="status">Filter Status</label>
                <select id="status" name="status">
                    <option value="">-- Semua Status --</option>
                    @foreach(['menunggu_tinjauan', 'ditinjau', 'diteruskan', 'selesai', 'ditolak'] as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                            {{ strtoupper(str_replace('_', ' ', $st)) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-top:12px;">
            <div style="width:32%;">
                <label for="severity">Filter Tingkat Kondisi</label>
                <select id="severity" name="severity">
                    <option value="">-- Semua Kondisi --</option>
                    @foreach(['rendah', 'sedang', 'tinggi'] as $sev)
                        <option value="{{ $sev }}" {{ request('severity') === $sev ? 'selected' : '' }}>
                            {{ ucfirst($sev) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul & Waktu Kejadian</th>
                <th>Area / Wilayah</th>
                <th>Pelapor</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
            <tr>
                <td>#{{ $report->id }}</td>
                <td>
                    <strong>{{ $report->title }}</strong><br>
                    <small style="color:#475569;">{{ $report->incident_time->format('d M Y H:i') }}</small>
                </td>
                <td>{{ $report->area->name }}</td>
                <td>{{ $report->reporter->name }}</td>
                <td>{{ strtoupper($report->severity) }}</td>
                <td>
                    <span class="badge" style="background:#e2e8f0;">
                        {{ strtoupper(str_replace('_', ' ', $report->status)) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('reports.show', $report) }}" class="btn btn-secondary">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#64748b;">Tidak ada data laporan yang sesuai dengan filter.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px;">
        {{ $reports->links() }}
    </div>
</div>
@endsection