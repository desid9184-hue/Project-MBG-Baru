<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'lauk',
        'sayur',
        'buah',
        'sambal',
        'kalori',
        'protein',
        'lemak',
        'karbohidrat',
        'keterangan',
    ];

    protected $casts = [
        'kalori'      => 'decimal:2',
        'protein'     => 'decimal:2',
        'lemak'       => 'decimal:2',
        'karbohidrat' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getTotalKaloriLabelAttribute(): string
    {
        return number_format($this->kalori, 0) . ' kkal';
    }
}
