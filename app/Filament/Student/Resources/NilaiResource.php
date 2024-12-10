<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\NilaiResource\Pages;
use App\Filament\Student\Resources\NilaiResource\RelationManagers;
use App\Models\Classroom;
use App\Models\Nilai;
use Auth;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NilaiResource extends Resource
{
    protected static ?string $model = Nilai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject.name'),
                TextColumn::make('nilai'),
                TextColumn::make('periode.name'),
                TextColumn::make('category_nilai.name'),
            ])
            ->filters([
                SelectFilter::make('class_id')
                    ->options(
                        Classroom::whereHas('students', function ($query) {
                            $query->where('user_id', Auth::user()->id);
                        })->groupBy('name', 'id')->pluck('name', 'id')
                    )
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListNilais::route('/'),
            // 'create' => Pages\CreateNilai::route('/create'),
            // 'edit' => Pages\EditNilai::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('student.user', function ($query) {
            $query->where('id', Auth::user()->id);
        });
    }                
}
