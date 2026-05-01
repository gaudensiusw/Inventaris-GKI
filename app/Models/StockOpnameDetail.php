<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameDetail extends Model
{
    use HasFactory;

    protected $table = 't_so_detail';
    protected $primaryKey = 'id_so_detail';

    protected $fillable = [
        'id_so',
        'id_barang',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'keterangan'
    ];

    public function header()
    {
        return $this->belongsTo(StockOpnameHeader::class, 'id_so', 'id_so');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_barang', 'id')->withTrashed();
    }
}
