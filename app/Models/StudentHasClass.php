<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Periode;

class StudentHasClass extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $table = 'student_has_classes';

    protected $fillable = ['students_id', 'classrooms_id', 'periode_id', 'tipetransaksi_id'];

    public function students(){
        return $this->belongsTo(Student::class, 'students_id', 'id');
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class, 'periode_id');
    }

    public function classrooms(){
        return $this->belongsTo(Classroom::class, 'classrooms_id', 'id');
    }

}
