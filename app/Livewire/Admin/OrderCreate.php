<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Employee;
use App\Models\Client;
use App\Models\Order;

class OrderCreate extends Component
{
    // Search fields
    public $searchDate;
    public $searchTime;
    public $searchSpecialty;
    
    // Results
    public $availableEmployees = [];
    
    // Selected for Order
    public $selectedEmployee;
    public $selectedTime;
    public $client_id;
    public $reason;
    
    public function mount()
    {
        $this->searchDate = date('Y-m-d');
        $this->availableEmployees = Employee::with('user')->get();
    }
    
    public function searchAvailability()
    {
        $query = Employee::with('user');
        if ($this->searchSpecialty) {
            $query->where('specialty', 'like', '%' . $this->searchSpecialty . '%');
        }
        $this->availableEmployees = $query->get();
        $this->selectedEmployee = null;
        $this->selectedTime = null;
    }
    
    public function selectTimeSlot($employeeId, $time)
    {
        $this->selectedEmployee = Employee::with('user')->find($employeeId);
        $this->selectedTime = $time;
    }
    
    public function confirmAppointment()
    {
        $this->validate([
            'selectedEmployee' => 'required',
            'searchDate' => 'required|date|after_or_equal:today',
            'selectedTime' => 'required',
            'client_id' => 'required|exists:clients,id',
            'reason' => 'required|string',
        ], [
            'selectedEmployee.required' => 'Debe seleccionar un horario disponible.',
            'selectedTime.required' => 'Debe seleccionar un horario disponible.',
            'client_id.required' => 'Debe seleccionar un cliente.',
            'reason.required' => 'El motivo del pedido es obligatorio.',
            'searchDate.after_or_equal' => 'No se pueden registrar pedidos en fechas pasadas.',
        ]);
        
        $startTime = \Carbon\Carbon::parse($this->selectedTime);
        $endTime = $startTime->copy()->addMinutes(15);
        $appointmentDateTime = \Carbon\Carbon::parse($this->searchDate . ' ' . $this->selectedTime);
        
        if ($appointmentDateTime->isPast()) {
            $this->addError('selectedTime', 'No se puede agendar un pedido en una hora pasada.');
            return;
        }
        
        Order::create([
            'client_id' => $this->client_id,
            'employee_id' => $this->selectedEmployee->id,
            'date' => $this->searchDate,
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => $endTime->format('H:i:s'),
            'duration' => 15,
            'reason' => $this->reason,
            'status' => 1
        ]);
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Guardado correctamente',
            'text' => 'El pedido se ha guardado correctamente.'
        ]);
        
        return redirect()->route('admin.orders.index');
    }

    public function render()
    {
        $clients = Client::with('user')->get();
        return view('livewire.admin.order-create', compact('clients'));
    }
}
