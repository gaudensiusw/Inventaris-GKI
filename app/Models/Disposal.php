<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposal extends Model
{
    use HasFactory;

    protected $table = 't_penghapusan';
    protected $primaryKey = 'id_hapus';

    protected $fillable = [
        'id_barang',
        'tgl_hapus',
        'id_user',
        'alasan',
        'keterangan'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
