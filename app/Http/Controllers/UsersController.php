<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $usuarios = User::all();
        return view('modulos.usuarios')->with('usuarios', $usuarios);
    }

    public function crear()
    {
        $datos = request()->validate([
            'name' => ['string','max:255'],
            'cargo' => ['required'],
            'email' => ['string','unique:users'],
            'password' => ['string','min:3']
        ]);

        User::create([
            'name'=>$datos["name"],
            'email'=>$datos["email"],
            'position'=>$datos["cargo"],
            'password'=>Hash::make($datos["password"]),
        ]);

        return redirect('usuarios')->with('UsuarioCreado','OK');
    }

    public function editar($id)
    {
        $usuarios = User::all();
        $usuario = User::find($id);
        return view('modulos.usuarios', compact('usuarios', 'usuario'));
    }

    public function actualizar($id)
    {
        $usuario = User::find($id);

        if($usuario["email"] != request('email')){
            $datos = request()->validate([
                'name' => ['required'],
                'email' => ['required', 'email', 'unique:users'],
                'cargo' => ['required']
            ]);
        }else{
            $datos = request()->validate([
                'name' => ['required'],
                'email' => ['required', 'email'],
                'cargo' => ['required']
            ]);
        }

        if($usuario["password"] != request('password')){
            $clave = request('password');
        }else{
            $clave = $usuario["password"];
        }

        if($clave == null){
            DB::table('users')->where('id', $usuario["id"])->update(['name' => $datos["name"], 'email' => $datos["email"], 'position' => $datos["cargo"]]);
        }else{
            DB::table('users')->where('id', $usuario["id"])->update(['name' => $datos["name"], 'email' => $datos["email"], 'position' => $datos["cargo"], 'password'=>Hash::make($clave)]);
        }

        return redirect('usuarios');
    }

    public function eliminar($id)
    {
        User::destroy($id);
        return redirect('usuarios')->with('UsuarioEliminado','OK');
    }
}
