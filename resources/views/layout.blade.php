<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Kinddel</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @auth
        <nav style="background: white; padding: 10px 40px; display:flex; justify-content:space-between; align-items:center; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            
            <h2 style="color: #ff758c; font-family: 'Pacifico'; margin:0;">Kinddel</h2>

            <div style="font-family: 'Poppins', sans-serif; font-size: 1.2rem; color: #555;">
                Bienvenido, <strong>{{ Auth::user()->name }}</strong>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary" style="background:#333; color:white; padding: 8px 20px; font-size: 0.9rem;">Cerrar Sesión</button>
            </form>
        </nav>
    @endauth

    @yield('content')
</body>
</html>