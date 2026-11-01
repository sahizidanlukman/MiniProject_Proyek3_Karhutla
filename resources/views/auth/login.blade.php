@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 420px; margin: 40px auto;">
    <h2 style="margin-top: 0;">Login Karhutla.app</h2>
    <p style="color: #475569; font-size: 14px;">Silakan masuk untuk melaporkan atau memantau indikasi kebakaran hutan dan lahan.</p>

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Masuk</button>
    </form>
</div>
@endsection