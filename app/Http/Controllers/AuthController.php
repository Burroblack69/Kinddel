<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Necesario para guardar fotos

class AuthController extends Controller
{
    public function showRegister() { return view('auth.register'); }
    public function showLogin() { return view('auth.login'); }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
            'sexo' => 'required',
            'preferencia' => 'required'
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'sexo' => $request->sexo,
            'preferencia' => $request->preferencia,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('login')->with('success', 'Registro exitoso.');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('citas.index');
        }
        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('home');
    }

    // --- NUEVAS FUNCIONES DE PERFIL ---

    // 1. Mostrar el formulario con los datos actuales
    public function editProfile() {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    // 2. Actualizar los datos y la foto
    public function updateProfile(Request $request) {
        $userId = Auth::id();
        $user = DB::table('users')->where('id', $userId)->first();

        $dataToUpdate = [
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'sexo' => $request->sexo,
            'preferencia' => $request->preferencia,
            'updated_at' => now()
        ];

        // Solo actualizar contraseña si escribió una nueva
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        // Subida de Imagen
        if ($request->hasFile('photo')) {
            // Guardar en storage/app/public/profiles
            $path = $request->file('photo')->store('profiles', 'public');
            $dataToUpdate['profile_photo'] = $path;
        }

        DB::table('users')->where('id', $userId)->update($dataToUpdate);

        return redirect()->route('citas.index')->with('success', 'Perfil actualizado correctamente');
    }
}