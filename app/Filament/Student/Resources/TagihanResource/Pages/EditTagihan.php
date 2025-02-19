<?php

namespace App\Filament\Student\Resources\tagihanResource\Pages;

use App\Filament\Student\Resources\tagihanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTagihan extends EditRecord
{
    protected static string $resource = tagihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
