<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Student;
use App\Models\TipeTransaksi;
use App\Models\Transaksi;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Navigation\MenuItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->label('Nama'),
                    Select::make('id_tipe_transaksi')
                        ->label('Jenis Transaksi')
                        ->options(TipeTransaksi::pluck('name', 'id'))
                        ->required(),
                    Select::make('bank')
                        ->label('Bank')
                        ->options([
                            'bca' => 'BCA',
                            'bni' => 'BNI',
                        ])
                        ->required(),
                ]),
            ]);


    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn (Builder $query) => $query->with('tipeTransaksi'))
            ->columns([
                TextColumn::make('order_id')->label('Order Id')
                    ->searchable(),
                TextColumn::make('name')->label('Nama'),
                TextColumn::make('tipeTransaksi.name')
                    ->label('Jenis Transaksi'),
                TextColumn::make('amount')->label('Jumlah'),
                TextColumn::make('va_number')->label('VA Number'),
                TextColumn::make('status')->label('Status')
                    ->colors([
                        'warning' => 'Pending',
                        'success' => 'Paid',
                        'danger' => 'Expired',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListTransaksi::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            // 'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
