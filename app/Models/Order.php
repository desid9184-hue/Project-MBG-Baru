<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'tanggal_pengiriman',
        'jumlah_porsi_besar',
        'jumlah_porsi_kecil',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pengiriman' => 'date',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function menu()
    {
        return $this->hasOne(Menu::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function getTotalPorsiAttribute(): int
    {
        return $this->jumlah_porsi_besar + $this->jumlah_porsi_kecil;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'         => 'Menunggu Konfirmasi',
            'diterima'        => 'Diterima Asisten',
            'diproses'        => 'Sedang Diproses',
            'dikemas'         => 'Sedang Dikemas',
            'siap_dikirim'    => 'Siap Dikirim',
            'dalam_perjalanan'=> 'Dalam Perjalanan',
            'sampai_sekolah'  => 'Sampai di Sekolah',
            'selesai'         => 'Selesai',
            'dibatalkan'      => 'Dibatalkan',
            default           => 'Unknown',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'         => 'badge-warning',
            'diterima'        => 'badge-info',
            'diproses'        => 'badge-primary',
            'dikemas'         => 'badge-primary',
            'siap_dikirim'    => 'badge-success',
            'dalam_perjalanan'=> 'badge-warning',
            'sampai_sekolah'  => 'badge-info',
            'selesai'         => 'badge-success',
            'dibatalkan'      => 'badge-danger',
            default           => 'badge-secondary',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'         => 'warning',
            'diterima'        => 'info',
            'diproses'        => 'primary',
            'dikemas'         => 'primary',
            'siap_dikirim'    => 'success',
            'dalam_perjalanan'=> 'warning',
            'sampai_sekolah'  => 'info',
            'selesai'         => 'success',
            'dibatalkan'      => 'danger',
            default           => 'secondary',
        };
    }
}
