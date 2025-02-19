<?php

namespace App\Filament\Student\Resources\tagihanResource\Pages;

use App\Filament\Student\Resources\tagihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTagihan extends ListRecords
{
    protected static string $resource = tagihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
