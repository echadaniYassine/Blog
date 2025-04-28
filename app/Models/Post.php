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
        if ($this->images) {
            $images = json_decode($this->images, true);
            return count($images) > 0 ? asset('storage/' . $images[0]) : asset('images/default-post.jpg');
        }
        return $this->image ? asset('storage/' . $this->image) : asset('images/default-post.jpg');
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
