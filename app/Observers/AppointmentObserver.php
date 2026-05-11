<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Jobs\ProcessAppointmentCreated;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        \Illuminate\Support\Facades\Log::info('AppointmentObserver fired for appointment ID: ' . $appointment->id);
        // Dispatch the job to run in the background
        ProcessAppointmentCreated::dispatch($appointment);
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "deleted" event.
     */
    public function deleted(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "restored" event.
     */
    public function restored(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "force deleted" event.
     */
    public function forceDeleted(Appointment $appointment): void
    {
        //
    }
}
