<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendOrderReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-order-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends WhatsApp reminders for orders scheduled for tomorrow.';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsAppService)
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $testPhone = env('TEST_PHONE');
        
        $orders = Order::with(['client.user', 'employee.user'])
            ->where('date', $tomorrow)
            ->get();

        $this->info("Found {$orders->count()} orders for tomorrow.");

        foreach ($orders as $order) {
            if ($order->client && $order->client->user && $order->client->user->phone) {
                $orderData = [
                    'client_name' => $order->client->user->name,
                    'employee_name' => $order->employee->user->name ?? 'Asignado',
                    'time' => Carbon::parse($order->start_time)->format('H:i'),
                ];
                
                $phoneTo = $testPhone ?: $order->client->user->phone;
                $whatsAppService->sendReminder($phoneTo, $orderData);
                $this->info("Reminder sent to {$phoneTo}");
            } else {
                Log::warning("No phone number found for order ID: {$order->id} to send reminder.");
            }
        }

        $this->info('Reminders sent successfully.');
    }
}
