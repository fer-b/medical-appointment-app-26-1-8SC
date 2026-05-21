<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderCreate extends Component
{
    // Form fields
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
        // Custom validations
        $this->validate([
            'searchDate' => 'required|date|after_or_equal:today',
            'reason' => 'required|string|min:3',
        ], [
            'searchDate.required' => 'La fecha del pedido es obligatoria.',
            'searchDate.after_or_equal' => 'No se pueden registrar pedidos en fechas pasadas.',
            'reason.required' => 'Las notas o dirección de entrega son obligatorias.',
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

        // Auto-resolve or create client profile for the authenticated user
        $user = Auth::user();
        $client = $user->client;

        if (!$client) {
            $client = Client::create([
                'user_id' => $user->id,
                'client_category_id' => \App\Models\ClientCategory::first()->id ?? 1,
                'shipping_preference' => 'Domicilio',
                'business_notes' => 'Perfil de cliente generado automáticamente'
            ]);
        }

        // Auto-assign to first employee
        $assignedEmployee = Employee::first();
        if (!$assignedEmployee) {
            $this->addError('beer_selection', 'Disculpas, no hay maestros cerveceros disponibles en este momento.');
            return;
        }

        // Default compatible timeslots
        $startTime = '12:00:00';
        $endTime = '12:15:00';

        Order::create([
            'client_id' => $client->id,
            'employee_id' => $assignedEmployee->id,
            'date' => $this->searchDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => 15,
            'reason' => $this->reason,
            'six_quantity' => $this->orderSix ? $this->sixQty : 0,
            'caguama_quantity' => $this->orderCaguama ? $this->caguamaQty : 0,
            'status' => 1 // Programado
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Pedido Recibido! 🍻',
            'text' => 'Hemos registrado tu pedido correctamente. ¡Salud!'
        ]);

        // Clear input fields
        $this->orderSix = false;
        $this->orderCaguama = false;
        $this->sixQty = 1;
        $this->caguamaQty = 1;
        $this->reason = '';
    }

    public function render()
    {
        // Fetch recent orders for the logged-in client
        $user = Auth::user();
        $orders = [];
        if ($user && $user->client) {
            $orders = Order::where('client_id', $user->client->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('livewire.client.order-create', [
            'recentOrders' => $orders
        ])->layout('layouts.app'); // Uses the standard Jetstream dynamic app layout!
    }
}
