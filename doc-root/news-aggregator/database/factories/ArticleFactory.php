<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'source_name' => $this->faker->randomElement(['New York Times', 'The Guardian', 'NewsAPI']),
            'source_api_id' => $this->faker->uuid,
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'content' => $this->faker->text,
            'url' => $this->faker->url,
            'url_to_image' => $this->faker->imageUrl,
            'category' => $this->faker->randomElement(['Business', 'Technology', 'Food', 'Sports', 'Politics', 'Health']),
            'section' => $this->faker->word,
            'author' => $this->faker->name,
            'published_at' => $this->faker->dateTimeThisYear,
        ];
    }

}
