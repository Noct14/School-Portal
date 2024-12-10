<?php

namespace App\Filament\Resources;

use App\Enums\ReligionStatus;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Infolists\Infolist;


class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Management';//grup side bar


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nis')
                    ->label('NIS')
                    ->required(),
                TextInput::make('name')
                    ->label('Nama Siswa')
                    ->required(),
                Select::make('gender')
                    ->options(
                        [
                            'Male' => 'Laki-laki',
                            'Female' => 'Perempuan'
                        ]
                    )
                    ->required(),
                DatePicker::make('birthday')
                    ->label('Tanggal Lahir')
                    ->required(),
                Select::make('religion')
                    ->options(ReligionStatus::class)
                    ->required(),
                TextInput::make('contact')
                    ->required(),
                FileUpload::make('profile')
                    ->directory('students')
                    ->required(),
                Select::make('status')
                    ->options(
                        [
                            'accept' => 'accept',
                            'off' => 'off',
                            'move' => 'move',
                            'grade' => 'grade'
                        ]
                    )
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Nomor')->state(
                    static function (HasTable $livewire, \stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
                //nomor

                TextColumn::make('nis')
                    ->searchable()
                    ->label('NIS'),
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nama Siswa'),
                TextColumn::make('gender')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('birthday')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tanggal Lahir'),
                TextColumn::make('contact'),
                ImageColumn::make('profile')
                    ->circular()
                    ->extraImgAttributes(['img_preview'])
                    ->toggleable(isToggledHiddenByDefault: false),
                SelectColumn::make('religion')
                        ->options(ReligionStatus::class)
                        ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('status')
                    ->formatStateUsing(fn (string $state): string => ucfirst("{$state}"))
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'accept' => 'Accept',
                        'off' => 'Off',
                        'move' => 'Move',
                        'grade' => 'Grade'
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('Change Status')
                        ->icon('heroicon-m-check')
                        ->requiresConfirmation()
                        ->form([
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'accept' => 'Accept',
                                    'off' => 'Off',
                                    'move' => 'Move',
                                    'grade' => 'Grade'
                                ])
                                ->required()
                                ])
                        ->action(function (Collection $records, array $data){
                            $records->each(function($record) use ($data) {
                                Student::where('id', $record->id)->update(['status' => $data['status']]);
                            });
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make()
            // ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}/view'),
        ];
    }

    public static function getLabel(): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'id') {
            return 'Murid';
        } else {
            return 'Students';
        };
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Fieldset::make('Biodata')
                            ->schema([
                                Split::make([
                                    ImageEntry::make('profile')
                                        ->hiddenLabel()
                                        ->grow(false),
                                    Grid::make(2)
                                        ->schema([
                                            Group::make([
                                                TextEntry::make('nis'),
                                                TextEntry::make('name'),
                                                TextEntry::make('gender'),
                                                TextEntry::make('birthday')
                                            ])
                                            ->inlineLabel()
                                            ->columns(1),

                                            Group::make([
                                                TextEntry::make('religion'),
                                                TextEntry::make('contact'),
                                                TextEntry::make('status')
                                                ->badge()
                                                ->colors(['accept' => 'success',
                                                    'off' => 'danger',
                                                    'move' => 'warning',
                                                    'grade' => 'warning',]),
                                                TextEntry::make('birthday')
                                            ])
                                            ->inlineLabel()
                                            ->columns(1),
                                        ])
                                ])->from('lg')
                            ])->columns(1)
                    ])->columns(2)
            ]);
    }
}
