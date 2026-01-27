<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // 1. Obtener datos del usuario actual para saber su preferencia
        $currentUser = DB::table('users')->where('id', $userId)->first();

        // 2. Consulta de Citas
        $query = DB::table('citas')
            ->join('users as creadores', 'citas.user_id', '=', 'creadores.id')
            ->leftJoin('users as respondedores', 'citas.responder_id', '=', 'respondedores.id')
            ->select(
                'citas.*', 
                'creadores.name as creador_nombre', 
                'creadores.lastname as creador_apellido',
                'creadores.sexo as creador_sexo', 
                'respondedores.name as responder_nombre',
                'respondedores.lastname as responder_apellido'
            );

        // FILTRO 1: No mostrar mis propias citas
        $query->where('citas.user_id', '!=', $userId);

        // FILTRO 2: Filtrar por Sexo según Preferencia
        if ($currentUser->preferencia !== 'Ambos') {
            $query->where('creadores.sexo', '=', $currentUser->preferencia);
        }

        $citas = $query->orderBy('citas.created_at', 'desc')->get();


        // 3. Usuarios Barra Lateral (Filtrados también)
        $usersQuery = DB::table('users')->where('id', '!=', $userId);
        if ($currentUser->preferencia !== 'Ambos') {
            $usersQuery->where('sexo', '=', $currentUser->preferencia);
        }
        $users = $usersQuery->get();


        // 4. Mis mensajes (CORREGIDO: Incluye nombre del remitente)
        $misMensajes = DB::table('mensajes')
            ->join('users', 'mensajes.sender_id', '=', 'users.id')
            ->where('receiver_id', $userId)
            ->select(
                'mensajes.*', 
                'users.name as remitente_nombre', 
                'users.lastname as remitente_apellido'
            )
            ->orderBy('created_at', 'desc')
            ->get();

        // 5. Chat seleccionado
        $usuarioAChatear = null;
        if ($request->has('chat_with')) {
            $usuarioAChatear = DB::table('users')->where('id', $request->chat_with)->first();
        }

        return view('citas.index', compact('citas', 'users', 'misMensajes', 'usuarioAChatear'));
    }

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

    public function responder(Request $request, $id)
    {
        DB::table('citas')->where('id', $id)->update([
            'estado' => $request->estado,
            'responder_id' => Auth::id(),
            'updated_at' => now()
        ]);
        return back();
    }

    public function enviarMensaje(Request $request)
    {
        DB::table('mensajes')->insert([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'mensaje' => $request->mensaje,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return back();
    }

    public function edit($id)
    {
        $cita = DB::table('citas')->where('id', $id)->first();
        if($cita->user_id != Auth::id()) return redirect()->route('citas.index');
        return view('citas.edit', compact('cita'));
    }

    public function update(Request $request, $id)
    {
        DB::table('citas')->where('id', $id)->update([
            'lugar' => $request->lugar, 
            'fecha' => $request->fecha, 
            'hora' => $request->hora, 
            'que_busca' => $request->que_busca,
            'updated_at' => now()
        ]);
        return redirect()->route('citas.index');
    }

    public function destroy($id)
    {
        $cita = DB::table('citas')->where('id', $id)->first();
        if($cita->user_id == Auth::id()) DB::table('citas')->where('id', $id)->delete();
        return back();
    }
}