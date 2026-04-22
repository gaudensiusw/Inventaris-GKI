<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price' => 'decimal:2',
        'purchase_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function rents()
    {
        return $this->hasMany(Rent::class);
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }

    public function activeRepair()
    {
        return $this->hasOne(Repair::class)->where('status', 'Dalam Perbaikan')->latest();
    }

    /**
     * Generate inventory code like INV-001
     */
    public function getInvCodeAttribute()
    {
        return 'INV-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get total value (price * quantity)
     */
    public function getTotalValueAttribute()
    {
        return $this->price * $this->quantity;
    }
}
