<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;

class AppointmentCreate extends Component
{
    // Search fields
    public $searchDate;
    public $searchTime;
    public $searchSpecialty;
    
    // Results
    public $availableDoctors = [];
    
    // Selected for Appointment
    public $selectedDoctor;
    public $selectedTime;
    public $patient_id;
    public $reason;
    
    public function mount()
    {
        $this->searchDate = date('Y-m-d');
        $this->availableDoctors = Doctor::with('user')->get();
    }
    
    public function searchAvailability()
    {
        $query = Doctor::with('user');
        if ($this->searchSpecialty) {
            $query->where('specialty', 'like', '%' . $this->searchSpecialty . '%');
        }
        $this->availableDoctors = $query->get();
        $this->selectedDoctor = null;
        $this->selectedTime = null;
    }
    
    public function selectTimeSlot($doctorId, $time)
    {
        $this->selectedDoctor = Doctor::with('user')->find($doctorId);
        $this->selectedTime = $time;
    }
    
    public function confirmAppointment()
    {
        $this->validate([
            'selectedDoctor' => 'required',
            'searchDate' => 'required|date|after_or_equal:today',
            'selectedTime' => 'required',
            'patient_id' => 'required|exists:patients,id',
            'reason' => 'required|string',
        ], [
            'selectedDoctor.required' => 'Debe seleccionar un horario disponible.',
            'selectedTime.required' => 'Debe seleccionar un horario disponible.',
            'patient_id.required' => 'Debe seleccionar un paciente.',
            'reason.required' => 'El motivo de la cita es obligatorio.',
            'searchDate.after_or_equal' => 'No se pueden registrar citas en fechas pasadas.',
        ]);
        
        $startTime = \Carbon\Carbon::parse($this->selectedTime);
        $endTime = $startTime->copy()->addMinutes(15);
        $appointmentDateTime = \Carbon\Carbon::parse($this->searchDate . ' ' . $this->selectedTime);
        
        if ($appointmentDateTime->isPast()) {
            $this->addError('selectedTime', 'No se puede agendar una cita en una hora pasada.');
            return;
        }
        
        Appointment::create([
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->selectedDoctor->id,
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
            'text' => 'La cita se ha guardado correctamente.'
        ]);
        
        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        $patients = Patient::with('user')->get();
        return view('livewire.admin.appointment-create', compact('patients'));
    }
}
