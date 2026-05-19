<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\BloodType;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.clients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {   
        $bloodTypes = BloodType::all();
        return view('admin.clients.edit', compact('client', 'bloodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'allergies' => 'nullable|string|min:3|max:255',
            'chronic_conditions' => 'nullable|string|min:3|max:255',
            'surgical_history' => 'nullable|string|min:3|max:255',
            'family_history' => 'nullable|string|min:3|max:255',
            'observations' => 'nullable|string|min:3|max:255',
            'emergency_contact_name' => 'nullable|string|min:3|max:255',
            'emergency_contact_phone' => ['nullable','string','max:12', 'min:10'],
            'emergency_contact_relationship' => 'nullable|string|min:3|max:50',
        ]);
        $client->update($data);

        session()->flash('swal',[
            'icon' => 'success',
            'title' => 'Cliente actualizado',
            'text' => 'Cliente actualizado correctamente.',
        ]);

        return redirect()->route('admin.clients.edit', $client)->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}