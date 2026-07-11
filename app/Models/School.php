<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_sekolah',
        'alamat',
        'latitude',
        'longitude',
    ];

    /**
     * Relasi: satu sekolah bisa punya banyak guru (users).
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}