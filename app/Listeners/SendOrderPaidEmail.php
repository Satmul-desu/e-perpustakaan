<?php

// app/Listeners/SendOrderPaidEmail.php

namespace App\Listeners;

use App\Events\OrderPaidEvent;
use App\Mail\AdminNewOrderNotification;
use App\Mail\OrderPaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderPaidEmail implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     */
    public int $maxExceptions = 3;

    /**
     * The number of seconds to wait before retrying the job.
     * Exponential backoff: 30s, 60s, 120s
     */
    public int $backoff = 30;

    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): \DateTime
    {
        return now()->addMinutes(10);
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPaidEvent $event): void
    {
        $order = $event->order;

        try {
            // 1. Kirim email ke customer
            Mail::to($order->user->email)
                ->send(new OrderPaid($order));

            Log::info('Order confirmation email sent', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'email' => $order->user->email,
            ]);

            // 2. Kirim notifikasi ke Admin
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

            // Re-throw untuk queue retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(OrderPaidEvent $event, \Throwable $e): void
    {
        Log::error('Order email job permanently failed', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Opsional: Kirim notifikasi ke developer/admin via Slack/Discord
        // Notification::route('slack', config('services.slack.webhook_url'))
        //     ->notify(new JobFailedNotification($e));
    }
}
