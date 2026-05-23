<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AdminReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN REKAPITULASI
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $today        = Carbon::today();
        $startWeek    = Carbon::now()->startOfWeek();
        $endWeek      = Carbon::now()->endOfWeek();
        $currentMonth = now()->month;
        $currentYear  = now()->year;

        // Penjualan Harian
        $dailySales = Order::whereDate('created_at', $today)
            ->where('status', Order::STATUS_SELESAI)
            ->sum('total_price');

        $dailyOrders = Order::whereDate('created_at', $today)->count();

        // Penjualan Mingguan
        $weeklySales = Order::whereBetween('created_at', [$startWeek, $endWeek])
            ->where('status', Order::STATUS_SELESAI)
            ->sum('total_price');

        $weeklyOrders = Order::whereBetween('created_at', [$startWeek, $endWeek])->count();

        // Penjualan Bulanan
        $monthlySales = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', Order::STATUS_SELESAI)
            ->sum('total_price');

        $monthlyOrders = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Transaksi Terbaru
        $recentOrders = Order::with('user')->latest()->take(10)->get();

        // Data Grafik 7 Hari
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = Order::whereDate('created_at', $date)
                ->where('status', Order::STATUS_SELESAI)
                ->sum('total_price');
            $chartData[] = [
                'date'  => $date->format('d M'),
                'sales' => (int) $sales,
            ];
        }

        return view('admin.rekapitulasi.index', compact(
            'dailySales', 'dailyOrders',
            'weeklySales', 'weeklyOrders',
            'monthlySales', 'monthlyOrders',
            'recentOrders', 'chartData'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT PDF
    |--------------------------------------------------------------------------
    */

    public function exportPdf()
    {
        $orders = Order::with('user')->latest()->take(500)->get();

        $pdf = Pdf::loadView('admin.rekapitulasi.pdf', compact('orders'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('rekapitulasi-penjualan-' . now()->format('Y-m-d') . '.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function exportExcel()
    {
        $orders = Order::with('user')->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekapitulasi');

        // Title
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN REKAPITULASI PENJUALAN - ' . now()->format('d/m/Y'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Subtitle
        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'Dimsum Mak\'Angga');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header
        $headers = ['No', 'Customer', 'No HP', 'Total', 'Status', 'Tanggal'];
        $cols    = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach ($headers as $i => $header) {
            $sheet->setCellValue($cols[$i] . '4', $header);
        }

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEA580C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A4:F4')->applyFromArray($headerStyle);

        // Data
        $row = 5;
        $no  = 1;

        foreach ($orders as $order) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $order->customer_name);
            $sheet->setCellValue('C' . $row, $order->phone ?? '-');
            $sheet->setCellValue('D' . $row, $order->total_price);
            $sheet->setCellValue('E' . $row, $order->status_label);
            $sheet->setCellValue('F' . $row, $order->created_at->format('d M Y H:i'));
            $row++;
        }

        // Format Rupiah
        if ($row > 5) {
            $sheet->getStyle('D5:D' . ($row - 1))
                ->getNumberFormat()
                ->setFormatCode('"Rp "#,##0');
        }

        // Auto Size
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Border
        if ($row > 5) {
            $sheet->getStyle('A4:F' . ($row - 1))->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
        }

        // Stream download
        $fileName = 'rekapitulasi-penjualan-' . now()->format('Y-m-d') . '.xlsx';

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
