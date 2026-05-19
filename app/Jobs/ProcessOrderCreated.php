<?php

namespace App\Jobs;

use App\Mail\OrderCreated;
use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ProcessOrderCreated implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsAppService): void
    {
        // For testing purposes, route everything to personal email/phone if configured in .env
        $testEmail = env('TEST_EMAIL');
        $testPhone = env('TEST_PHONE');

        // 1. Send Email with PDF to Client
        if ($this->order->client && $this->order->client->user) {
            $emailTo = $testEmail ?: $this->order->client->user->email;
            Mail::to($emailTo)->send(new OrderCreated($this->order, $this->order->client->user));
        }

        // 2. Send Historical Report PDF to Employee
        if ($this->order->employee && $this->order->employee->user) {
            $emailTo = $testEmail ?: $this->order->employee->user->email;
            
            // Fetch all upcoming orders for this employee (today and future)
            $upcomingOrders = Order::where('employee_id', $this->order->employee_id)
                ->where('date', '>=', \Carbon\Carbon::today()->toDateString())
                ->with(['client.user'])
                ->orderBy('date')
                ->orderBy('start_time')
                ->get();

            Mail::to($emailTo)->send(new \App\Mail\DailyEmployeeReport($this->order->employee, $upcomingOrders, true));
        }

        // 3. Send WhatsApp Confirmation to Client
        if ($this->order->client && $this->order->client->user && $this->order->client->user->phone) {
            $orderData = [
                'client_name' => $this->order->client->user->name,
                'employee_name' => $this->order->employee->user->name ?? 'Asignado',
                'date' => \Carbon\Carbon::parse($this->order->date)->format('d/m/Y'),
                'time' => \Carbon\Carbon::parse($this->order->start_time)->format('H:i'),
            ];
            
            $phoneTo = $testPhone ?: $this->order->client->user->phone;
            $whatsAppService->sendConfirmation($phoneTo, $orderData);
        } else {
            Log::warning('No phone number found for client user ID: ' . ($this->order->client->user->id ?? 'N/A') . ' to send WhatsApp confirmation.');
        }
    }
}
