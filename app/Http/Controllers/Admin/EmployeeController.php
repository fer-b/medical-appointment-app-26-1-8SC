<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')->latest()->paginate(10);
        return view('admin.employees.index', compact('employees'));
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
