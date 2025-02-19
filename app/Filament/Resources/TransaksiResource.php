<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Student;
use App\Models\TipeTransaksi;
use App\Models\Transaksi;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Navigation\MenuItem;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

 
    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
            // ->required()
            ,
            Select::make('id_tipe_transaksi')
                ->options(fn () => \DB::table('tagihans')->pluck('name', 'id_tipe_transaksi')->toArray())
                // ->relationship('tagihan', 'name')
                ->required(),
            Select::make('bank')
                ->options(['bca' => 'BCA', 'bni' => 'BNI'])
                ->required(),
        ]);


    }

    

    public static function table(Table $table): Table
    {
        return $table
           
            ->columns([
                TextColumn::make('student.name')->label('Nama Siswa'),
                TextColumn::make('name')->label('Nama Transaksi')
                ->searchable(),
                // TextColumn::make('id_tipe_transaksi')->label('Tipe Transaksi'),
                TextColumn::make('amount')->label('Nominal')->money('IDR'),
                TextColumn::make('bank')->label('Bank'),
                TextColumn::make('va_number')->label('Virtual Account'),
                TextColumn::make('status')->label('Status')
                        ->colors([
                            'warning' => 'Pending',
                            'success' => 'Paid',
                            'danger' => 'Expired',
                        ]),
                TextColumn::make('updated_at')->label('Tanggal Pembayaran')->dateTime(),
                
            ])
            ->filters([
                SelectFilter::make('Status')
                ->options([
                    'Pending' => 'Pending',
                    'Paid' => 'Paid',])
            ])
            ->actions([
                Tables\Actions\Action::make('print_invoice')
                    ->label('Print Invoice')
                    ->url(fn (Transaksi $record) => route('transaksi.bukti-bayar', $record->id))
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
            // 'create' => Pages\CreateTransaksi::route('/create'),
            // 'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
