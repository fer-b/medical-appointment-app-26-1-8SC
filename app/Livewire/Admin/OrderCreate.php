<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Client;
use App\Models\Order;

class OrderCreate extends Component
{
    use \App\Traits\ManagesBeerStock;

    // Basic fields
    public $client_id;
    public $searchDate;
    public $reason;

    // Beer formats selection
    public $orderSix = false;
    public $orderCaguama = false;

    // Quantities
    public $sixQty = 1;
    public $caguamaQty = 1;

    // Mock stock available
    public $stockSix = 45;
    public $stockCaguama = 30;

    public function mount()
    {
        $this->searchDate = date('Y-m-d');
        $this->loadStock();
    }

    public function incrementSix()
    {
        if ($this->sixQty < $this->stockSix) {
            $this->sixQty++;
        }
    }

    public function decrementSix()
    {
        if ($this->sixQty > 1) {
            $this->sixQty--;
        }
    }

    public function incrementCaguama()
    {
        if ($this->caguamaQty < $this->stockCaguama) {
            $this->caguamaQty++;
        }
    }

    public function decrementCaguama()
    {
        if ($this->caguamaQty > 1) {
            $this->caguamaQty--;
        }
    }

    public function confirmOrder()
    {
        $this->loadStock();

        // Custom validations
        $this->validate([
            'client_id' => 'required|exists:clients,id',
            'searchDate' => 'required|date|after_or_equal:today',
            'reason' => 'required|string|min:3',
        ], [
            'client_id.required' => 'Debe seleccionar un cliente.',
            'searchDate.required' => 'La fecha del pedido es obligatoria.',
            'searchDate.after_or_equal' => 'No se pueden registrar pedidos en fechas pasadas.',
            'reason.required' => 'El motivo o detalles de entrega son obligatorios.',
            'reason.min' => 'El detalle del pedido debe tener al menos 3 caracteres.',
        ]);

        if (!$this->orderSix && !$this->orderCaguama) {
            $this->addError('beer_selection', 'Debe seleccionar al menos un formato de cerveza (Six o Caguama) para ordenar.');
            return;
        }

        if ($this->orderSix && ($this->sixQty < 1 || $this->sixQty > $this->stockSix)) {
            $this->addError('sixQty', "La cantidad de Six debe estar entre 1 y {$this->stockSix}.");
            return;
        }

        if ($this->orderCaguama && ($this->caguamaQty < 1 || $this->caguamaQty > $this->stockCaguama)) {
            $this->addError('caguamaQty', "La cantidad de Caguamas debe estar entre 1 y {$this->stockCaguama}.");
            return;
        }

        // Auto-assign to first available employee for fulfillment
        $assignedEmployee = Employee::first();
        if (!$assignedEmployee) {
            $this->addError('beer_selection', 'No hay empleados cerveceros registrados para procesar el pedido.');
            return;
        }

        // Set default prep times for scheduling compatibility
        $startTime = '12:00:00';
        $endTime = '12:15:00';

        Order::create([
            'client_id' => $this->client_id,
            'employee_id' => $assignedEmployee->id,
            'date' => $this->searchDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => 15,
            'reason' => $this->reason,
            'six_quantity' => $this->orderSix ? $this->sixQty : 0,
            'caguama_quantity' => $this->orderCaguama ? $this->caguamaQty : 0,
            'status' => 1 // Programado / Pendiente
        ]);

        $this->loadStock();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Pedido registrado',
            'text' => 'El pedido cervecero se ha guardado correctamente.'
        ]);

        return redirect()->route('admin.orders.index');
    }

    public function render()
    {
        $clients = Client::with('user')->get();
        return view('livewire.admin.order-create', compact('clients'));
    }
}
