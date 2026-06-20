<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image'];

    protected $appends = ['image_url'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getRoomImage()
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
            if (file_exists(public_path('storage/rooms/' . $fileName))) {
                return asset('storage/rooms/' . $fileName);
            }
        }

        return null;
    }

    public function getImageUrlAttribute()
    {
        return $this->getRoomImage();
    }
}
