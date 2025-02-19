<?php

namespace App\Filament\Student\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Auth;
use Filament\Notifications\Notification;
use DB;

class PayTransaction extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Pembayaran';

    protected static string $view = 'filament.student.pages.pay-transaction';

    public $name;
    public $id_tipe_transaksi;
    public $bank;
    public $transactionName;

    public function mount()
    {
        $this->name = auth()->user()->name; // Auto-isi dengan nama user yang login
        $this->transactionName = ''; // Default kosong
    }

    public function updatedIdTipeTransaksi($value)
    {
        // Ambil nama transaksi dari database berdasarkan id_tipe_transaksi
        $this->transactionName = DB::table('tagihans')
            ->where('id_tipe_transaksi', $value)
            ->value('name') ?? 'Tidak Diketahui';
    }

    public function submit()
    {
        // Validasi input
        $this->validate([
            'id_tipe_transaksi' => 'required|string',
            'bank' => 'required|string|in:bca,bni',
        ]);

        try {
            // Kirim data ke API
            $response = Http::post('http://127.0.0.1:8000/api/spp/pay', [
                'name' => $this->transactionName, // Gunakan nama transaksi
                'id_tipe_transaksi' => $this->id_tipe_transaksi,
                'students_id' => auth()->id(),
                'bank' => $this->bank,
            ]);

            // Cek status response
            if ($response->successful()) {
                $result = $response->json();
                Notification::make()
                    ->title('Transaksi Berhasil')
                    ->success()
                    ->body('Transaksi berhasil, VA: ' . $result['data']['va'])
                    ->send(); // Kirim notifikasi sukses
            } else {
                Notification::make()
                    ->title('Gagal')
                    ->danger()
                    ->body('Gagal melakukan transaksi.')
                    ->send(); // Kirim notifikasi gagal
            }
        } catch (\Exception $e) {
            // Notification::make()
            //     ->title('Terjadi Kesalahan')
            //     ->danger()
            //     ->body('Terjadi kesalahan: ' . $e->getMessage())
            //     ->send(); // Kirim notifikasi error
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('id_tipe_transaksi')
                ->label('ID Tipe Transaksi')
                ->options(function () {
                    // Ambil semua tipe transaksi
                    $allTransactions = DB::table('tagihans')->pluck('name', 'id_tipe_transaksi')->toArray();

                    // Ambil transaksi yang sudah dibuat user
                    $userTransactions = DB::table('transaksi')
                        ->where('students_id', auth()->id())
                        ->pluck('id_tipe_transaksi')
                        ->toArray();

                    // Filter opsi yang belum pernah digunakan oleh user
                    $availableTransactions = array_diff_key($allTransactions, array_flip($userTransactions));

                    return $availableTransactions;
                })
                ->reactive() // Tambahkan untuk memicu perubahan dinamis
                ->afterStateUpdated(fn ($state) => $this->updatedIdTipeTransaksi($state)) // Panggil metode
                ->required(),

            Forms\Components\TextInput::make('transactionName')
                ->label('Nama Transaksi')
                ->default($this->transactionName)
                ->disabled()
                ->required(),

            Forms\Components\Select::make('bank')
                ->label('Bank')
                ->options([
                    'bca' => 'BCA',
                    'bni' => 'BNI',
                ])
                ->required(),
        ];
    }
}
