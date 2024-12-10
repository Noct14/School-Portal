<?php

namespace App\Filament\Resources\NilaiResource\Pages;

use App\Filament\Resources\NilaiResource;
use Filament\Actions\Action;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNilai extends EditRecord
{
    protected static string $resource = NilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // protected function getSaveFormAction(): Action
    // {
    //     return Action::make('create')
    //     ->disabled(function () : bool {
    //         return $this->data['nilai'] > 100 ? true : false;
    //     })
    //     ->tooltip(function () : string {
    //         return $this->data['nilai'] > 100 ? 'Nilai tidak boleh lebih dari 100' : '';
    //     });
    // }
}
