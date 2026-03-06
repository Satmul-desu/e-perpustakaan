<?php
namespace App\Http\Controllers;
use App\Services\OrderService;
use Illuminate\Http\Request;
class CheckoutController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart;
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }
        $cart->loadItems();
        return view('checkout.index', compact('cart'));
    }
    public function store(Request $request, OrderService $orderService)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);
        try {
            $order = $orderService->createOrder(auth()->user(), $request->only(['name', 'phone', 'address']));
            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! Silahkan lakukan pembayaran.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}