<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'comment_id'];

    // Relationships
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Ensure unique like per post or comment
    public static function boot()
    {
        parent::boot();

        static::creating(function ($like) {
            if ($like->post_id) {
                if (self::where('user_id', $like->user_id)->where('post_id', $like->post_id)->exists()) {
                    throw new \Exception('User already liked this post');
                }
            }

            if ($like->comment_id) {
                if (self::where('user_id', $like->user_id)->where('comment_id', $like->comment_id)->exists()) {
                    throw new \Exception('User already liked this comment');
                }
            }
        });
    }
}
