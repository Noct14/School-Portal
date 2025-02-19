<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use App\Models\Tagihan;
use App\Models\Periode;
use App\Models\Student;
use App\Models\TipeTransaksi;
use App\Models\Transaksi;
use DB;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Support\RawJs;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\StudentHasClass;
// use Illuminate\Support\Carbon;
use Carbon\Carbon;
use Str;


class TagihanRelationManager extends RelationManager
{
    protected static string $relationship = 'tagihan';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Jenis Transaksi')
                    ->required(),
                TextInput::make('price')
                    ->label('Jumlah Tagihan')
                    ->required(),
            ]);
    }



    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Jenis Transaksi'),
                TextColumn::make('price')
                    ->label('Jumlah Tagihan')
                    ->money('IDR'),
                TextColumn::make('id_tipe_transaksi')
                    ->label('ID Tipe Transaksi'),
                
            ])
                
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->after(function (Model $record) {
                    // Dapatkan semua siswa yang terhubung dengan kelas terkait
                    $students = StudentHasClass::where('classrooms_id', $record->classroom_id)
                        ->pluck('students_id');

                    // Buat transaksi untuk setiap siswa
                    foreach ($students as $studentId) {
                        Transaksi::create([
                            'order_id' => (string) Str::uuid(),
                            'students_id' => $studentId,
                            'name' => $record->name,
                            'id_tipe_transaksi' => $record->id_tipe_transaksi,
                            'va_number' => '88433' . str_pad(random_int(0, 99999999999), 18, '0', STR_PAD_LEFT),
                            'amount' => $record->price,
                            'bank' => 'bca',
                            'status' => 'Pending', // Atur sesuai kebutuhan
                        ]);
                    }
                }),
                
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                ReplicateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }



};