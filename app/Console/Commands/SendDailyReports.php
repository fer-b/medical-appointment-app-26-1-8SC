<?php

namespace App\Console\Commands;

use App\Mail\DailyAdminReport;
use App\Mail\DailyDoctorReport;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-reports {--all : Incluir todas las citas sin importar la fecha}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends daily appointment reports to admins and doctors.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $testEmail = env('TEST_EMAIL');
        $includeAll = $this->option('all');
        
        $query = Appointment::with(['patient.user', 'doctor.user']);

        if (!$includeAll) {
            $query->where('date', $today);
        }

        $appointments = $query->orderBy('date')->orderBy('start_time')->get();

        // 1. Send Admin Report
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $emailTo = $testEmail ?: $admin->email;
            Mail::to($emailTo)->send(new DailyAdminReport($appointments));
        }
        $this->info("Admin reports sent.");

        // 2. Send Doctor Reports
        $groupedAppointments = $appointments->groupBy('doctor_id');
        
        foreach ($groupedAppointments as $doctorId => $doctorAppointments) {
            // All appointments in this group belong to the same doctor
            $doctor = $doctorAppointments->first()->doctor;
            
            if ($doctor && $doctor->user && $doctor->user->email) {
                // Fetch ALL UPCOMING appointments for this doctor (today and future)
                $allUpcomingAppointments = Appointment::where('doctor_id', $doctorId)
                    ->where('date', '>=', $today)
                    ->with(['patient.user'])
                    ->orderBy('date')
                    ->orderBy('start_time')
                    ->get();

                $emailTo = $testEmail ?: $doctor->user->email;
                Mail::to($emailTo)->send(new DailyDoctorReport($doctor, $allUpcomingAppointments, true));
                $this->info("Full schedule report sent to Doctor ID: {$doctorId} ({$doctor->user->name})");
            }
        }

        $this->info('Daily reports processing completed.');
    }
}
