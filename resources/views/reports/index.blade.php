@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2 style="margin:0;">Daftar Laporan Indikasi Karhutla</h2>
        @if(auth()->user()->role === 'pelapor')
            <a href="{{ route('reports.create') }}" class="btn btn-primary">+ Buat Laporan Baru</a>
        @endif
    </div>
    <p style="margin-top:12px; color:#64748b;">Halo, <strong>{{ auth()->user()->name }}</strong>! Anda berhasil masuk ke sistem Karhutla.app</p>
</div>
@endsection