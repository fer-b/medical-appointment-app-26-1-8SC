<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient.user', 'doctor.user'])->latest()->paginate(10);
        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('admin.appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
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

        Appointment::create($data);

        return redirect()->route('admin.appointments.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Cita creada',
            'text' => 'La cita se ha registrado correctamente.'
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
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('admin.appointments.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Cita eliminada',
            'text' => 'La cita ha sido eliminada.'
        ]);
    }
}
