<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'tracking_code', 'berat', 'harga', 'status_pembayaran', 'waktu', 'id_paket'
    ];

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'id_paket');
    }
}
