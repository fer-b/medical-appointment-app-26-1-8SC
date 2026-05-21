<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Employee;
use App\Models\User;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderCreate extends Component
{
    // Public user fields
    public $name;
    public $email;
    public $phone;

    // Order fields
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

    public $orderCompleted = false;

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'reason' => 'required|string|min:3',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo válido.',
            'phone.required' => 'El teléfono es obligatorio.',
            'reason.required' => 'El detalle del pedido o dirección de entrega es obligatorio.',
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
            $this->addError('beer_selection', 'En este momento no podemos procesar pedidos. Por favor, intenta más tarde.');
            return;
        }

        // Handle Public User -> Client Logic
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            // Create a new User
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => Hash::make(Str::random(16)), // Secure random password
                'id_number' => 'CLIENT-' . strtoupper(Str::random(8)), // Auto-generated ID number for required field
                'address' => 'No especificada', // Default placeholder for required address field
            ]);
            // Assign client role if Spatie permissions are active (fallback handled gracefully)
            if (class_exists(\Spatie\Permission\Models\Role::class)) {
                $user->assignRole('client');
            }
        } else {
            // Optionally update phone if missing
            if (!$user->phone) {
                $user->update(['phone' => $this->phone]);
            }
        }

        // Check if user has a client profile
        $client = Client::firstOrCreate(
            ['user_id' => $user->id],
            ['client_category_id' => 2] // Default to 'Minorista / Particular' if seeded as ID 2
        );

        // Set default prep times for scheduling compatibility and PDF formatting
        $startTime = '12:00:00';
        $endTime = '12:15:00';

        // Create the Order
        Order::create([
            'client_id' => $client->id,
            'employee_id' => $assignedEmployee->id,
            'date' => date('Y-m-d'), // Public orders are assumed for ASAP/Today by default
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => 15,
            'reason' => $this->reason,
            'six_quantity' => $this->orderSix ? $this->sixQty : 0,
            'caguama_quantity' => $this->orderCaguama ? $this->caguamaQty : 0,
            'status' => 1 // Programado / Pendiente
        ]);

        $this->orderCompleted = true;
    }

    public function render()
    {
        return view('livewire.public.order-create')->layout('layouts.guest');
    }
}
