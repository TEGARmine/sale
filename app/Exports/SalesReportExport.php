<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithEvents
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $users = User::all();
        $sales = Sales::whereBetween('tanggal_transaksi', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $countingForUser = [];
        $jumlahTransaksiBarang = [];
        $jumlahTransaksiJasa = [];
        $nominalTransaksiBarang = [];
        $nominalTransaksiJasa = [];

        foreach ($sales as $value) {
            $userId = $value->user_id;
            $tanggalTransaksi = $value->tanggal_transaksi;
            $kategoriId = $value->kategori_id;
            $nominal = $value->nominal;

            if (!isset($countingForUser[$userId])) {
                $countingForUser[$userId] = [];
                $jumlahTransaksiBarang[$userId] = 0;
                $jumlahTransaksiJasa[$userId] = 0;
                $nominalTransaksiBarang[$userId] = 0;
                $nominalTransaksiJasa[$userId] = 0;
            }

            if (!in_array($tanggalTransaksi, $countingForUser[$userId])) {
                $countingForUser[$userId][] = $tanggalTransaksi;
            }

            if ($kategoriId == 1) {
                $jumlahTransaksiBarang[$userId]++;
                $nominalTransaksiBarang[$userId] += $nominal;
            } elseif ($kategoriId == 2) {
                $jumlahTransaksiJasa[$userId]++;
                $nominalTransaksiJasa[$userId] += $nominal;
            }
        }

        $data = [];

        foreach ($users as $user) {
            $userId = $user->id;
            $jumlahHariKerja = isset($countingForUser[$userId]) ? count($countingForUser[$userId]) : 0;
            $jumlahTransaksiBarangUser = $jumlahTransaksiBarang[$userId] ?? 0;
            $jumlahTransaksiJasaUser = $jumlahTransaksiJasa[$userId] ?? 0;
            $nominalTransaksiBarangUser = $nominalTransaksiBarang[$userId] ?? 0;
            $nominalTransaksiJasaUser = $nominalTransaksiJasa[$userId] ?? 0;

            $data[] = [
                'user_id' => $userId,
                'user_name' => $user->name,
                'total_hari_kerja' => $jumlahHariKerja,
                'jumlah_transaksi_barang' => $jumlahTransaksiBarangUser,
                'jumlah_transaksi_jasa' => $jumlahTransaksiJasaUser,
                'nominal_transaksi_barang' => $nominalTransaksiBarangUser,
                'nominal_transaksi_jasa' => $nominalTransaksiJasaUser,
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            ['Requestor'],
            [],
            ['Parameter'],
            ['Start Date'],
            ['End Date'],
            [],
            [
                'User',
                'Jumlah Hari Kerja',
                'Jumlah Transaksi Barang',
                'Jumlah Transaksi Jasa',
                'Nominal Transaksi Barang',
                'Nominal Transaksi Jasa',
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 16,
            'B' => 18,
            'C' => 22,
            'D' => 22,
            'E' => 25,
            'F' => 25,
        ];
    }

    public function map($row): array
    {
        $nominalTransaksiBarang = number_format($row['nominal_transaksi_barang'], 0, ',', ',');
        $nominalTransaksiJasa = number_format($row['nominal_transaksi_jasa'], 0, ',', ',');

        return [
            $row['user_name'],
            (string) ($row['total_hari_kerja'] ?? 0),
            (string) ($row['jumlah_transaksi_barang'] ?? 0),
            (string) ($row['jumlah_transaksi_jasa'] ?? 0),
            $nominalTransaksiBarang,
            $nominalTransaksiJasa,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle('A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('B:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $richText = new RichText();
                $payableName = $richText->createTextRun(auth()->user()->name . ' (');
                $payableName->getFont()->setBold(false);

                $payableEmail = $richText->createTextRun(auth()->user()->email);
                $payableEmail->getFont()->setColor(new Color(Color::COLOR_RED));

                $closingBracket = $richText->createTextRun(')');
                $closingBracket->getFont()->setBold(false);

                $sheet->getCell('B1')->setValue($richText);

                $formatter = new \IntlDateFormatter(
                    'id_ID',
                    \IntlDateFormatter::LONG,
                    \IntlDateFormatter::NONE,
                    'Asia/Jakarta',
                    \IntlDateFormatter::GREGORIAN
                );

                $startDate = $formatter->format(new \DateTime($this->startDate));
                $endDate = $formatter->format(new \DateTime($this->endDate));

                $sheet->setCellValue('B4', $startDate);
                $sheet->setCellValue('B5', $endDate);

                $sheet->getStyle('A1:A5')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B1:B5')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A7:F7')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
            },
        ];
    }
}
