<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use App\Models\Transaksi;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CreateTransaksi extends CreateRecord
{
    protected static string $resource = TransaksiResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {   
        
        // Panggil API pembayaran
        $response = Http::withoutVerifying()->withHeaders([
            'Accept' => 'application/json', // Memastikan server mengembalikan JSON
        ])
        ->post(route('api.pay'), [
            'name' => 'test',
            'id_tipe_transaksi' => $data['id_tipe_transaksi'],
            'bank' => $data['bank'],
        ])
        ;

        if (!$data['id_tipe_transaksi'] || !$data['bank']) {
            Notification::make()
                ->title('Missing Data')
                ->body('Transaction type and bank are required.')
                ->warning()
                ->send();
            return $data;
        }

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
            ->body('VA Number: ' . $result['data']['va_number'])
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