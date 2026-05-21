<?php

namespace App\Observers;

use App\Models\Order;
use App\Jobs\ProcessOrderCreated;
use App\Traits\ManagesBeerStock;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    use ManagesBeerStock;

    // Dummy properties required by the loadStock method in the trait (if called)
    // but here we only need getStockData() and updateStockData()
    public $stockSix;
    public $stockCaguama;
    public $sixQty;
    public $caguamaQty;
    public $orderSix;
    public $orderCaguama;

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        Log::info('OrderObserver fired for order ID: ' . $order->id);

        // Synchronously decrement stock from stock.json
        $stock = $this->getStockData();
        $newSix = $stock['six'] - $order->six_quantity;
        $newCaguama = $stock['caguama'] - $order->caguama_quantity;
        $this->updateStockData($newSix, $newCaguama);

        Log::info("Stock decreased. New stock -> Six Pack: {$newSix}, Caguama: {$newCaguama}");

        // Dispatch the job to run in the background (sends email/WhatsApp)
        ProcessOrderCreated::dispatch($order);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        $oldStatus = $order->getOriginal('status');
        $newStatus = $order->status;

        $wasActive = in_array($oldStatus, [1, 2]);
        $isActive = in_array($newStatus, [1, 2]);

        $stock = $this->getStockData();
        $sixStock = $stock['six'];
        $caguamaStock = $stock['caguama'];

        if ($wasActive && !$isActive) {
            // Changed from active to cancelled/inactive -> Restore all stock
            $sixStock += $order->six_quantity;
            $caguamaStock += $order->caguama_quantity;
            Log::info("Order #{$order->id} cancelled. Restored stock -> Six: {$order->six_quantity}, Caguama: {$order->caguama_quantity}");
        } elseif (!$wasActive && $isActive) {
            // Changed from inactive to active -> Decrement all stock
            $sixStock -= $order->six_quantity;
            $caguamaStock -= $order->caguama_quantity;
            Log::info("Order #{$order->id} re-activated. Decreased stock -> Six: {$order->six_quantity}, Caguama: {$order->caguama_quantity}");
        } elseif ($isActive) {
            // Remained active -> Adjust by difference in quantities
            $sixDiff = $order->six_quantity - $order->getOriginal('six_quantity');
            $caguamaDiff = $order->caguama_quantity - $order->getOriginal('caguama_quantity');
            
            if ($sixDiff != 0 || $caguamaDiff != 0) {
                $sixStock -= $sixDiff;
                $caguamaStock -= $caguamaDiff;
                Log::info("Order #{$order->id} quantities updated. Adjusted stock by Six: {$sixDiff}, Caguama: {$caguamaDiff}");
            }
        }

        $this->updateStockData($sixStock, $caguamaStock);
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        // When soft-deleted or force-deleted, restore the stock if the order was active
        if (in_array($order->status, [1, 2])) {
            $stock = $this->getStockData();
            $newSix = $stock['six'] + $order->six_quantity;
            $newCaguama = $stock['caguama'] + $order->caguama_quantity;
            $this->updateStockData($newSix, $newCaguama);

            Log::info("Order #{$order->id} soft-deleted. Restored stock -> Six Pack: {$newSix}, Caguama: {$newCaguama}");
        }
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        // When restored, decrement stock again if active
        if (in_array($order->status, [1, 2])) {
            $stock = $this->getStockData();
            $newSix = $stock['six'] - $order->six_quantity;
            $newCaguama = $stock['caguama'] - $order->caguama_quantity;
            $this->updateStockData($newSix, $newCaguama);

            Log::info("Order #{$order->id} restored. Decreased stock -> Six Pack: {$newSix}, Caguama: {$newCaguama}");
        }
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        // Handled under deleted unless we specifically want separate behavior.
    }
}

