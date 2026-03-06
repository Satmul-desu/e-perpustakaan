<?php
namespace App\Listeners;
use App\Services\CartService;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
class MergeCartListener
{
    public function __construct()
    {
    }
    public function handle(Login $event): void
    {
        $cartService = new CartService();
        $cartService->mergeCartOnLogin();
    }
}