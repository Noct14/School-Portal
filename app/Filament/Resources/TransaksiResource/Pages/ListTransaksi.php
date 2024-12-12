<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransaksi extends ListRecords
{
    protected static string $resource = TransaksiResource::class;

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
