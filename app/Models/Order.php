<?php
// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'service_id',
        'berat',
        'total_harga',
        'status',
        'perkiraan_selesai',
        'catatan'
    ];

    protected $casts = [
        'perkiraan_selesai' => 'datetime',
        'berat' => 'decimal:2',
        'total_harga' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->order_number = 'LND' . date('Ymd') . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }
}