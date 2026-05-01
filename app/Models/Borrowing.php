<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $table = 'rents';
    protected $primaryKey = 'id_pinjam';

    protected $fillable = [
        'id_barang',
        'qty',
        'peminjam',
        'komisi_terkait',
        'tgl_pinjam',
        'tgl_kembali_rencana',
        'tgl_kembali_aktual',
        'id_user',
        'status_pinjam',
        'catatan'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_barang');
    }
}
