<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'driver_id',
        'status_pengiriman',
        'current_latitude',
        'current_longitude',
        'tracking_active',
        'delivered_at',
        'catatan_driver',
    ];

    protected $casts = [
        'tracking_active'   => 'boolean',
        'delivered_at'      => 'datetime',
        'current_latitude'  => 'decimal:8',
        'current_longitude' => 'decimal:8',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function trackingLogs()
    {
        return $this->hasMany(TrackingLog::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status_pengiriman) {
            'menunggu'         => 'Menunggu',
            'dalam_perjalanan' => 'Dalam Perjalanan',
            'sampai_sekolah'   => 'Sampai di Sekolah',
            'selesai'          => 'Selesai',
            default            => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status_pengiriman) {
            'menunggu'         => 'warning',
            'dalam_perjalanan' => 'primary',
            'sampai_sekolah'   => 'info',
            'selesai'          => 'success',
            default            => 'secondary',
        };
    }
}
