<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karhutla.app - Pelaporan & Pemantauan Kebakaran Hutan dan Lahan</title>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { margin: 0; background: #fff; color: #222; font-size: 15px; }

        nav { background: #b91c1c; color: #fff; padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: #fff; text-decoration: none; margin-right: 14px; }
        nav a:hover { text-decoration: underline; }

        .container { max-width: 1000px; margin: 20px auto; padding: 0 16px; }
        .card { border: 1px solid #ddd; padding: 16px; margin-bottom: 16px; }

        .btn { display: inline-block; padding: 6px 12px; border: 1px solid transparent; border-radius: 3px; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-primary { background: #b91c1c; color: #fff; }
        .btn-success { background: #b91c1c; color: #fff; }
        .btn-secondary { background: #fff; color: #333; border-color: #999; }
        .btn-warning { background: #fff; color: #b91c1c; border-color: #b91c1c; }

        .alert-success { background: #f3f3f3; border-left: 4px solid #2e7d32; padding: 10px 12px; margin-bottom: 16px; }
        .alert-error { background: #fdecec; border-left: 4px solid #b91c1c; color: #7f1d1d; padding: 10px 12px; margin-bottom: 16px; }

        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 14px; }
        th { background: #f3f3f3; }

        .form-group { margin-bottom: 14px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; font-size: 14px; }
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="datetime-local"], select, textarea {
            width: 100%; padding: 7px 8px; border: 1px solid #aaa; border-radius: 3px; font-size: 14px;
        }

        .badge { display: inline-block; padding: 2px 6px; border: 1px solid #bbb; border-radius: 3px; font-size: 12px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px; }

        .pagination { display: flex; gap: 6px; list-style: none; padding: 0; margin: 12px 0 0; }
        .pagination .page-link { display: inline-block; padding: 5px 10px; border: 1px solid #bbb; border-radius: 3px; text-decoration: none; color: #222; background: #fff; }
        .pagination .active .page-link { background: #b91c1c; color: #fff; border-color: #b91c1c; }
        .pagination .disabled .page-link { color: #999; }
    </style>
</head>
<body>
    <nav>
        <div>
            <strong>Karhutla.app</strong>
            @auth
                <a href="{{ route('reports.index') }}" style="margin-left: 20px;">Daftar Laporan</a>
                @if(auth()->user()->role === 'pelapor')
                    <a href="{{ route('reports.create') }}">+ Buat Laporan</a>
                @endif
                @if(in_array(auth()->user()->role, ['relawan', 'koordinator']) && Route::has('reports.recap'))
                    <a href="{{ route('reports.recap') }}">Rekap Laporan</a>
                @endif
            @endauth
        </div>
        <div>
            @auth
                <span style="margin-right: 12px;">
                    {{ auth()->user()->name }} ({{ strtoupper(auth()->user()->role) }})
                </span>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>