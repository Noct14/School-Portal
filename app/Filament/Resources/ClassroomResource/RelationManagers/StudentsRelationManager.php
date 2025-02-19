<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use App\Models\Periode;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\StudentHasClass;
use Log;


class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'studentHasClasses';

    protected static ?string $title = 'Siswa';


    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Select::make('students_id')
    //                 ->multiple()
    //                 ->options(fn() => Student::all()->pluck('name', 'id'))
    //                 ->label('Select Students')
    //                 ->saveUsing(function ($state) {
    //                     return $state;  // Pastikan $state adalah array, bukan string
    //                 }),
    //             Select::make('periode_id')
    //                 ->options(Periode::all()->pluck('name', 'id'))
    //                 ->label('Select Periode'),
    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('students.nis' )
                    ->label('NIS')
                    ->searchable(),
                Tables\Columns\TextColumn::make('students.name')
                    ->label('Nama Siswa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('students.gender')
                    ->label('Gender'),
                Tables\Columns\TextColumn::make('students.contact')
                    ->label('contact'),
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
};