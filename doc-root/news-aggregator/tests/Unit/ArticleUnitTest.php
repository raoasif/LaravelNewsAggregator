<?php

namespace Tests\Unit;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_an_article()
    {
        $data = [
            'source_name' => 'TechCrunch',
            'source_api_id' => '12345',
            'title' => 'Breaking Tech News',
            'description' => 'The latest in tech.',
            'content' => 'Full article content here.',
            'url' => 'https://example.com',
            'url_to_image' => 'https://example.com/image.jpg',
            'category' => 'Tech',
            'section' => 'Gadgets',
            'author' => 'John Doe',
            'published_at' => now(),
        ];

        $article = Article::create($data);

        $this->assertNotNull($article);
        $this->assertDatabaseHas('articles', ['source_api_id' => '12345']);
    }

}
