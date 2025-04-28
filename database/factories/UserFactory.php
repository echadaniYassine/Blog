<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'role' => $this->faker->randomElement(['blogger', 'admin']), 
            'phone' => $this->faker->phoneNumber(), 
            'bio' => $this->faker->text(100), 
            'address' => $this->faker->address(),
            'profile_image' => $this->faker->imageUrl(100, 100, 'people'), 
            'pdf' => 'pdfs/sample.pdf', 
        ];
    }
}
