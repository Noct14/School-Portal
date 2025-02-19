<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;


class tagihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        // 'tipe_id',
        'price',
        'name',
        'student_has_classes_id'
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate UUID ketika model baru dibuat
        static::creating(function ($model) {
            if (empty($model->id_tipe_transaksi)) {
                $model->id_tipe_transaksi = (string) Str::uuid(); // Menghasilkan UUID secara otomatis
            }
        });
    }
    // {
    //     $months = [
    //         'Januari', 'Februari', 'Maret', 'April',
    //         'Mei', 'Juni', 'Juli', 'Agustus',
    //         'September', 'Oktober', 'November', 'Desember'
    //     ];

    //     foreach ($months as $month) {
    //         self::create([
    //             'name' => $data['name'] . ' ' . $month,
    //             'price' => $data['price'],
    //             'classroom_id' => $data['classroom_id']
    //             // 'id_tipe' => $data['id_tipe'],
    //         ]);
    //     }
    // }

    
    // public function tipetransaksi(){
    //     return $this->belongsTo(TipeTransaksi::class, 'tipe_id', 'id_tipe');
    // }

    public function classrooms(){
        return $this->belongsTo(Classroom::class);
    }

    public function studentHasClass()
    {
        return $this->belongsTo(StudentHasClass::class, 'student_has_classes_id');
    }

    public function student()
    {
        return $this->hasOneThrough(
            Student::class,
            StudentHasClass::class,
            'id',                // Foreign key di StudentHasClass
            'id',                // Foreign key di Student
            'student_has_classes_id', // Lokal key di Tagihan
            'students_id'        // Foreign key di StudentHasClass
        );
    }

    public function pembayaran(){
        return $this->hasOne(Transaksi::class);
    }

}
