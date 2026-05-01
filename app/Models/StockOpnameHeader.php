<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameHeader extends Model
{
    use HasFactory;

    protected $table = 't_so_header';
    protected $primaryKey = 'id_so';

    protected $fillable = [
        'id_so',
        'tgl_audit',
        'id_user',
        'status',
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function details()
    {
        return $this->hasMany(StockOpnameDetail::class, 'id_so', 'id_so');
    }
}
