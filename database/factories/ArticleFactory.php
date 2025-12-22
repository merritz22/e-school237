<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
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
        $title = fake()->sentence(rand(3, 8));
        $content = fake()->paragraphs(rand(5, 15), true);
        $status = fake()->randomElement(['draft', 'published', 'published', 'published', 'archived']); // Plus de publiés
        
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $content,
            'excerpt' => fake()->optional()->text(200),
            'featured_image' => fake()->optional(0.3)->imageUrl(800, 400, 'education'),
            'author_id' => User::whereIn('role', ['admin', 'moderator', 'author'])->inRandomOrder()->first()?->id ?? User::factory(),
            'status' => $status,
            'views_count' => fake()->numberBetween(0, 1000),
            'published_at' => $status === 'published' ? fake()->dateTimeBetween('-6 months', 'now') : null,
        ];
    }

    /**
     * Indicate that the article is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    /**
     * Indicate that the article is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the article is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }

    /**
     * Create a popular article with high views.
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'views_count' => fake()->numberBetween(500, 2000),
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-2 months', 'now'),
        ]);
    }
}