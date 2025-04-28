<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Image.php model
class Image extends Model
{
    protected $fillable = ['post_id', 'image_path'];
    protected $casts = [
        'images' => 'array',  // This casts 'images' to an array
    ];
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
