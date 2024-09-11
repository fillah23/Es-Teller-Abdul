<?php

namespace App\Http\Controllers;

use App\Exports\LaporanTransaksiExport;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter dari request
        $tipeTransaksi = $request->input('tipeTransaksi');
        $dateRange = $request->input('date_range');
        $shift = $request->input('shift'); // New filter for shift

        // Default date range jika tidak ada input
        if (!$dateRange) {
            $startDate = Carbon::now()->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');
        } else {
            // Pisahkan rentang tanggal
            $dates = explode(" to ", $dateRange);
            $startDate = $dates[0];
            $endDate = isset($dates[1]) ? $dates[1] : $dates[0];
        }

        // Query dasar
        $query = Transaksi::query();

        // Filter berdasarkan tipeTransaksi
        if ($tipeTransaksi) {
            $query->where('tipeTransaksi', $tipeTransaksi);
        }

        // Filter berdasarkan shift
        if ($shift) {
            $query->where('shift', $shift);
        }

        // Filter berdasarkan rentang tanggal
        $query->whereBetween('tanggal', [$startDate, $endDate]);

        // Ambil data transaksi
        $transaksi = $query->get();

        // Hitung total pemasukan, pengeluaran, dan laba bersih
        $totalPemasukan = $transaksi->where('tipeTransaksi', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $transaksi->where('tipeTransaksi', 'pengeluaran')->sum('nominal');
        $labaBersih = $totalPemasukan - $totalPengeluaran;

        // Return ke view dengan tambahan total dan filter shift
        return view('laporan.index', [
            'transaksi' => $transaksi,
            'tipeTransaksi' => $tipeTransaksi,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'shift' => $shift, // Pass shift filter value
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'labaBersih' => $labaBersih,
        ]);
    }
    public function exportExcel(Request $request)
    {
        $fileName = 'Laporan_Transaksi_All_Data_' . Carbon::now()->format('Y-m-d') . '.xlsx';

    // Return Excel download for all data
    return Excel::download(new LaporanTransaksiExport(null, null), $fileName);
    }
}
