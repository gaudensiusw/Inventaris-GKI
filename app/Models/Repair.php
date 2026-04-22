<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'repair_date' => 'date',
        'estimated_completion' => 'date',
        'actual_completion' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
