<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Share;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 2 admins
        User::factory()->create([
            'name' => 'Admin One',
            'email' => 'admin1@example.com',
            'role' => 'admin', // Explicitly set the role as admin
        ]);

        User::factory()->create([
            'name' => 'Admin Two',
            'email' => 'admin2@example.com',
            'role' => 'admin', // Explicitly set the role as admin
        ]);

        // Create 5 bloggers
        User::factory(5)->create([
            'role' => 'blogger', // Explicitly set the role as blogger for all 5
        ]);

        // Create 10 posts, likes, comments, and shares
        Post::factory(10)->create()->each(function ($post) {
            // Create likes for each post
            Like::factory(5)->create(['post_id' => $post->id]);

            // Create comments for each post
            Comment::factory(3)->create(['post_id' => $post->id]);

            // Create shares for each post
            Share::factory(2)->create(['post_id' => $post->id]);
        });
    }
}
