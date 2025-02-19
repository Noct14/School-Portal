<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TipeTransaksi extends Model
{
    use HasFactory;

    protected $table = 'tipe_transaksi';

    protected $guarded = [];

    protected $fillable = [
        'name',
        'price',
        'is_monthly'
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate UUID ketika model baru dibuat
        static::creating(function ($model) {
            if (empty($model->id_tipe)) {
                $model->id_tipe = (string) Str::uuid(); // Menghasilkan UUID secara otomatis
            }
        });
    }
    
}
