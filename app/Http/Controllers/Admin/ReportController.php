<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $year  = $request->get('year',  now()->year);
        $month = $request->get('month', null);

        // Revenue per bulan (untuk chart)
        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->whereIn('status', ['confirmed','processing','shipped','delivered'])
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get()
            ->keyBy('month');

        // Query orders untuk tabel
        $query = Order::with(['user', 'items'])
            ->whereIn('status', ['confirmed','processing','shipped','delivered']);

        $query->whereYear('created_at', $year);
        if ($month) $query->whereMonth('created_at', $month);

        $orders = $query->latest()->paginate(20)->withQueryString();

        // Summary
            $baseQuery = Order::whereIn('status', ['confirmed','processing','shipped','delivered'])
                ->whereYear('created_at', $year);

            if ($month) $baseQuery->whereMonth('created_at', $month);

            $summary = [
                'total_revenue'  => (clone $baseQuery)->sum('total'),
                'total_tax'      => (clone $baseQuery)->sum('tax'),
                'total_subtotal' => (clone $baseQuery)->sum('subtotal'),
                'total_orders'   => (clone $baseQuery)->count(),
                'avg_order'      => (clone $baseQuery)->avg('total'),
                'total_items'    => OrderItem::whereHas('order', function($q) use ($year, $month) {
                    $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                    ->whereYear('created_at', $year);
                    if ($month) $q->whereMonth('created_at', $month);
                })->sum('quantity'),
            ];

            $orders = (clone $baseQuery)->with(['user','items'])->latest()->paginate(20)->withQueryString();

        // Top produk terlaris
        $topProducts = OrderItem::select('product_id', 'product_name', 'product_sku',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('order', function($q) use ($year, $month) {
                $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                  ->whereYear('created_at', $year);
                if ($month) $q->whereMonth('created_at', $month);
            })
            ->groupBy('product_id', 'product_name', 'product_sku')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // Revenue per brand
        $brandRevenue = OrderItem::select(
                DB::raw('products.brand_id'),
                DB::raw('brands.name as brand_name'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('SUM(order_items.quantity) as total_qty'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->whereHas('order', function($q) use ($year, $month) {
                $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                  ->whereYear('created_at', $year);
                if ($month) $q->whereMonth('created_at', $month);
            })
            ->groupBy('products.brand_id', 'brands.name')
            ->orderByDesc('total_revenue')
            ->get();

        $years = range(now()->year, 2024);
        $profitData = \App\Models\OrderItem::select(
                    \DB::raw('SUM(order_items.subtotal) as total_revenue'),
                    \DB::raw('SUM(order_items.quantity * products.cost_price) as total_cost'),
                    \DB::raw('SUM(order_items.subtotal - (order_items.quantity * products.cost_price)) as total_profit')
                )
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereHas('order', function($q) use ($year, $month) {
                    $q->whereIn('status', ['confirmed','processing','shipped','delivered'])
                    ->whereYear('created_at', $year);
                    if ($month) $q->whereMonth('created_at', $month);
                })
                ->first();

            return view('admin.reports.sales', compact(
                'orders', 'summary', 'monthlyRevenue',
                'topProducts', 'brandRevenue', 'years',
                'year', 'month', 'profitData'   // ← tambah profitData
        ));
    }

    public function exportSales(Request $request)
{
    $year  = $request->get('year', now()->year);
    $month = $request->get('month');

    $query = Order::whereIn('status', ['confirmed','processing','shipped','delivered'])
        ->whereYear('created_at', $year)
        ->with(['user', 'items.product']);

    if ($month) $query->whereMonth('created_at', $month);

    $orders = $query->latest()->get();

    // Hitung summary
    $totalRevenue = $orders->sum('total');
    $totalTax     = $orders->sum('tax');
    $totalItems   = $orders->sum(fn($o) => $o->items->sum('quantity'));

    // Buat spreadsheet
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Laporan Penjualan');

    // ===== STYLING =====
    $headerStyle = [
        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
        'fill'      => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        'borders'   => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
    ];

    $titleStyle = [
        'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1E3A8A']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    ];

    $summaryLabelStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => '374151']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
    ];

    $evenRowStyle = [
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F9FAFB']],
    ];

    $totalStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => '1E3A8A']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DBEAFE']],
        'borders' => ['top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 'color' => ['rgb' => '1E3A8A']]],
    ];

    // ===== HEADER PERUSAHAAN =====
    $sheet->mergeCells('A1:H1');
    $sheet->setCellValue('A1', 'PT CENTRAL BEARINDO INTERNATIONAL');
    $sheet->getStyle('A1')->applyFromArray($titleStyle);
    $sheet->getRowDimension(1)->setRowHeight(25);

    $sheet->mergeCells('A2:H2');
    $sheet->setCellValue('A2', 'LAPORAN PENJUALAN — '.($month ? \Carbon\Carbon::create()->month($month)->format('F').' ' : '').$year);
    $sheet->getStyle('A2')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '6B7280']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    ]);

    $sheet->mergeCells('A3:H3');
    $sheet->setCellValue('A3', 'Dicetak pada: '.now()->isoFormat('dddd, D MMMM Y · HH:mm'));
    $sheet->getStyle('A3')->applyFromArray([
        'font'      => ['size' => 9, 'color' => ['rgb' => '9CA3AF'], 'italic' => true],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    ]);

    // ===== SUMMARY =====
    $sheet->setCellValue('A5', 'RINGKASAN');
    $sheet->mergeCells('A5:H5');
    $sheet->getStyle('A5')->applyFromArray([
        'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '1E3A8A']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DBEAFE']],
    ]);

    $summaryData = [
        ['Total Pesanan',   $orders->count().' pesanan'],
        ['Total Revenue',   'Rp '.number_format($totalRevenue, 0, ',', '.')],
        ['Total PPN (11%)', 'Rp '.number_format($totalTax, 0, ',', '.')],
        ['Total Item Terjual', number_format($totalItems).' pcs'],
    ];

    foreach ($summaryData as $i => $row) {
        $r = 6 + $i;
        $sheet->setCellValue('A'.$r, $row[0]);
        $sheet->setCellValue('B'.$r, $row[1]);
        $sheet->mergeCells('B'.$r.':D'.$r);
        $sheet->getStyle('A'.$r)->applyFromArray($summaryLabelStyle);
        $sheet->getStyle('B'.$r)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '059669']],
        ]);
    }

    // ===== TABEL HEADER =====
    $startRow = 12;
    $headers  = ['No.', 'No. Pesanan', 'Tanggal', 'Pelanggan', 'Email', 'Perusahaan', 'Total (Rp)', 'Jumlah Item'];
    $cols     = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

    foreach ($headers as $i => $header) {
        $sheet->setCellValue($cols[$i].$startRow, $header);
    }

    $sheet->getStyle('A'.$startRow.':H'.$startRow)->applyFromArray($headerStyle);
    $sheet->getRowDimension($startRow)->setRowHeight(22);

    // ===== DATA ROWS =====
    foreach ($orders as $i => $order) {
        $row = $startRow + 1 + $i;
        $sheet->setCellValue('A'.$row, $i + 1);
        $sheet->setCellValue('B'.$row, $order->order_number);
        $sheet->setCellValue('C'.$row, $order->created_at->format('d/m/Y'));
        $sheet->setCellValue('D'.$row, $order->user->name);
        $sheet->setCellValue('E'.$row, $order->user->email);
        $sheet->setCellValue('F'.$row, $order->user->company_name ?? '-');
        $sheet->setCellValue('G'.$row, $order->total);
        $sheet->setCellValue('H'.$row, $order->items->sum('quantity'));

        // Format angka
        $sheet->getStyle('G'.$row)->getNumberFormat()
            ->setFormatCode('#,##0');

        // Alternating row color
        if ($i % 2 === 0) {
            $sheet->getStyle('A'.$row.':H'.$row)->applyFromArray($evenRowStyle);
        }

        // Border tipis
        $sheet->getStyle('A'.$row.':H'.$row)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_HAIR, 'color' => ['rgb' => 'E5E7EB']]],
        ]);

        $sheet->getRowDimension($row)->setRowHeight(18);
    }

    // ===== TOTAL ROW =====
    $totalRow = $startRow + 1 + $orders->count();
    $sheet->setCellValue('A'.$totalRow, '');
    $sheet->setCellValue('F'.$totalRow, 'TOTAL');
    $sheet->setCellValue('G'.$totalRow, $totalRevenue);
    $sheet->setCellValue('H'.$totalRow, $totalItems);
    $sheet->mergeCells('A'.$totalRow.':F'.$totalRow);
    $sheet->getStyle('A'.$totalRow.':H'.$totalRow)->applyFromArray($totalStyle);
    $sheet->getStyle('G'.$totalRow)->getNumberFormat()->setFormatCode('#,##0');
    $sheet->getRowDimension($totalRow)->setRowHeight(20);

    // ===== COLUMN WIDTH =====
    $sheet->getColumnDimension('A')->setWidth(6);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(14);
    $sheet->getColumnDimension('D')->setWidth(22);
    $sheet->getColumnDimension('E')->setWidth(28);
    $sheet->getColumnDimension('F')->setWidth(22);
    $sheet->getColumnDimension('G')->setWidth(18);
    $sheet->getColumnDimension('H')->setWidth(14);

    // ===== FREEZE PANES =====
    $sheet->freezePane('A'.($startRow + 1));

    // ===== PRINT SETTINGS =====
    $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    $sheet->getPageSetup()->setFitToWidth(1);

    // ===== DOWNLOAD =====
    $filename = 'Laporan-Penjualan-'.($month ? \Carbon\Carbon::create()->month($month)->format('M').'-' : '').$year.'.xlsx';

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

    return response()->streamDownload(function() use ($writer) {
        $writer->save('php://output');
    }, $filename, [
        'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        'Cache-Control'       => 'max-age=0',
    ]);
}

    public function stock(Request $request)
    {
        $query = ProductStock::with(['product.brand', 'product.category'])
            ->whereHas('product', fn($q) => $q->whereNull('deleted_at'));

        if ($request->filled('brand_id')) {
            $query->whereHas('product', fn($q) => $q->where('brand_id', $request->brand_id));
        }

        if ($request->filled('status')) {
            match($request->status) {
                'low'   => $query->whereColumn('quantity', '<=', 'min_stock')->where('quantity', '>', 0),
                'empty' => $query->where('quantity', 0),
                'safe'  => $query->whereColumn('quantity', '>', 'min_stock'),
                default => null,
            };
        }

        $stocks = $query->get();

        $summary = [
            'total_sku'    => $stocks->count(),
            'total_qty'    => $stocks->sum('quantity'),
            'low_count'    => $stocks->filter(fn($s) => $s->quantity > 0 && $s->quantity <= $s->min_stock)->count(),
            'empty_count'  => $stocks->where('quantity', 0)->count(),
        ];

        $brands = \App\Models\Brand::where('is_active', true)->get();

        return view('admin.reports.stock', compact('stocks', 'summary', 'brands'));
    }
}