<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Doctor;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user')->latest()->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        // Placeholder
    }

    public function store(Request $request)
    {
        // Placeholder
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        // Placeholder
    }

    public function update(Request $request, string $id)
    {
        // Placeholder
    }

    public function destroy(string $id)
    {
        // Placeholder
    }
}
