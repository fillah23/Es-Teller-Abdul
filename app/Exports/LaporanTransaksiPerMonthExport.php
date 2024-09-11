<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LaporanTransaksiPerMonthExport implements FromView, WithTitle, WithStyles
{
    protected $month;
    protected $monthName;

    public function __construct(Carbon $month, $monthName)
    {
        $this->month = $month;
        $this->monthName = $monthName;
    }

    public function view(): \Illuminate\Contracts\View\View
    {
        // Fetch pemasukan (income) and pengeluaran (expenditure) data for the given month
        $startDate = $this->month->copy()->startOfMonth();
        $endDate = $this->month->copy()->endOfMonth();

        $pemasukan = Transaksi::where('tipeTransaksi', 'pemasukan')
                                ->whereBetween('tanggal', [$startDate, $endDate])
                                ->get();

        $pengeluaran = Transaksi::where('tipeTransaksi', 'pengeluaran')
                                 ->whereBetween('tanggal', [$startDate, $endDate])
                                 ->get();

        // Calculate total pemasukan and pengeluaran
        $totalPemasukan = $pemasukan->sum('nominal');
        $totalPengeluaran = $pengeluaran->sum('nominal');
        $pendapatanBersih = $totalPemasukan - $totalPengeluaran;

        return view('laporan.laporan-transaksi', [
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'month' => $this->monthName,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'pendapatanBersih' => $pendapatanBersih,
        ]);
    }

    public function title(): string
    {
        return $this->month->format('F Y');
    }

    public function styles(Worksheet $sheet)
    {
        // Style only the headers for both Pemasukan and Pengeluaran tables
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                       'startColor' => ['argb' => '007BFF']],
        ]);

        $sheet->getStyle('H1:K1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                       'startColor' => ['argb' => 'FF5733']], // Different color for Pengeluaran header
        ]);

        // Auto size columns for better readability
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Add border to the entire table
        $sheet->getStyle('A1:K' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'], // Black border color
                ],
            ],
        ]);

        return $sheet;
    }
}
