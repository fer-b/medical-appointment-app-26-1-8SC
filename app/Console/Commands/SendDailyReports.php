<?php

namespace App\Console\Commands;

use App\Mail\DailyAdminReport;
use App\Mail\DailyEmployeeReport;
use App\Models\Order;
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
    protected $signature = 'app:send-daily-reports {--all : Incluir todos los pedidos sin importar la fecha}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends daily order reports to admins and employees.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $testEmail = env('TEST_EMAIL');
        $includeAll = $this->option('all');
        
        $query = Order::with(['client.user', 'employee.user']);

        if (!$includeAll) {
            $query->where('date', $today);
        }

        $orders = $query->orderBy('date')->orderBy('start_time')->get();

        // 1. Send Admin Report
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            $emailTo = $testEmail ?: $admin->email;
            Mail::to($emailTo)->send(new DailyAdminReport($orders));
        }
        $this->info("Admin reports sent.");

        // 2. Send Employee Reports
        $groupedOrders = $orders->groupBy('employee_id');
        
        foreach ($groupedOrders as $employeeId => $employeeOrders) {
            // All orders in this group belong to the same employee
            $employee = $employeeOrders->first()->employee;
            
            if ($employee && $employee->user && $employee->user->email) {
                // Fetch ALL UPCOMING orders for this employee (today and future)
                $allUpcomingOrders = Order::where('employee_id', $employeeId)
                    ->where('date', '>=', $today)
                    ->with(['client.user'])
                    ->orderBy('date')
                    ->orderBy('start_time')
                    ->get();

                $emailTo = $testEmail ?: $employee->user->email;
                Mail::to($emailTo)->send(new DailyEmployeeReport($employee, $allUpcomingOrders, true));
                $this->info("Full schedule report sent to Employee ID: {$employeeId} ({$employee->user->name})");
            }
        }

        $this->info('Daily reports processing completed.');
    }
}
