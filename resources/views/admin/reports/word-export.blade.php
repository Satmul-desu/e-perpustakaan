<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 20px;
            color: #000;
        }
        h1 {
            font-size: 18pt;
            text-align: center;
            margin-bottom: 5px;
            color: #1a1a1a;
        }
        .subtitle {
            text-align: center;
            font-size: 12pt;
            color: #666;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 14pt;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
            color: #1a1a1a;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .summary-label {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 40%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .page-break {
            page-break-before: always;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10pt;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>LAPORAN PENJUALAN</h1>
    <p class="subtitle">Periode: {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}</p>

    <h2>1. RINGKASAN</h2>
    <table class="summary-table">
        <tr>
            <td class="summary-label">Total Pendapatan</td>
            <td>Rp {{ number_format($summary->total_revenue ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="summary-label">Total Pesanan</td>
            <td>{{ number_format($summary->total_orders ?? 0) }}</td>
        </tr>
        <tr>
            <td class="summary-label">Pelanggan Unik</td>
            <td>{{ number_format($summary->unique_customers ?? 0) }}</td>
        </tr>
        <tr>
            <td class="summary-label">Rata-rata Nilai Pesanan</td>
            <td>Rp {{ number_format($summary->avg_order_value ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="summary-label">Total Ongkos Kirim</td>
            <td>Rp {{ number_format($summary->total_shipping ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h2>2. PENJUALAN PER KATEGORI</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th class="text-center">Produk Terjual</th>
                <th class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $totalRevenue = $byCategory->sum('total_revenue'); @endphp
            @forelse($byCategory as $category)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $category->category_name }}</td>
                    <td class="text-center">{{ number_format($category->total_sold) }}</td>
                    <td class="text-right">Rp {{ number_format($category->total_revenue, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <h2>3. DAFTAR PESANAN</h2>
    <table>
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Pelanggan</th>
                <th>Items</th>
                <th class="text-right">Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->order_number }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>{{ $order->orderItems->count() }} item</td>
                    <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada pesanan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        <p>Toko Online Raihan</p>
    </div>
</body>
</html>

