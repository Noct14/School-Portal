<?php

namespace App\Filament\Resources\NilaiResource\Pages;

use App\Filament\Resources\NilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNilais extends ListRecords
{
    protected static string $resource = NilaiResource::class;

    protected function getHeaderActions(): array
    {   
        $decodeQueryString = urldecode(request()->getQueryString());
        return [
            Actions\Action::make('Export')
            ->url(url('/export-nilai?'.$decodeQueryString)),
            Actions\CreateAction::make(),
        ];
    }
}
