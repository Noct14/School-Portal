<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'students';

    protected $fillable = [
        'nis', 'name', 'gender', 'birthday', 'religion', 'contact', 'class_id', 'email', 'password'
    ];

    protected $hidden = [
        'password', 
        'remember_token'
    ];

    // relasi ke Classroom (langsung)
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id', 'id');
    }

    // relasi many-to-many ke Classroom via pivot
    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'student_has_classes', 'students_id', 'classrooms_id') // Nama kolom pivot
            ->withPivot('periode_id') // Jika ada
            ->withTimestamps();
    }

    public function studentHasClasses()
    {
        return $this->belongsToMany(Classroom::class, 'student_has_classes', 'students_id', 'classrooms_id') // Nama kolom pivot
            ->withPivot('periode_id') // Jika ada
            ->withTimestamps();
    }
}
