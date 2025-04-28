<?php

namespace Database\Factories;

// database/factories/PostFactory.php
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'caption' => $this->faker->sentence(), // Generate random caption
            'image' => $this->faker->imageUrl(640, 480, 'posts'), // Generate random image URL
            'pdf' => $this->faker->randomElement([null, $this->faker->filePath()]), // Generate random PDF or null
            'type' => $this->faker->randomElement(['news', 'book', 'cours']),
        ];
    }
}
