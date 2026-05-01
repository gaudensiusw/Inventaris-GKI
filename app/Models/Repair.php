<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_barang',
        'qty',
        'id_vendor',
        'tgl_service',
        'jenis_perbaikan',
        'status',
        'estimated_completion',
        'biaya',
        'id_user',
        'keterangan'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_barang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
