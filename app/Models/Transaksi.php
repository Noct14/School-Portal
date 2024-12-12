<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    public function tipeTransaksi()
    {
        return $this->belongsTo(TipeTransaksi::class, 'id_tipe_transaksi','id');
    }

    protected $fillable = [
        'name',
        'id_tipe_transaksi',
        'bank'
    ];
    
}
