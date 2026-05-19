<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Client;
use App\Models\Employee;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['client.user', 'employee.user'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $clients = Client::with('user')->get();
        $employees = Employee::with('user')->get();
        return view('admin.orders.create', compact('clients', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'nullable|string',
        ]);

        $data = $request->all();
        // Calculate duration in minutes
        $start = \Carbon\Carbon::parse($request->start_time);
        $end = \Carbon\Carbon::parse($request->end_time);
        $data['duration'] = $end->diffInMinutes($start);

        Order::create($data);

        return redirect()->route('admin.orders.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Pedido creado',
            'text' => 'El pedido se ha registrado correctamente.'
        ]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Pedido eliminado',
            'text' => 'El pedido ha sido eliminado.'
        ]);
    }
}
