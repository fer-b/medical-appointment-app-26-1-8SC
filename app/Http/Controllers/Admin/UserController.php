<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9]+$/|unique:users',
            'phone' => 'required|digits_between:7,15',
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::create($data);

        $user->roles()->attach($data['role_id']);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario creado correctamente',
            'text' => 'El usuario ha sido creado correctamente.',
        ]);

        // Si el usuario creado es un cliente, envía el módulo clientes
        if($user->hasRole('client')){
            // Creamos el registro para un cliente
             $client = $user->client()->create([]);
             return redirect()->route('admin.clients.edit', $client);
        }

        return redirect(route('admin.users.index'))->with('success', 'Usuario creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'id_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9]+$/|unique:users,id_number,' . $user->id,
            'phone' => 'required|digits_between:7,15',
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user->update($data);
        //si el usuario quiere editar la contrase;a que lo guarde

        if($request->filled('password')){
           $user->password = bcrypt ($request->password);
              $user->save();
        }



        $user->roles()->sync($data['role_id']);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario actualizado.',
            'text' => 'El usuario ha sido actualizado correctamente.'
        ]);
        return redirect()->route('admin.users.edit', $user->id);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {

        // No permitir que un usuario logeado se elimine a sí mismo
        if ($user->id == Auth::user()->id) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Acción denegada. No puedes eliminarte a ti mismo.',
                'text' => 'Por seguridad, no puedes eliminar tu propia cuenta.'
            ]);
            abort(403, 'No puedes eliminarte a ti mismo.');

            return redirect(route('admin.users.index'));
        }


        //Eliminar roles asociados a un usuario
        $user->roles()->detach();

        //Eliminar usuario
        $user->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario eliminado.',
            'text' => 'El usuario ha sido eliminado correctamente.'
        ]);

        return redirect(route('admin.users.index'));
    }
}
