<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $images = fake()->randomElement([
            ['preview.jpg', 'full.jpeg'],
            ['preview_2.jpg', 'full_2.jpeg'],
        ]);

        return [
            'name' => fake()->realText(60),
            'short_desc' => fake()->optional()->realText(150),
            'body' => fake()->realText(800),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'preview_image' => $images[0],
            'full_image' => $images[1],
        ];
    }
}
