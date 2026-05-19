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
        'quantity',
        'unit',
        'qty_baik',
        'qty_rusak_ringan',
        'qty_rusak_berat',
        'qty_tersedia',
        'qty_dipinjam',
        'qty_diperbaiki',
        'qty_hilang',
        'qty_tidak_digunakan',
        'qty_pengadaan',
        'condition',
        'status',
        'purchase_date',
        'description',
        'keterangan',
        'is_write_off',
        'deleted_by',
        'delete_reason'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'is_write_off' => 'boolean',
    ];

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
