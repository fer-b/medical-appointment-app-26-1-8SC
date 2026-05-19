<?php

namespace App\Observers;

use App\Models\Order;
use App\Jobs\ProcessOrderCreated;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        \Illuminate\Support\Facades\Log::info('OrderObserver fired for order ID: ' . $order->id);
        // Dispatch the job to run in the background
        ProcessOrderCreated::dispatch($order);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
