<?php

namespace App\Listeners;

use App\Services\CartService;
use Illuminate\Auth\Events\Login;

class MergeCartListener
{
    public function __construct() {}

    public function handle(Login $event): void
    {
        $cartService = new CartService;
        $cartService->mergeCartOnLogin();
    }
}
