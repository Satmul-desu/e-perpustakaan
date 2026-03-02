<?php
// app/Exports/SalesReportExport.php

namespace App\Exports;

use App\Models\Order;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;

class SalesReportExport implements WithStyles, WithTitle
{
    protected string $dateFrom;
    protected string $dateTo;

    public function __construct(string $dateFrom, string $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    /**
     * Return data for the Excel file
     */
    public function collection()
    {
        return Order::with(['user', 'orderItems.product'])
            ->whereDate('created_at', '>=', $this->dateFrom)
            ->whereDate('created_at', '<=', $this->dateTo)
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($order) {
                return [
                    'order_number' => $order->order_number,
                    'date' => $order->created_at->format('d/m/Y H:i'),
                    'customer_name' => $order->user->name ?? 'N/A',
                    'customer_email' => $order->user->email ?? 'N/A',
                    'items_count' => $order->orderItems->count(),
                    'total_quantity' => $order->orderItems->sum('quantity'),
                    'subtotal' => $order->total_amount - $order->shipping_cost,
                    'shipping_cost' => $order->shipping_cost,
                    'total_amount' => $order->total_amount,
                    'status' => ucfirst($order->status),
                ];
            });
    }

    /**
     * Sheet title
     */
    public function title(): string
    {
        return 'Detail Pesanan';
    }

    /**
     * Headings for the main sheet
     */
    public function headings(): array
    {
        return [
            'No. Order',
            'Tanggal',
            'Nama Customer',
            'Email',
            'Jumlah Item',
            'TotalQty',
            'Subtotal',
            'Ongkir',
            'Total',
            'Status',
        ];
    }

    /**
     * Map data to cells
     */
    public function map($order): array
    {
        return [
            $order['order_number'],
            $order['date'],
            $order['customer_name'],
            $order['customer_email'],
            $order['items_count'],
            $order['total_quantity'],
            $order['subtotal'],
            $order['shipping_cost'],
            $order['total_amount'],
            $order['status'],
        ];
    }

    /**
     * Apply styles to the spreadsheet
     */
    public function styles(Worksheet $sheet)
    {
        // Get data for summary calculations
        $orders = $this->collection();
        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $totalShipping = $orders->sum('shipping_cost');
        $avgOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Style configuration
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '6366f1'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ];

