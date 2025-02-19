<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Classroom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function studentHasClasses()
    {
        return $this->hasMany(StudentHasClass::class, 'classrooms_id', 'id');
    }
    
    // public function tipeTransaksi(){
    //     return $this->belongsTo(TipeTransaksi::class, 'tipetransaksi_id', 'id_tipe');
    // }

    public function tagihan(){
        return $this->hasMany(tagihan::class);
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(Student::class, StudentHasClass::class, 'classrooms_id', 'id', 'id', 'students_id');
    }

    public function student()
{
    return $this->belongsToMany(Student::class, 'student_has_classes', 'classroom_id', 'student_id') // Nama kolom pivot
        ->withPivot('periode_id') // Jika ada
        ->withTimestamps();
}

    public function periode(){
        return $this->belongsTo(Periode::class, 'periode_id', 'id');
    }
    // public function student(): HasManyThrough {
    //     return $this->hasManyThrough(Student::class, StudentHasClass::class);
    // }

}
