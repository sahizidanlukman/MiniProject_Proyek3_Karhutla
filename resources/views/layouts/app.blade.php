<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiKarhutla - Pelaporan & Pemantauan Kebakaran Hutan dan Lahan</title>
    <style>
        * { box-sizing: border-box; font-family: system-ui, -apple-system, sans-serif; }
        body { margin: 0; background: #f4f6f8; color: #1e293b; }
        nav { background: #991b1b; color: #fff; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: #fff; text-decoration: none; margin-right: 16px; font-weight: 500; }
        nav a:hover { text-decoration: underline; }
        .container { max-width: 1100px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
        .btn { display: inline-block; padding: 8px 14px; border-radius: 6px; border: none; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: 600; }
        .btn-primary { background: #b91c1c; color: #fff; }
        .btn-secondary { background: #475569; color: #fff; }
        .btn-warning { background: #d97706; color: #fff; }
        .btn-success { background: #15803d; color: #fff; }
        .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        .alert-error { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; font-size: 14px; }
        th { background: #f1f5f9; }
        .form-group { margin-bottom: 14px; }
        label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px; }
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="datetime-local"], select, textarea {
            width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;
        }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px; }
    </style>
</head>
<body>
    <nav>
        <div>
            <strong>SiKarhutla Komunitas</strong>
            @auth
                <a href="{{ route('reports.index') }}" style="margin-left: 20px;">Daftar Laporan</a>
                @if(auth()->user()->role === 'pelapor')
                    <a href="{{ route('reports.create') }}">+ Buat Laporan</a>
                @endif
                @if(in_array(auth()->user()->role, ['relawan', 'koordinator']))
                    <a href="{{ route('reports.recap') }}">Rekap Laporan</a>
                @endif
            @endauth
        </div>
        <div>
            @auth
                <span style="margin-right: 12px;">
                    {{ auth()->user()->name }} (<strong>{{ strtoupper(auth()->user()->role) }}</strong>)
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