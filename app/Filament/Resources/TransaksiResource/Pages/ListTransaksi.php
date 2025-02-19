<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;

class ListTransaksi extends ListRecords
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {   
        $decodeQueryString = urldecode(request()->getQueryString());
        return [
            Actions\Action::make('Export')
            
            // FAKE EXPORT
            ->action(function () {
                // Path file yang ingin di-download
                $filePath = storage_path('app/public/REKAP_PEMBAYARAN_SISWA.xlsx');

                // Periksa apakah file ada
                if (file_exists($filePath)) {
                    $currentDate = date('Ymd'); // Format tanggal: YYYYMMDD
                    $fileName = "Laporan_Pembayaran_Siswa_{$currentDate}.xlsx";
                    return response()->download($filePath, $fileName);
                }

                // Jika file tidak ditemukan, tampilkan notifikasi error
                $this->notify('danger', 'File tidak ditemukan!');
            }),
            
            // ->action(function (array $data) {
            //     // Redirect ke route export dengan parameter tanggal
            //     return redirect()->route('export.transactions', [
            //         'start_date' => $data['start_date'],
            //         'end_date' => $data['end_date'],
            //     ]);
            // }),
        ];
    }
}
