<?php

namespace App\Listeners;

use App\Events\OrderPaidEvent;
use App\Mail\AdminNewOrderNotification;
use App\Mail\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOrderPaidEmail implements ShouldQueue
{
    public int $tries = 3;

    public int $maxExceptions = 3;

    public int $backoff = 30;

    public function retryUntil(): \DateTime
    {
        return now()->addMinutes(10);
    }

    public function handle(OrderPaidEvent $event): void
    {
        $order = $event->order;
        try {
            Mail::to($order->user->email)
                ->send(new OrderPaid($order));
            Log::info('Order confirmation email sent', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'email' => $order->user->email,
            ]);
            $adminEmail = config('mail.admin_email', 'admin@tokobuku.com');
            Mail::to($adminEmail)
                ->send(new AdminNewOrderNotification($order));
            Log::info('Admin notification email sent', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send order email', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function failed(OrderPaidEvent $event, \Throwable $e): void
    {
        Log::error('Order email job permanently failed', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
