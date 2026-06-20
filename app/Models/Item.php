<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['image_url'];

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

    public function getItemImage()
    {
        // First check if a custom uploaded image path is saved in the database
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        if (empty($this->name)) {
            return null;
        }

        // Replace spaces with underscores
        $formattedName = str_replace(' ', '_', $this->name);

        // Extensions to check
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'JPG', 'JPEG', 'PNG', 'WEBP'];

        foreach ($extensions as $ext) {
            $fileName = $formattedName . '.' . $ext;
            if (file_exists(public_path('storage/items/' . $fileName))) {
                return asset('storage/items/' . $fileName);
            }
        }

        return null;
    }

    public function getImageUrlAttribute()
    {
        return $this->getItemImage();
    }
}
