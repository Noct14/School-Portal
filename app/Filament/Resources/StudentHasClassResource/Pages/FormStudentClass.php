<?php

namespace App\Filament\Resources\StudentHasClassResource\Pages;

use App\Filament\Resources\StudentHasClassResource;
use App\Models\Classroom;
use App\Models\Periode;
use App\Models\Student;
use App\Models\StudentHasClass;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;

class FormStudentClass extends Page implements HasForms
{

    use InteractsWithForms;
    protected static string $resource = StudentHasClassResource::class;

    protected static string $view = 'filament.resources.student-has-class-resource.pages.form-student-class';

    public $students = [];
    public $classrooms = '';
    public $periode = '';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function getFormSchema(): array
    {
        return [
            Card::make()
                ->schema([
                    Select::make('students')
                        ->searchable()
                        ->multiple()
                        ->options(Student::pluck('name', 'id'))
                        ->label('Student')
                        ->columnSpan(3),
                    Select::make('classrooms')
                        ->searchable()
                        ->options(Classroom::pluck('name', 'id'))
                        ->label('Class'),
                    Select::make('periode')
                        ->searchable()  
                        ->options(Periode::pluck('name', 'id'))
            ])
            ->columns(3)
        ];
    }

    public function save()
    {
        $students = $this->students;
        $insert = [];
        foreach ($students as $row) {
            array_push($insert, [
                'students_id' => $row,
                'classrooms_id' => $this->classrooms,
                'periode_id' => $this->periode,
            ]);
        }

        StudentHasClass::insert($insert);

        return redirect()->to('admin\student-has-classes');
    }

}
