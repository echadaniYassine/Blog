<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'type', 'caption'
    ];

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

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function pdf()
    {
        return $this->hasOne(Pdf::class);
    }

    // Accessor for primary image
    public function getImageUrlAttribute()
    {
        return $this->images()->first() ? asset('storage/' . $this->images()->first()->path) : asset('images/placeholder.jpg');
    }

    // Accessor for all image URLs
    public function getImagesUrlsAttribute()
    {
        return $this->images->map(fn($image) => asset('storage/' . $image->path))->toArray();
    }

    // Accessor for PDF URL
    public function getPdfUrlAttribute()
    {
        return $this->pdf ? asset('storage/' . $this->pdf->path) : null;
    }
}
