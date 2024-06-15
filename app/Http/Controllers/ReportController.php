<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sales;
use Illuminate\Http\Request;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $users = User::all();
        $sales = Sales::orderBy('created_at', 'desc')->get();

        $data = [];

        foreach ($users as $user) {
            $userId = $user->id;
            $jumlahTransaksiBarang = 0;
            $jumlahTransaksiJasa = 0;
            $nominalTransaksiBarang = 0;
            $nominalTransaksiJasa = 0;
            $tanggalTransaksiUnik = [];

            foreach ($sales as $sale) {
                if ($sale->user_id == $userId) {
                    $tanggalTransaksi = $sale->tanggal_transaksi;
                    $kategoriId = $sale->kategori_id;
                    $nominal = $sale->nominal;

                    if (!in_array($tanggalTransaksi, $tanggalTransaksiUnik)) {
                        $tanggalTransaksiUnik[] = $tanggalTransaksi;
                    }

                    if ($kategoriId == 1) {
                        $jumlahTransaksiBarang++;
                        $nominalTransaksiBarang += $nominal;
                    } elseif ($kategoriId == 2) {
                        $jumlahTransaksiJasa++;
                        $nominalTransaksiJasa += $nominal;
                    }
                }
            }

            $jumlahHariKerja = count($tanggalTransaksiUnik);
            $data[] = [
                'user_id' => $userId,
                'user_name' => $user->name,
                'total_hari_kerja' => $jumlahHariKerja,
                'jumlah_transaksi_barang' => $jumlahTransaksiBarang,
                'jumlah_transaksi_jasa' => $jumlahTransaksiJasa,
                'nominal_transaksi_barang' => $nominalTransaksiBarang,
                'nominal_transaksi_jasa' => $nominalTransaksiJasa,
            ];
        }

        return view('sales.report', compact('data'));
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        return Excel::download(new SalesReportExport($request->start_date, $request->end_date), 'sales_report.xlsx');
    }
}
