@extends('layouts.app')

@section('content')
<div class="card">
    <h2 style="margin-top:0;">Rekap Laporan</h2>

    <form action="{{ route('reports.recap') }}" method="GET" style="margin-bottom:16px;">
        <label for="severity">Tingkat Kondisi</label>
        <select id="severity" name="severity" style="width:220px; margin-right:8px;">
            <option value="">Semua</option>
            @foreach(['rendah', 'sedang', 'tinggi'] as $sev)
                <option value="{{ $sev }}" {{ $severityFilter === $sev ? 'selected' : '' }}>
                    {{ ucfirst($sev) }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('reports.recap') }}" class="btn btn-secondary">Reset</a>
    </form>

    <p>
        Total laporan: <strong>{{ $totalReports }}</strong><br>
        Laporan aktif: <strong>{{ $activeReports }}</strong><br>
        Laporan selesai: <strong>{{ $completedReports }}</strong>
    </p>

    <table>
        <thead>
            <tr>
                <th>Wilayah</th>
                <th>Total</th>
                <th>Aktif</th>
                <th>Diteruskan</th>
                <th>Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($areaRecaps as $area)
            <tr>
                <td>{{ $area->name }}</td>
                <td>{{ $area->total_count }}</td>
                <td>{{ $area->active_count }}</td>
                <td>{{ $area->forwarded_count }}</td>
                <td>{{ $area->completed_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection