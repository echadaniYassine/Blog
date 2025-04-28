<?php


namespace Database\Factories;

// database/factories/ShareFactory.php
use App\Models\Share;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShareFactory extends Factory
{
    protected $model = Share::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
            'platform' => $this->faker->randomElement(['whatsapp', 'facebook', 'twitter']),
        ];
    }
}