        $numberStyle = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ];

        // Get last row
        $lastRow = $sheet->getHighestRow() + 2;

        // Add summary section
        $sheet->mergeCells('A' . ($lastRow) . ':B' . ($lastRow));
        $sheet->setCellValue('A' . $lastRow, 'RINGKASAN LAPORAN');
        $sheet->getStyle('A' . $lastRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $summaryRow = $lastRow + 1;
        $sheet->setCellValue('A' . $summaryRow, 'Total Pesanan');
        $sheet->setCellValue('B' . $summaryRow, $totalOrders);
        $sheet->setCellValue('A' . ($summaryRow + 1), 'Total Pendapatan');
        $sheet->setCellValue('B' . ($summaryRow + 1), '=SUM(I2:I' . ($lastRow - 2) . ')');
        $sheet->setCellValue('A' . ($summaryRow + 2), 'Total Ongkir');
        $sheet->setCellValue('B' . ($summaryRow + 2), '=SUM(H2:H' . ($lastRow - 2) . ')');
        $sheet->setCellValue('A' . ($summaryRow + 3), 'Rata-rata Pesanan');
        $sheet->setCellValue('B' . ($summaryRow + 3), '=B' . ($summaryRow + 1) . '/' . $totalOrders);

        // Format currency cells
        $sheet->getStyle('G' . ($summaryRow + 1) . ':B' . ($summaryRow + 3))
            ->getNumberFormat()
            ->setFormatCode('#,##0');

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(12);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);

        return [
            1 => $headerStyle,
        ];
    }

    /**
     * Create the spreadsheet with multiple sheets
     */
    public function spreadsheet(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();

        // Sheet 1: Orders Detail
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Detail Pesanan');

        // Headers
        $headers = $this->headings();
        foreach ($headers as $index => $header) {
            $cell = chr(65 + $index) . '1';
            $sheet1->setCellValue($cell, $header);
        }

        // Data
        $orders = $this->collection();
        $row = 2;
        foreach ($orders as $order) {
            $sheet1->setCellValue('A' . $row, $order['order_number']);
            $sheet1->setCellValue('B' . $row, $order['date']);
            $sheet1->setCellValue('C' . $row, $order['customer_name']);
            $sheet1->setCellValue('D' . $row, $order['customer_email']);
            $sheet1->setCellValue('E' . $row, $order['items_count']);
            $sheet1->setCellValue('F' . $row, $order['total_quantity']);
            $sheet1->setCellValue('G' . $row, $order['subtotal']);
            $sheet1->setCellValue('H' . $row, $order['shipping_cost']);
            $sheet1->setCellValue('I' . $row, $order['total_amount']);
            $sheet1->setCellValue('J' . $row, $order['status']);
            $row++;
        }

        // Apply styles to sheet 1
        $this->applyHeaderStyles($sheet1);
        $this->applyCurrencyFormat($sheet1, 'G', 2, $row - 1);
        $this->applyCurrencyFormat($sheet1, 'H', 2, $row - 1);
        $this->applyCurrencyFormat($sheet1, 'I', 2, $row - 1);

        // Sheet 2: Category Summary
        $this->createCategorySheet($spreadsheet);

        // Sheet 3: Daily Summary
        $this->createDailySheet($spreadsheet);

        return $spreadsheet;
    }

    /**
     * Apply header styles
     */
    private function applyHeaderStyles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '6366f1'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);
    }

    /**
     * Apply currency format to a column range
     */
    private function applyCurrencyFormat(Worksheet $sheet, $column, $startRow, $endRow)
    {
        $sheet->getStyle($column . $startRow . ':' . $column . $endRow)
            ->getNumberFormat()
            ->setFormatCode('#,##0');
    }

    /**
     * Create category summary sheet
     */
    private function createCategorySheet(Spreadsheet $spreadsheet)
    {
        $sheet = $spreadsheet->createSheet(1);
        $sheet->setTitle('Per Kategori');

        $categories = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->whereDate('orders.created_at', '>=', $this->dateFrom)
            ->whereDate('orders.created_at', '<=', $this->dateTo)
            ->where('orders.payment_status', 'paid')
            ->groupBy('categories.id', 'categories.name')
            ->select([
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
            ])
            ->orderByDesc('total_revenue')
            ->get();

        // Headers
        $sheet->setCellValue('A1', 'Kategori');
        $sheet->setCellValue('B1', 'Total Terjual');
        $sheet->setCellValue('C1', 'Total Pendapatan');

        $this->applyHeaderStyles($sheet);

        // Data
        $row = 2;
        $totalRevenue = $categories->sum('total_revenue');
        foreach ($categories as $category) {
            $sheet->setCellValue('A' . $row, $category->category_name);
            $sheet->setCellValue('B' . $row, $category->total_sold);
            $sheet->setCellValue('C' . $row, $category->total_revenue);
            $row++;
        }

        // Total row
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('B' . $row, '=' . 'SUM(B2:B' . ($row - 1) . ')');
        $sheet->setCellValue('C' . $row, '=' . 'SUM(C2:C' . ($row - 1) . ')');

        // Format
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(20);
        $this->applyCurrencyFormat($sheet, 'C', 2, $row);
    }

    /**
     * Create daily summary sheet
     */
    private function createDailySheet(Spreadsheet $spreadsheet)
    {
        $sheet = $spreadsheet->createSheet(2);
        $sheet->setTitle('Harian');

        $daily = Order::whereDate('created_at', '>=', $this->dateFrom)
            ->whereDate('created_at', '<=', $this->dateTo)
            ->where('payment_status', 'paid')
            ->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as revenue')
            ])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Headers
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Jumlah Pesanan');
        $sheet->setCellValue('C1', 'Pendapatan');

        $this->applyHeaderStyles($sheet);

        // Data
        $row = 2;
        foreach ($daily as $day) {
            $sheet->setCellValue('A' . $row, \Carbon\Carbon::parse($day->date)->format('d/m/Y'));
            $sheet->setCellValue('B' . $row, $day->orders);
            $sheet->setCellValue('C' . $row, $day->revenue);
            $row++;
        }

        // Total row
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('B' . $row, '=' . 'SUM(B2:B' . ($row - 1) . ')');
        $sheet->setCellValue('C' . $row, '=' . 'SUM(C2:C' . ($row - 1) . ')');

        // Format
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(20);
        $this->applyCurrencyFormat($sheet, 'C', 2, $row);
    }
}

