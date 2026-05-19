<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Schedule;

class ScheduleManager extends Component
{
    public Employee $employee;
    public $day;
    public $start_time;
    public $end_time;

    protected $rules = [
        'day' => 'required|string',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i|after:start_time',
    ];

    protected $messages = [
        'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
    ];

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
        $this->employee->load('user', 'schedules');
    }

    public function addSchedule()
    {
        $this->validate();

        Schedule::create([
            'employee_id' => $this->employee->id,
            'day' => $this->day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);

        $this->reset(['day', 'start_time', 'end_time']);
        $this->employee->load('schedules');
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Horario Agregado',
            'text' => 'El horario se guardó correctamente.'
        ]);
    }

    public function deleteSchedule($scheduleId)
    {
        Schedule::findOrFail($scheduleId)->delete();
        $this->employee->load('schedules');
    }

    public function render()
    {
        return view('livewire.admin.schedule-manager')->layout('layouts.admin', [
            'title' => 'Horarios del Empleado',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
                ['name' => 'Empleados', 'route' => route('admin.employees.index')],
                ['name' => 'Horarios']
            ]
        ]);
    }
}
