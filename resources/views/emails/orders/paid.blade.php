@php
$primaryColor = '#6366f1';
$secondaryColor = '#f1f5f9';
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Diterima - {{ config('app.name') }}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; }
            .mobile-padding { padding: 20px !important; }
            .mobile-stack { display: block !important; width: 100% !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <div style="display: none; max-height: 0; overflow: hidden;">
        Pembayaran pesanan Anda telah kami terima. Pesanan Anda sedang kami proses.
    </div>
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f5;">
        <tr>
            <td align="center" style="padding: 30px 10px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    <tr>
                        <td style="background: linear-gradient(135deg, {{ $primaryColor }}, #8b5cf6); padding: 30px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700;">
                                Pembayaran Diterima ✓
                            </h1>
                            <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 14px;">
                                Pesanan Anda telah dikonfirmasi
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="mobile-padding" style="padding: 30px 40px;">
                            <p style="color: #334155; font-size: 16px; margin: 0 0 20px 0;">
                                Halo <strong>{{ $order->user->name }}</strong>,
                            </p>
                            <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin: 0 0 25px 0;">
                                Selamat! Pembayaran untuk pesanan Anda telah kami terima. Pesanan Anda sekarang sedang kami proses dan akan segera dikirim.
                            </p>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: {{ $secondaryColor }}; border-radius: 8px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding-bottom: 10px;">
                                                    <span style="color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Nomor Pesanan</span><br>
                                                    <strong style="color: {{ $primaryColor }}; font-size: 18px;">#{{ $order->order_number }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 5px;">
                                                    <span style="color: #64748b; font-size: 12px;">Tanggal Pesanan</span><br>
                                                    <strong style="color: #334155; font-size: 14px;">{{ $order->created_at->format('d M Y, H:i') }}</strong>
                                                </td>
                                                <td style="padding-bottom: 5px; text-align: right;">
                                                    <span style="color: #64748b; font-size: 12px;">Total Pembayaran</span><br>
                                                    <strong style="color: #22c55e; font-size: 18px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <h3 style="color: #334155; font-size: 16px; margin: 0 0 15px 0; border-bottom: 2px solid {{ $secondaryColor }}; padding-bottom: 10px;">
                                Rincian Pesanan
                            </h3>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 20px;">
                                <thead>
                                    <tr style="background-color: {{ $secondaryColor }};">
                                        <th align="left" style="padding: 12px 10px; color: #334155; font-size: 12px; text-transform: uppercase;">Produk</th>
                                        <th align="center" style="padding: 12px 10px; color: #334155; font-size: 12px; text-transform: uppercase;">Qty</th>
                                        <th align="right" style="padding: 12px 10px; color: #334155; font-size: 12px; text-transform: uppercase;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr style="border-bottom: 1px solid {{ $secondaryColor }};">
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
                                <tfoot>
                                    <tr>
                                        <td colspan="2" align="right" style="padding: 10px 10px; color: #64748b; font-size: 13px;">Subtotal</td>
                                        <td align="right" style="padding: 10px 10px; color: #334155; font-size: 14px;">Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right" style="padding: 10px 10px; color: #64748b; font-size: 13px;">Ongkos Kirim</td>
                                        <td align="right" style="padding: 10px 10px; color: #334155; font-size: 14px;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr style="background-color: {{ $secondaryColor }};">
                                        <td colspan="2" align="right" style="padding: 12px 10px; color: #334155; font-size: 14px; font-weight: 600;">TOTAL</td>
                                        <td align="right" style="padding: 12px 10px; color: {{ $primaryColor }}; font-size: 16px; font-weight: 700;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <h3 style="color: #334155; font-size: 16px; margin: 0 0 15px 0; border-bottom: 2px solid {{ $secondaryColor }}; padding-bottom: 10px;">
                                Alamat Pengiriman
                            </h3>
                            <div style="background-color: {{ $secondaryColor }}; padding: 15px 20px; border-radius: 8px; margin-bottom: 25px;">
                                <p style="margin: 0 0 5px 0; color: #334155; font-weight: 600;">{{ $order->shipping_name }}</p>
                                <p style="margin: 0 0 5px 0; color: #64748b; font-size: 14px;">{{ $order->shipping_phone }}</p>
                                <p style="margin: 0; color: #64748b; font-size: 14px; line-height: 1.5;">{{ $order->shipping_address }}</p>
                            </div>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 25px;">
                                <tr>
                                    <td align="center">
                                        <span style="display: inline-block; background-color: #dcfce7; color: #166534; padding: 10px 20px; border-radius: 20px; font-weight: 600; font-size: 14px;">
                                            ✓ Pesanan Sedang Diproses
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 25px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ route('orders.show', $order) }}" style="display: inline-block; background: linear-gradient(135deg, {{ $primaryColor }}, #4f46e5); color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 8px; font-weight: 600; font-size: 14px;">
                                            Lihat Detail Pesanan
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin: 0;">
                                Anda akan menerima nomor resi pengiriman melalui email terpisah begitu pesanan dikirim.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #1e293b; padding: 25px 40px; text-align: center;">
                            <p style="color: #ffffff; font-size: 16px; font-weight: 600; margin: 0 0 10px 0;">
                                {{ config('app.name') }}
                            </p>
                            <p style="color: #94a3b8; font-size: 13px; margin: 0 0 15px 0;">
                                Terima kasih telah berbelanja bersama kami
                            </p>
                            <p style="color: #64748b; font-size: 12px; margin: 0;">
                                Jika ada pertanyaan, silakan reply email ini atau hubungi customer service kami.
                            </p>
                        </td>
                    </tr>
                </table>
                <p style="color: #94a3b8; font-size: 12px; margin-top: 20px;">
                    Email ini dikirim ke <a href="mailto:{{ $order->user->email }}" style="color: {{ $primaryColor }};">{{ $order->user->email }}</a>
                </p>
            </td>
        </tr>
    </table>
</body>
</html>