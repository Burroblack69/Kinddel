@extends('layout')

@section('content')
<div class="main-layout">

    <div class="sidebar-left">
        <h3 style="color: #ff758c; margin-top: 0;">Usuarios</h3>
        <p style="font-size: 0.8rem; color: #777;">Selecciona para enviar mensaje:</p>
        
        @foreach($users as $user)
            <a href="{{ route('citas.index', ['chat_with' => $user->id]) }}" class="user-item">
                <strong>{{ $user->name }} {{ $user->lastname }}</strong>
            </a>
        @endforeach
    </div>

    <div class="content-center">
        
        @if($usuarioAChatear)
            <div class="chat-box">
                <h4 style="margin: 0 0 10px 0; color: #d63031;">
                    Enviar mensaje a: {{ $usuarioAChatear->name }}
                    <a href="{{ route('citas.index') }}" style="float:right; text-decoration:none; font-size:1rem; color: #555;">&times;</a>
                </h4>
                <form action="{{ route('mensajes.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $usuarioAChatear->id }}">
                    <textarea name="mensaje" rows="3" placeholder="Escribe tu mensaje aquí..." style="width:100%; border:1px solid #ddd; border-radius:5px; padding:5px; resize: none;" required></textarea>
                    <button type="submit" class="btn btn-primary" style="margin-top:5px; font-size:0.8rem;">Enviar</button>
                </form>
            </div>
        @endif

        <div class="card" style="margin-bottom: 30px;">
            <h2 style="font-family: 'Pacifico'; color: #ff758c; margin-top:0;">Proponer una Cita</h2>
            <form action="{{ route('citas.store') }}" method="POST">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div class="form-group"><label>Lugar</label><input type="text" name="lugar" required></div>
                    <div class="form-group"><label>Fecha</label><input type="date" name="fecha" required></div>
                    <div class="form-group"><label>Hora</label><input type="time" name="hora" required></div>
                    <div class="form-group"><label>¿Qué buscas?</label>
                        <select name="que_busca">
                            <option>Amistad</option>
                            <option>Vacilar</option>
                            <option>Algo serio</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="background: #ff758c; color: white;">Crear Cita</button>
            </form>
        </div>

        <h2 style="font-family: 'Pacifico'; text-align: center; color: #555; font-size: 2.5rem;">Listado de citas</h2>
        
        @foreach($citas as $cita)
        <div class="cita-card">
            <div class="cita-info">
                <h3 style="color: #333; font-weight: normal; font-size: 1.1rem; margin-top:0;">
                    <strong style="color: #ff758c; text-transform: capitalize;">{{ $cita->creador_nombre }} {{ $cita->creador_apellido }}</strong> 
                    quiere ir a <strong>{{ $cita->lugar }}</strong> 
                    el día {{ $cita->fecha }} a las {{ $cita->hora }} 
                    y está buscando <strong style="text-decoration: underline;">{{ $cita->que_busca }}</strong>.
                </h3>

                <div style="margin-top: 10px;">
                    @if($cita->estado == 'pendiente')
                        <span class="badge pendiente" style="background:#ffeaa7; padding:5px 10px; border-radius:10px;">Esperando respuesta...</span>
                    @else
                        <span class="badge {{ $cita->estado }}" style="padding: 5px 10px; border-radius: 10px;">
                            @if($cita->responder_id)
                                <strong>{{ $cita->responder_nombre }} {{ $cita->responder_apellido }}</strong>
                            @else
                                Alguien 
                            @endif
                            {{ $cita->estado == 'confirmada' ? 'aceptó' : 'rechazó' }} esta cita.
                        </span>
                    @endif
                </div>
            </div>

            <div class="actions">
                @if($cita->user_id == Auth::id())
                    <a href="{{ route('citas.edit', $cita->id) }}" class="btn-edit">Editar</a>
                    <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete">Borrar</button>
                    </form>
                @else
                    @if($cita->estado == 'pendiente')
                        <form action="{{ route('citas.responder', $cita->id) }}" method="POST" style="display:inline;">
                            @csrf <input type="hidden" name="estado" value="confirmada">
                            <button type="submit" class="btn-confirm">Aceptar</button>
                        </form>
                        <form action="{{ route('citas.responder', $cita->id) }}" method="POST" style="display:inline;">
                            @csrf <input type="hidden" name="estado" value="negada">
                            <button type="submit" class="btn-deny">Rechazar</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="sidebar-right">
        <input type="checkbox" id="toggle-inbox">
        <label for="toggle-inbox" class="inbox-label">📩 Ver mis mensajes</label>

        <div class="inbox-container">
            <h4 style="margin-top:0;">Buzón de Entrada</h4>
            @if($misMensajes->isEmpty())
                <p style="color:#999; font-size: 0.9rem;">No tienes mensajes nuevos.</p>
            @else
                @foreach($misMensajes as $msg)
                    <div class="message-card">
                        <div style="font-weight: bold; color: #6c5ce7; margin-bottom: 2px;">
                            {{ $msg->remitente_nombre }} {{ $msg->remitente_apellido }}
                        </div>
                        
                        <p style="margin: 0 0 5px 0; font-style: italic; color: #555;">"{{ $msg->mensaje }}"</p>
                        <small style="font-size: 0.7rem; color: #aaa; display:block; margin-bottom:5px;">{{ $msg->created_at }}</small>
                        
                        <a href="{{ route('citas.index', ['chat_with' => $msg->sender_id]) }}" class="btn-reply">
                            Responder ↩
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

</div>
@endsection