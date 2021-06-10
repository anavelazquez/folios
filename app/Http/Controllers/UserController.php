<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    // mostrar los registros que se guardan en la base de datos
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }
//crear usuarios---------------------------------
public function create()
{
    $tipo = DB::table('trabajador')->get();
    $array =  array(
        'tipo' => $tipo,
       
    );

    return view('users.create', compact('tipo'));
}

public function store(Request $request)
{
    User::create($request->only("username", "password", 'permissions', 'trabajador_id'));
    return redirect() ->route('users.index')->with('success', 'Usuario creado correctamente');
}


// function para mostrar los registros con detalle-------------------------------
    public function show(User $user)
    {
        // $user = User::findOrfail($id);
        // dd($user);
        return view('users.show', compact('user'));
    }

    // funcion para editar
    public function edit(User $user)
    {
        $tipo = DB::table('trabajador')->get();
    $array =  array(
        'tipo' => $tipo
       
    );
        return view('users.edit', compact('user', 'tipo'));
    }
    //function para actualizar
public function update(Request $request, User $user)
{
    
$data = $request ->only("username", "password", 'permissions', 'trabajador_id');
$password=$request->input('password');

$user->update($data);
return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente');
}
public function destroy(User $user)
{
$user->delete();
return back()->with('sucess', 'Usuario eliminado correctmente');
}
}