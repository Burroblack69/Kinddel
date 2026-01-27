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

            <div style="display: flex; align-items: center; gap: 10px; font-family: 'Poppins', sans-serif;">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ff758c;">
                @else
                    <div style="width: 40px; height: 40px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #555;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
                
                <span style="font-size: 1.1rem; color: #555;">
                    Bienvenido, <strong>{{ Auth::user()->name }}</strong>
                </span>
            </div>

            <div style="display: flex; gap: 10px; align-items: center;">
                <a href="{{ route('profile.edit') }}" class="btn" style="background: #6c5ce7; color: white; padding: 8px 15px; font-size: 0.8rem;">
                    Edición del perfil
                </a>

                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="background:#333; color:white; padding: 8px 15px; font-size: 0.8rem;">Cerrar Sesión</button>
                </form>
            </div>
        </nav>
    @endauth

    @yield('content')
</body>
</html>