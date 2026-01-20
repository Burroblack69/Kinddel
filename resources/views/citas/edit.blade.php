@extends('layout')
@section('content')
<div class="container">
    <div class="card">
        <h2>Editar Cita</h2>
        <form action="{{ route('citas.update', $cita->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><label>Lugar</label><input type="text" name="lugar" value="{{ $cita->lugar }}" required></div>
            <div class="form-group"><label>Fecha</label><input type="date" name="fecha" value="{{ $cita->fecha }}" required></div>
            <div class="form-group"><label>Hora</label><input type="time" name="hora" value="{{ $cita->hora }}" required></div>
            <div class="form-group"><label>¿Qué buscas?</label>
                <select name="que_busca">
                    <option {{ $cita->que_busca == 'Amistad' ? 'selected' : '' }}>Amistad</option>
                    <option {{ $cita->que_busca == 'Vacilar' ? 'selected' : '' }}>Vacilar</option>
                    <option {{ $cita->que_busca == 'Algo serio' ? 'selected' : '' }}>Algo serio</option>
                </select>
            </div>
            <button type="submit" class="btn btn-edit" style="width: 100%">Actualizar Cita</button>
        </form>
        <br>
        <a href="{{ route('citas.index') }}">Cancelar</a>
    </div>
</div>
@endsection