@extends('layout')
@section('content')
<div class="container">
    <div class="card">
        <h2 style="text-align: center; color: #ff758c;">Únete a Kinddel</h2>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Apellido</label>
                <input type="text" name="lastname" required>
            </div>

            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Sexo (¿Qué eres?)</label>
                <select name="sexo" required>
                    <option value="Hombre">Hombre</option>
                    <option value="Mujer">Mujer</option>
                </select>
            </div>

            <div class="form-group">
                <label>Preferencia (¿Qué buscas ver?)</label>
                <select name="preferencia" required>
                    <option value="Hombre">Hombres</option>
                    <option value="Mujer">Mujeres</option>
                    <option value="Ambos">Ambos</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="background: #ff758c; color: white; width:100%; margin-top: 10px;">Registrarse</button>
        </form>
    </div>
</div>
@endsection