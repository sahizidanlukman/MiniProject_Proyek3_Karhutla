@extends('layouts.app')

@section('content')
<div class="card">
    <h2 style="margin-top: 0;">Perbaiki Laporan #{{ $report->id }}</h2>
    <p style="color: #475569; font-size: 14px;">Perubahan hanya diizinkan selama laporan masih berstatus <strong>menunggu_tinjauan</strong> (BR-01).</p>

    <form action="{{ route('reports.update', $report) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Judul Laporan</label>
            <input type="text" id="title" name="title" value="{{ old('title', $report->title) }}" required>
        </div>

        <div class="form-group">
            <label for="area_id">Area / Wilayah Kejadian</label>
            <select id="area_id" name="area_id" required>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ old('area_id', $report->area_id) == $area->id ? 'selected' : '' }}>
                        {{ $area->name }} ({{ $area->district }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid-3">
            <div class="form-group">
                <label for="incident_time">Waktu Kejadian Dilihat</label>
                <input type="datetime-local" id="incident_time" name="incident_time" value="{{ old('incident_time', $report->incident_time->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="form-group">
                <label for="latitude">Latitude (-90 s.d. 90)</label>
                <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', $report->latitude) }}" required>
            </div>
            <div class="form-group">
                <label for="longitude">Longitude (-180 s.d. 180)</label>
                <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', $report->longitude) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="severity">Tingkat Kondisi Indikasi</label>
            <select id="severity" name="severity" required>
                @foreach(['rendah', 'sedang', 'tinggi'] as $level)
                    <option value="{{ $level }}" {{ old('severity', $report->severity) === $level ? 'selected' : '' }}>
                        {{ ucfirst($level) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi Kondisi di Lokasi</label>
            <textarea id="description" name="description" rows="4" required>{{ old('description', $report->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="evidence">Ganti File Bukti (Opsional - kosongkan jika tidak diganti)</label>
            <input type="file" id="evidence" name="evidence" accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('reports.show', $report) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection