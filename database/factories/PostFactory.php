<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(rand(5, 8), true),
            'description' => $this->faker->paragraph,
            'body' => $this->faker->paragraphs(rand(2, 4), true),
            'published_at' => Carbon::now(),
        ];
    }
}
