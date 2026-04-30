<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;

class LoanPaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function payFine(Loan $loan)
    {
        if ($loan->fine_amount <= 0 || $loan->fine_status === 'paid') {
            return back()->with('error', 'Tidak ada denda yang perlu dilunasi untuk peminjaman ini.');
        }

        if ($loan->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $orderId = 'FINE-' . $loan->id . '-' . time();

        $params = array(
            'transaction_details' => array(
                'order_id' => $orderId,
                'gross_amount' => $loan->fine_amount,
            ),
            'customer_details' => array(
                'first_name' => $loan->user->name,
                'email' => $loan->user->email,
            ),
            'item_details' => array(
                array(
                    'id' => 'FINE-'.$loan->id,
                    'price' => $loan->fine_amount,
                    'quantity' => 1,
                    'name' => 'Denda Terlambat: ' . substr($loan->book->name, 0, 30)
                )
            )
        );

        try {
            $paymentUrl = Snap::createTransaction($params)->redirect_url;
            return redirect($paymentUrl);
        } catch (\Exception $e) {
            Log::error('Midtrans Fine Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function notification(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? '';
        $transactionStatus = $payload['transaction_status'] ?? '';
        
        if (str_starts_with($orderId, 'FINE-')) {
            $parts = explode('-', $orderId);
            $loanId = $parts[1] ?? null;
            
            if ($loanId) {
                $loan = Loan::find($loanId);
                if ($loan) {
                    if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                        $loan->fine_status = 'paid';
                        $loan->save();
                    }
                }
            }
        }
        return response()->json(['success' => true]);
    }
}
