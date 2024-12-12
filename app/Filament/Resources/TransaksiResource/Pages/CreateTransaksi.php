<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class CreateTransaksi extends CreateRecord
{
    protected static string $resource = TransaksiResource::class;

    // Override fungsi create default
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Panggil API pembayaran
        $response = Http::withoutVerifying()->withHeaders([
            'Accept' => 'application/json', // Memastikan server mengembalikan JSON
        ])
        ->post(route('api.pay'), [
            'name' => $data['name'],
            'id_tipe_transaksi' => $data['id_tipe_transaksi'],
            'bank' => $data['bank'],
        ]);

        // Periksa apakah respons valid JSON
        if ($response->header('Content-Type') !== 'application/json') {
            Notification::make()
                ->title('Non-JSON Response Ignored')
                ->body('Received an unexpected response format. Please check the API later.')
                ->warning()
                ->send();

            // Abaikan proses create jika format tidak sesuai
            return $data;
        }

        if (!$response->successful()) {
            Notification::make()
                ->title('Error creating payment')
                ->body($response->json()['message'] ?? 'Unknown error occurred')
                ->danger()
                ->send();

            $this->halt(); // Menghentikan proses create jika API gagal
        }

        $result = $response->json();
        
        // Tampilkan notifikasi dengan VA number
        Notification::make()
            ->title('Payment created successfully')
            ->body('VA Number: ' . $result['data']['va'])
            ->success()
            ->persistent()
            ->send();

        // Data sudah di-handle oleh API, tidak perlu create record lagi
        $this->halt();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}