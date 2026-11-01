@extends('layouts.app')

@section('content')
<div class="card">
    <h2 style="margin-top: 0;">Buat Laporan Indikasi Kebakaran Hutan / Lahan</h2>
    <p style="color: #475569; font-size: 14px;">Lengkapi lokasi koordinat, kondisi yang terlihat, dan unggah foto/dokumen bukti pendukung.</p>

    <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Judul Laporan</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Asap tebal di dekat kanal blok timur" required>
        </div>

        <div class="form-group">
            <label for="area_id">Area / Wilayah Kejadian</label>
            <select id="area_id" name="area_id" required>
                <option value="">-- Pilih Area / Wilayah --</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                        {{ $area->name }} ({{ $area->district }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Kotak informasi asynchronous area (akan diaktifkan pada Step 5) -->
        <div id="area-async-info" style="display:none; background:#fffbeb; border:1px solid #fde68a; padding:12px; border-radius:6px; margin-bottom:14px; font-size:14px;"></div>

        <div class="grid-3">
            <div class="form-group">
                <label for="incident_time">Waktu Kejadian Dilihat</label>
                <input type="datetime-local" id="incident_time" name="incident_time" value="{{ old('incident_time') }}" required>
            </div>
            <div class="form-group">
                <label for="latitude">Latitude (-90 s.d. 90)</label>
                <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude') }}" placeholder="-0.123456" required>
            </div>
            <div class="form-group">
                <label for="longitude">Longitude (-180 s.d. 180)</label>
                <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude') }}" placeholder="109.123456" required>
            </div>
        </div>

        <div class="form-group">
            <label for="severity">Tingkat Kondisi Indikasi</label>
            <select id="severity" name="severity" required>
                <option value="rendah" {{ old('severity') === 'rendah' ? 'selected' : '' }}>Rendah (Asap tipis / titik kecil)</option>
                <option value="sedang" {{ old('severity') === 'sedang' ? 'selected' : '' }}>Sedang (Asap pekat / mulai meluas)</option>
                <option value="tinggi" {{ old('severity') === 'tinggi' ? 'selected' : '' }}>Tinggi (Api terlihat jelas / dekat pemukiman atau kebun)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi Kondisi di Lokasi</label>
            <textarea id="description" name="description" rows="4" placeholder="Jelaskan kondisi asap/api, arah angin, atau patokan lokasi..." required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="evidence">File Bukti Pendukung (JPG, JPEG, PNG, atau PDF - Maks. 2 MB)</label>
            <input type="file" id="evidence" name="evidence" accept=".jpg,.jpeg,.png,.pdf" required>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Laporan</button>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection