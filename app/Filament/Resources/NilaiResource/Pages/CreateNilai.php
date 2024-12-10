<?php

namespace App\Filament\Resources\NilaiResource\Pages;

use App\Filament\Resources\NilaiResource;
use App\Models\CategoryNilai;
use App\Models\Classroom;
use App\Models\Nilai;
use App\Models\Periode;
use App\Models\Student;
use App\Models\Subject;
use Closure;
use Filament\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;

class CreateNilai extends CreateRecord
{
    protected static string $resource = NilaiResource::class;

    protected static string $view = 'filament.resources.nilai-resource.pages.form-nilai';

    public function form(Form $form): form
    {
        return $form->schema([
            Card::make()
                ->schema([
                    Card::make()->schema([
                        Select::make('classrooms')
                        ->options(Classroom::all()->pluck('name', 'id'))
                        ->label('Kelas')
                        ->live()
                        ->afterStateUpdated(function (Set $set){
                            $set('student', null);
                            $set('periode', null);
                        }),
                    Select::make('periode')
                        ->options(Periode::all()->pluck('name', 'id'))
                        ->label('Periode')
                        ->live()
                        ->preload()
                        ->afterStateUpdated(fn (Set $set)=> $set('student', null)),
                    
                    Select::make('subject_id')
                        ->options(Subject::all()->pluck('name', 'id'))    
                        ->label('Mata Pelajaran')
                        ->required(),
                    Select::make('category_nilai')
                        ->options(CategoryNilai::all()->pluck('name', 'id'))
                        ->label('Kategori Nilai')
                        ->required()
                        ->columnSpan(3),
                    ]),
                    Repeater::make('nilaiStudents')
                        ->schema(fn (Get $get): array =>   [
                        Select::make('students')
                            ->options(function () use ($get) {
                                $data = Student::whereIn('id', function ($query) use ($get) {
                                    $query->select('students_id')
                                        ->from('student_has_classes')
                                        ->where('homerooms_id', $get('classrooms'))
                                        ->where('periode_id', $get('periode'))
                                        ->where('is_open', true)->pluck('students_id');
                                })
                                ->pluck('name', 'id');
                                return $data;
                        })
                        ->label('Siswa'),
                        TextInput::make('nilai')
                            ->required()
                            ->rules([
                                fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                    if ($get('nilai') > 100) {
                                        $fail('Nilai tidak boleh lebih dari 100');
                                    }
                                },
                            ])
                    ])->columns(2)
                ])
                ]);
    }

    public function save(){
        $get = $this->form->getState();

        $insert = [];
        foreach ($get['nilaiStudents'] as $row) {
            array_push($insert, [
                'class_id' => $get['classrooms'],
                'student_id' => $row['students'],
                'periode_id' => $get['periode'],
                'teacher_id' => auth()->user()->id,
                'subject_id' => $get['subject_id'],
                'category_nilai_id' => $get['category_nilai'],
                'nilai' => $row['nilai']
            ]);
        }

        Nilai::insert($insert);

        return redirect()->to('admin/nilais');
    }
}
