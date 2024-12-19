<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class AggregateNewsArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:aggregate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store articles from external news APIs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->fetchNewsApiArticles();
        $this->fetchGuardianArticles();
        $this->fetchNyTimesArticles();

        $this->info('Articles fetched and stored successfully.');
    }

    private function fetchNewsApiArticles()
    {
        $response = Http::get('https://newsapi.org/v2/top-headlines', [
            'apiKey' => env('NEWSAPI_KEY'),
            'country' => 'us',
        ]);

        if ($response->successful()) {
            $this->storeArticles($response->json()['articles'], 'NewsAPI');
        }
    }

    private function fetchGuardianArticles()
    {
        $response = Http::get('https://content.guardianapis.com/search', [
            'api-key' => env('GUARDIAN_API_KEY'),
            'show-fields' => 'headline,byline,body',
        ]);

        if ($response->successful()) {
            $this->storeArticles($response->json()['response']['results'], 'The Guardian');
        } else {
            logger()->error('Failed to fetch articles from The Guardian', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }

    private function fetchNyTimesArticles()
    {
        $response = Http::get('https://api.nytimes.com/svc/topstories/v2/home.json', [
            'api-key' => env('NYTIMES_API_KEY'),
        ]);

        if ($response->successful()) {
            $this->storeArticles($response->json()['results'], 'New York Times');
        }
    }

    private function storeArticles($articles, $source)
    {
        foreach ($articles as $article) {
            // Map fields based on the API source
        $mappedArticle = $this->mapArticleFields($article, $source);

        // Use updateOrCreate to insert or update the record
        Article::updateOrCreate(
            ['url' => $mappedArticle['url']],
            [
                'title' => $mappedArticle['title'],
                'author' => $mappedArticle['author'] ?? 'Unknown',
                'source_name' => $source,
                'source_api_id' => $mappedArticle['source_api_id'] ?? null,
                'description' => $mappedArticle['description'] ?? '',
                'content' => $mappedArticle['content'] ?? '',
                'url_to_image' => $mappedArticle['url_to_image'] ?? null,
                'category' => $mappedArticle['category'] ?? 'General',
                'section' => $mappedArticle['section'] ?? null,
                'published_at' => $mappedArticle['published_at'] ?? now(),
                ]
            );
        }
    }

    private function mapArticleFields(array $article, string $source): array
    {
        switch ($source) {
            case 'NewsAPI':
                return [
                    'source_api_id' => $article['source']['id'] ?? null,
                    'title' => $article['title'],
                    'author' => $article['author'] ?? null,
                    'description' => $article['description'] ?? null,
                    'content' => $article['content'] ?? null,
                    'url' => $article['url'],
                    'url_to_image' => $article['urlToImage'] ?? null,
                    'category' => $article['category'] ?? 'Unknown',
                    'published_at' => $article['publishedAt'] ?? now(),
                    'section' => $article['source']['name'] ?? null,
                ];
            case 'The Guardian':
                return [
                    'source_api_id' => $article['id'] ?? null,
                    'title' => $article['webTitle'],
                    'author' => $article['fields']['byline'] ?? null,
                    'description' => $article['fields']['trailText'] ?? null,
                    'content' => $article['fields']['body'] ?? null,
                    'url' => $article['webUrl'],
                    'url_to_image' => $article['fields']['thumbnail'] ?? null,
                    'category' => $article['type'] ?? 'Unknown',
                    'published_at' => $article['webPublicationDate'] ?? now(),
                    'section' => $article['sectionName'] ?? null,
                ];
            case 'New York Times':
                return [
                    'source_api_id' => $article['uri'] ?? null,
                    'title' => $article['title'] ?? '',
                    'author' => $article['byline'],
                    'description' => $article['abstract'] ?? '',
                    'content' => $article['lead_paragraph'] ?? null,
                    'url' => $article['url'],
                    'url_to_image' => $article['multimedia'][0]['url'] ?? null,
                    'category' => $article['subsection'] ?? 'Unknown',
                    'section' => $article['section'] ?? null,
                    'published_at' => $article['published_date'] ?? now()
                ];
            default:
                return [];
        }
    }
}
