@extends('layout')
@section('content')
<div class="landing">
    <h1>Kinddel</h1>
    <div class="btn-group">
        <a href="{{ route('register') }}" class="btn btn-primary">Registrarse</a>
        <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
    </div>
    <div class="slogan">Kinddel Vacilar jamás fue tan fácil</div>
</div>
@endsection