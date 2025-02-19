<?php

namespace App\Models;

use Http;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // public $timestamps = false;

    protected $fillable = [
        'order_id',
        'students_id',
        'id_tipe_transaksi',
        'bank',
        'name',
        'va_number',
        'amount',
        'status',
    ];

    protected $table = 'transaksi';

    public function student()
{
    return $this->belongsTo(Student::class, 'students_id');
}

    public function tagihan()
    {
        return $this->belongsTo(tagihan::class, 'id_tipe_transaksi');
    }

    // protected $fillable = ['name', 'id_tipe_transaksi', 'bank', 'amount', 'va_number', 'status'];

    public static function createWithApi($data)
    {
        $response = Http::timeout(20)->post(url('/api/spp/pay'), $data);

        if ($response->failed()) {
            throw new \Exception('Gagal menghubungi API');
        }

        $result = $response->json();

        if (!isset($result['data']['va'])) {
            throw new \Exception('Response API tidak valid');
        }

        return [
            'va' => $result['data']['va'],
        ];
    }
}
