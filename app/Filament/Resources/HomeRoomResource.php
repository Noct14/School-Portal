<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeRoomResource\Pages;
use App\Filament\Resources\HomeRoomResource\RelationManagers;
use App\Models\HomeRoom;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Models\Classroom;
use App\Models\Periode;
use Filament\Tables\Columns\TextColumn;

class HomeRoomResource extends Resource
{
    protected static ?string $model = HomeRoom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('classrooms_id')
                    ->label('Pilih Kelas')
                    ->options(Classroom::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('periode_id')
                    ->label('Pilih Periode')
                    ->options(Periode::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('teachers_id')
                    ->label('Pilih Guru')
                    ->options(Teacher::all()->pluck('name', 'id'))
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('classroom.name')
                    ->label('Select Class')
                    ->searchable(),
                TextColumn::make('periode.name')
                    ->label('Select Periode')
                    ->searchable(),
                TextColumn::make('teacher.name')
                    ->label('Wali murid')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListHomeRooms::route('/'),
            'create' => Pages\CreateHomeRoom::route('/create'),
            'edit' => Pages\EditHomeRoom::route('/{record}/edit'),
        ];
    }
}
