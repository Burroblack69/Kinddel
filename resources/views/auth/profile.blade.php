@extends('layout')

@section('content')
<div class="container">
    <div class="card">
        <h2 style="text-align: center; color: #6c5ce7;">Edición del Perfil</h2>
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div style="text-align: center; margin-bottom: 20px;">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #6c5ce7;">
                @else
                    <div style="width: 100px; height: 100px; background: #ddd; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 2rem; color: #555;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                <br>
                <label style="cursor: pointer; color: #6c5ce7; text-decoration: underline; margin-top: 5px;">
                    Cambiar Foto
                    <input type="file" name="photo" style="display: none;">
                </label>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="name" value="{{ $user->name }}" required>
                </div>
                <div class="form-group">
                    <label>Apellido</label>
                    <input type="text" name="lastname" value="{{ $user->lastname }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="email" value="{{ $user->email }}" required>
            </div>

            <div class="form-group">
                <label>Nueva Contraseña (Déjalo vacío si no quieres cambiarla)</label>
                <input type="password" name="password" placeholder="********">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Sexo</label>
                    <select name="sexo" required>
                        <option value="Hombre" {{ $user->sexo == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                        <option value="Mujer" {{ $user->sexo == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Preferencia</label>
                    <select name="preferencia" required>
                        <option value="Hombre" {{ $user->preferencia == 'Hombre' ? 'selected' : '' }}>Hombres</option>
                        <option value="Mujer" {{ $user->preferencia == 'Mujer' ? 'selected' : '' }}>Mujeres</option>
                        <option value="Ambos" {{ $user->preferencia == 'Ambos' ? 'selected' : '' }}>Ambos</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary" style="background: #6c5ce7; flex: 1;">Guardar Cambios</button>
                <a href="{{ route('citas.index') }}" class="btn" style="background: #ddd; color: #333;">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection