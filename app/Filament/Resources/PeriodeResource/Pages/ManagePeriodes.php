<?php

namespace App\Filament\Resources\PeriodeResource\Pages;

use App\Filament\Resources\PeriodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePeriodes extends ManageRecords
{
    protected static string $resource = PeriodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    //judul halaman
    public function getTitle(): string
    {
        return 'Tahun Ajaran'; 
    }
}
