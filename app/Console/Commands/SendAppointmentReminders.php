<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-appointment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends WhatsApp reminders for appointments scheduled for tomorrow.';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsAppService)
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $testPhone = env('TEST_PHONE');
        
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('date', $tomorrow)
            ->get();

        $this->info("Found {$appointments->count()} appointments for tomorrow.");

        foreach ($appointments as $appointment) {
            if ($appointment->patient && $appointment->patient->user && $appointment->patient->user->phone) {
                $appointmentData = [
                    'patient_name' => $appointment->patient->user->name,
                    'doctor_name' => $appointment->doctor->user->name ?? 'Asignado',
                    'time' => Carbon::parse($appointment->start_time)->format('H:i'),
                ];
                
                $phoneTo = $testPhone ?: $appointment->patient->user->phone;
                $whatsAppService->sendReminder($phoneTo, $appointmentData);
                $this->info("Reminder sent to {$phoneTo}");
            } else {
                Log::warning("No phone number found for appointment ID: {$appointment->id} to send reminder.");
            }
        }

        $this->info('Reminders sent successfully.');
    }
}
