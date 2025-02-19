<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipeTransaksiResource\Pages;
use App\Filament\Resources\TipeTransaksiResource\RelationManagers;
use App\Models\TipeTransaksi;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipeTransaksiResource extends Resource
{
    protected static ?string $model = TipeTransaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')
                    ->required()
                    ->label('Jenis Transaksi'),
                TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->label('Jumlah Tagihan'), 
                ])           
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Jenis Transaksi'),
                TextColumn::make('price')
                    ->label('Jumlah Tagihan')
                    ->money('IDR'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTipeTransaksis::route('/'),
            'create' => Pages\CreateTipeTransaksi::route('/create'),
            'edit' => Pages\EditTipeTransaksi::route('/{record}/edit'),
        ];
    }
}
