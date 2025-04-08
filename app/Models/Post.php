<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'image', 'caption', 'pdf', 'images'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }

    // Image upload path
    public function getImageUrlAttribute()
    {
        // If it's a news post with multiple images, return the first one
        if ($this->type == 'news' && $this->images && is_array($this->images) && count($this->images) > 0) {
            return asset('storage/' . $this->images[0]);
        }

        // For book and course posts with a single image
        return $this->image ? asset('storage/' . $this->image) : asset('images/placeholder.jpg');
    }

    // PDF upload path
    public function getPdfUrlAttribute()
    {
        return $this->pdf ? asset('storage/' . $this->pdf) : null;
    }

    // Decode images from JSON
    public function getImagesAttribute($value)
    {
        return json_decode($value, true);
    }
}
