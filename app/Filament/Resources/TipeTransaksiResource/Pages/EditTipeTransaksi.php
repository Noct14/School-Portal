<?php

namespace App\Filament\Resources\TipeTransaksiResource\Pages;

use App\Filament\Resources\TipeTransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipeTransaksi extends EditRecord
{
    protected static string $resource = TipeTransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
