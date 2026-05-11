<?php

namespace App\Jobs;

use App\Mail\AppointmentCreated;
use App\Models\Appointment;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ProcessAppointmentCreated implements ShouldQueue
{
    use Queueable;

    public $appointment;

    /**
     * Create a new job instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsAppService): void
    {
        // For testing purposes, route everything to personal email/phone if configured in .env
        $testEmail = env('TEST_EMAIL');
        $testPhone = env('TEST_PHONE');

        // 1. Send Email with PDF to Patient
        if ($this->appointment->patient && $this->appointment->patient->user) {
            $emailTo = $testEmail ?: $this->appointment->patient->user->email;
            Mail::to($emailTo)->send(new AppointmentCreated($this->appointment, $this->appointment->patient->user));
        }

        // 2. Send Historical Report PDF to Doctor
        if ($this->appointment->doctor && $this->appointment->doctor->user) {
            $emailTo = $testEmail ?: $this->appointment->doctor->user->email;
            
            // Fetch all upcoming appointments for this doctor (today and future)
            $upcomingAppointments = Appointment::where('doctor_id', $this->appointment->doctor_id)
                ->where('date', '>=', \Carbon\Carbon::today()->toDateString())
                ->with(['patient.user'])
                ->orderBy('date')
                ->orderBy('start_time')
                ->get();

            Mail::to($emailTo)->send(new \App\Mail\DailyDoctorReport($this->appointment->doctor, $upcomingAppointments, true));
        }

        // 3. Send WhatsApp Confirmation to Patient
        if ($this->appointment->patient && $this->appointment->patient->user && $this->appointment->patient->user->phone) {
            $appointmentData = [
                'patient_name' => $this->appointment->patient->user->name,
                'doctor_name' => $this->appointment->doctor->user->name ?? 'Asignado',
                'date' => \Carbon\Carbon::parse($this->appointment->date)->format('d/m/Y'),
                'time' => \Carbon\Carbon::parse($this->appointment->start_time)->format('H:i'),
            ];
            
            $phoneTo = $testPhone ?: $this->appointment->patient->user->phone;
            $whatsAppService->sendConfirmation($phoneTo, $appointmentData);
        } else {
            Log::warning('No phone number found for patient user ID: ' . ($this->appointment->patient->user->id ?? 'N/A') . ' to send WhatsApp confirmation.');
        }
    }
}
