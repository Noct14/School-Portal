<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransaksiExport implements FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Mengambil data berdasarkan rentang tanggal
     */
    public function headings(): array
    {
        return [
            'NIS',
            'Nama Siswa',
            'Tipe Transaksi',
            'Bank',
            'Nominal',
            'Status',
            'Tanggal Update',
        ];
    }

    public function collection()
    {
        $transaksis = Transaksi::with('student') // Ambil relasi siswa
            ->whereBetween('updated_at', [$this->startDate, $this->endDate])
            ->get();

        // Persiapkan data yang akan diekspor
        $data = $transaksis->map(function ($transaksi) {
            return [
                'nis' => $transaksi->student->nis ?? 'N/A',
                'name' => $transaksi->student->name ?? 'N/A', // Nama siswa
                'transaksi_name' => $transaksi->name,
                'bank' => $transaksi->bank,
                'amount' => $transaksi->amount,
                'status' => $transaksi->status,
                'updated_at' => $transaksi->updated_at->format('d-m-Y H:i'), // Format tanggal pembaruan
            ];
        });

        return $data;
    }

    /**
     * Mapping data untuk setiap baris
     */
    public function map($transaction): array
    {
        return [
            $transaction['nis'],
            $transaction['name'],
            $transaction['transaksi_name'],
            $transaction['bank'],
            $transaction['amount'],
            $transaction['status'],
            $transaction['updated_at'],
        ];
    }

    /**
     * Mengatur judul sheet
     */
    public function title(): string
    {
        return 'Laporan Transaksi';
    }

    /**
     * Mengatur gaya pada file Excel
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Menebalkan baris pertama (header)
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Menambahkan event setelah sheet dibuat
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Menambahkan judul di atas header
                $currentDate = now()->format('d-m-Y H:i');
                $sheet->mergeCells('A1:G1');
                $sheet->setCellValue('A1', 'Laporan Transaksi Sekolah');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Menambahkan subjudul "SD SANTO MIKAEL"
                $sheet->mergeCells('A2:G2');
                $sheet->setCellValue('A2', 'SD SANTO MIKAEL');
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Menambahkan subjudul tanggal periode
                $sheet->mergeCells('A3:G3');
                $sheet->setCellValue('A3', 'Periode: ' . $this->startDate . ' - ' . $this->endDate);
                $sheet->getStyle('A3')->getFont()->setItalic(true);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');

                // Menambahkan tanggal unduh
                $sheet->mergeCells('A4:G4');
                $sheet->setCellValue('A4', 'Tanggal Unduh: ' . $currentDate);
                $sheet->getStyle('A4')->getFont()->setItalic(true);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal('center');

                // Menambahkan baris kosong setelah judul
                $sheet->getRowDimension(5)->setRowHeight(20);

                // Menambahkan header kolom
                $sheet->fromArray($this->headings(), null, 'A5'); // Mulai dari baris 5

                // Ambil data transaksi dan masukkan ke dalam sheet
                $data = $this->collection()->toArray(); // Gunakan toArray() untuk konversi Collection ke array
                $sheet->fromArray($data, null, 'A6'); // Mulai dari baris 6 untuk data transaksi

                // Mengatur lebar kolom otomatis
                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
