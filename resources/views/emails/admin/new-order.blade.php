{{-- resources/views/emails/admin/new-order.blade.php --}}
@php
$primaryColor = '#6366f1';
$dangerColor = '#ef4444';
$successColor = '#22c55e';
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Baru - {{ config('app.name') }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; }
            .mobile-padding { padding: 20px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f5;">
    
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f5;">
        <tr>
            <td align="center" style="padding: 30px 10px;">
                
                <!-- Warning Banner -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; margin-bottom: 15px;">
                    <tr>
                        <td style="background: linear-gradient(135deg, #ef4444, #dc2626); padding: 20px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 700;">
                                PESANAN BARU!
                            </h1>
                            <p style="color: rgba(255,255,255,0.9); margin: 5px 0 0 0; font-size: 14px;">
                                Pembayaran telah diterima
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Main Content -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    
                    <!-- Order Info -->
                    <tr>
                        <td style="padding: 30px 40px;">
                            
                            <p style="color: #334155; font-size: 16px; margin: 0 0 20px 0;">
                                Halo Admin,<br>
                                Ada pesanan baru yang sudah dibayar. Segera proses pesanan ini.
                            </p>

                            <!-- Order Summary -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f1f5f9; border-radius: 8px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding-bottom: 10px;">
                                                    <span style="color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Nomor Pesanan</span><br>
                                                    <strong style="color: {{ $primaryColor }}; font-size: 20px;">#{{ $order->order_number }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 5px;">
                                                    <span style="color: #64748b; font-size: 12px;">Customer</span><br>
                                                    <strong style="color: #334155; font-size: 14px;">{{ $order->user->name }}</strong><br>
                                                    <span style="color: #64748b; font-size: 13px;">{{ $order->user->email }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 5px;">
                                                    <span style="color: #64748b; font-size: 12px;">Tanggal Pesan</span><br>
                                                    <strong style="color: #334155; font-size: 14px;">{{ $order->created_at->format('d M Y, H:i') }}</strong>
                                                </td>
                                                <td style="text-align: right;">
                                                    <span style="color: #64748b; font-size: 12px;">Total</span><br>
                                                    <strong style="color: {{ $successColor }}; font-size: 20px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Order Items -->
                            <h3 style="color: #334155; font-size: 16px; margin: 0 0 15px 0; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
                                Items
                            </h3>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 20px;">
                                <thead>
                                    <tr style="background-color: #f1f5f9;">
                                        <th align="left" style="padding: 12px 10px; color: #334155; font-size: 12px;">Produk</th>
                                        <th align="center" style="padding: 12px 10px; color: #334155; font-size: 12px;">Qty</th>
                                        <th align="right" style="padding: 12px 10px; color: #334155; font-size: 12px;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td style="padding: 12px 10px; color: #334155; font-size: 14px;">
                                            {{ $item->product_name }}
                                        </td>
                                        <td align="center" style="padding: 12px 10px; color: #64748b; font-size: 14px;">
                                            {{ $item->quantity }}
                                        </td>
                                        <td align="right" style="padding: 12px 10px; color: #334155; font-size: 14px;">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Shipping Address -->
                            <h3 style="color: #334155; font-size: 16px; margin: 0 0 15px 0; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">
                                Alamat Pengiriman
                            </h3>
                            
                            <div style="background-color: #fef3c7; padding: 15px 20px; border-radius: 8px; margin-bottom: 25px; border-left: 4px solid #f59e0b;">
                                <p style="margin: 0 0 5px 0; color: #92400e; font-weight: 600;">{{ $order->shipping_name }}</p>
                                <p style="margin: 0 0 5px 0; color: #78350f; font-size: 14px;">{{ $order->shipping_phone }}</p>
                                <p style="margin: 0; color: #78350f; font-size: 14px; line-height: 1.5;">{{ $order->shipping_address }}</p>
                            </div>

                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 20px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('admin.orders.show', $order) }}" style="display: inline-block; background: linear-gradient(135deg, {{ $primaryColor }}, #4f46e5); color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 8px; font-weight: 600; font-size: 14px;">
                                            Lihat & Proses Pesanan
                                        </a>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 20px 40px; text-align: center;">
                            <p style="color: #ffffff; font-size: 14px; font-weight: 600; margin: 0 0 5px 0;">
                                {{ config('app.name') }} Admin Panel
                            </p>
                            <p style="color: #64748b; font-size: 12px; margin: 0;">
                                Pesanan ini perlu diproses secepatnya
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
