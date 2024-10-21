<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use News\Models\Article;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'url' => $this->faker->url(),
            'author' => $this->faker->name(),
            'category' => $this->faker->word(),
            'source' => 'Test Source',
            'published_at' => $this->faker->dateTime(),
        ];
    }
}
