<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    // Muestra la lista y el chat
    public function index(Request $request)
    {
        $userId = Auth::id();

        // 1. Obtener Citas con datos del Creador Y del que Responde
        $citas = DB::table('citas')
            ->join('users as creadores', 'citas.user_id', '=', 'creadores.id')
            ->leftJoin('users as respondedores', 'citas.responder_id', '=', 'respondedores.id')
            ->select(
                'citas.*', 
                'creadores.name as creador_nombre', 
                'creadores.lastname as creador_apellido',
                'respondedores.name as responder_nombre',
                'respondedores.lastname as responder_apellido'
            )
            ->orderBy('citas.created_at', 'desc')
            ->get();

        // 2. Usuarios para el chat
        $users = DB::table('users')->where('id', '!=', $userId)->get();

        // 3. Mis mensajes
        $misMensajes = DB::table('mensajes')
            ->join('users', 'mensajes.sender_id', '=', 'users.id')
            ->where('receiver_id', $userId)
            ->select('mensajes.*', 'users.name as remitente_nombre
            ', 'users.lastname as remitente_apellido')
            ->orderBy('created_at', 'desc')
            ->get();

        // 4. Lógica de chat seleccionado
        $usuarioAChatear = null;
        if ($request->has('chat_with')) {
            $usuarioAChatear = DB::table('users')->where('id', $request->chat_with)->first();
        }

        return view('citas.index', compact('citas', 'users', 'misMensajes', 'usuarioAChatear'));
    }

    // ESTA ES LA FUNCIÓN QUE TE FALTABA (Guardar Cita)
    public function store(Request $request)
    {
        DB::table('citas')->insert([
            'user_id' => Auth::id(),
            'lugar' => $request->lugar,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'que_busca' => $request->que_busca,
            'estado' => 'pendiente',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return back()->with('success', 'Cita publicada exitosamente');
    }

    // Responder (Aceptar/Negar) guardando QUIÉN lo hizo
    public function responder(Request $request, $id)
    {
        DB::table('citas')->where('id', $id)->update([
            'estado' => $request->estado,
            'responder_id' => Auth::id(), // <--- AQUÍ GUARDAMOS QUIÉN RESPONDE
            'updated_at' => now()
        ]);
        return back();
    }

    // Funciones extra (Editar, Actualizar, Borrar, Chat)
    public function edit($id) {
        $cita = DB::table('citas')->where('id', $id)->first();
        if($cita->user_id != Auth::id()) return redirect()->route('citas.index');
        return view('citas.edit', compact('cita'));
    }

    public function update(Request $request, $id) {
        DB::table('citas')->where('id', $id)->update([
            'lugar' => $request->lugar, 
            'fecha' => $request->fecha, 
            'hora' => $request->hora, 
            'que_busca' => $request->que_busca
        ]);
        return redirect()->route('citas.index');
    }

    public function destroy($id) {
        $cita = DB::table('citas')->where('id', $id)->first();
        if($cita->user_id == Auth::id()) DB::table('citas')->where('id', $id)->delete();
        return back();
    }

    public function enviarMensaje(Request $request) {
        DB::table('mensajes')->insert([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'mensaje' => $request->mensaje,
            'created_at' => now()
        ]);
        return back();
    }
}