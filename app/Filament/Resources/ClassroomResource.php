<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomResource\Pages;
use App\Filament\Resources\ClassroomResource\RelationManagers;
use App\Filament\Resources\ClassroomResource\RelationManagers\tagihanClasess;
use App\Filament\Resources\ClassroomResource\RelationManagers\TagihanRelationManager;
use App\Filament\Resources\ClassroomResource\RelationManagers\StudentRelationManager;
use App\Filament\Resources\ClassroomResource\RelationManagers\StudentsRelationManager;
use App\Filament\Resources\ClassroomResource\RelationManagers\SubjectsRelationManager;
use App\Models\Classroom;
use App\Models\Periode;
use App\Models\Student;
use App\Models\TipeTransaksi;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;

class ClassroomResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    // protected static ?string $navigationGroup = 'Setting';//grup sidebar

    protected static ?string $navigationLabel = 'Kelas';// nama side bar

    // public static function shouldRegisterNavigation(): bool
    // {
    //     if (auth()->user()->can('classroom'))   //hide navigation
    //         return true;
    //     else
    //         return false;
    // }

    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    TextInput::make('slug'),
                    Select::make('periode_id')
                            ->searchable()
                            ->options(Periode::all()->pluck('name', 'id'))
                            ->label('Periode'),
                    // Select::make('tipetransaksi_id')
                    // ->options(TipeTransaksi::all()->pluck('name', 'id_tipe'))
                    // ->label('Jenis Tagihan'),
                ])           
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Kelas'),
                TextColumn::make('periode.name'),
                

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
            StudentsRelationManager::class,
            TagihanRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassrooms::route('/'),
            'create' => Pages\CreateClassroom::route('/create'),
            'edit' => Pages\EditClassroom::route('/{record}/edit'),
        ];
    }
}
