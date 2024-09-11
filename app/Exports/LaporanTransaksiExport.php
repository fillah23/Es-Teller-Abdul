<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;

class LaporanTransaksiExport implements WithMultipleSheets
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
{
    $sheets = [];

    // If no start and end dates are provided, fetch all data without filtering
    if (is_null($this->startDate) && is_null($this->endDate)) {
        $transaksiPerMonth = Transaksi::selectRaw('YEAR(tanggal) as year, MONTH(tanggal) as month')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        foreach ($transaksiPerMonth as $periode) {
            $month = Carbon::createFromDate($periode->year, $periode->month)->format('F Y');
            $sheets[] = new LaporanTransaksiPerMonthExport(Carbon::create($periode->year, $periode->month), $month);
        }
    } else {
        // Existing logic if date range is provided (keep this for flexibility)
        $currentDate = Carbon::parse($this->startDate)->startOfMonth();
        $endDate = Carbon::parse($this->endDate)->endOfMonth();

        while ($currentDate <= $endDate) {
            $month = $currentDate->format('F Y');
            $sheets[] = new LaporanTransaksiPerMonthExport($currentDate->copy(), $month);
            $currentDate->addMonth();
        }
    }

    return $sheets;
}

}
