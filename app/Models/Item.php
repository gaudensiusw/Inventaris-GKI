<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'slug',
        'entno',
        'kode_aset',
        'barcode',
        'name',
        'image',
        'category_id',
        'room_id',
        'merk_model',
        'spesifikasi',
        'quantity', // This is total_qty in new project
        'unit',
        'qty_baik',
        'qty_rusak_ringan',
        'qty_rusak_berat',
        'qty_tersedia',
        'qty_dipinjam',
        'qty_diperbaiki', // This was qty_perbaikan
        'qty_hilang',
        'qty_tidak_digunakan',
        'qty_pengadaan',
        'condition',
        'status',
        'price',
        'purchase_date',
        'description',
        'keterangan',
        'unit',
        'is_write_off'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
