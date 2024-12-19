<?php
namespace App\Services;

use App\Models\Article;

class ArticleService
{
    protected $article;

    public function __construct(Article $article) {
        $this->article = $article;
    }

    /**
     * Fetch articles with pagination and filtering options
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getArticles(array $filters, $perPage)
    {
        $query = $this->article->query();

        // Search by title or description
        if (isset($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
        }

        // Filter by category
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        // Filter by source
        if (isset($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        // Filter by date
        if (isset($filters['date'])) {
            $query->whereDate('published_date', $filters['date']);
        }

        // Paginate results (default 10 per page)
        return $query->paginate($perPage);
    }

    /**
     * Retrieve a single article by its ID
     *
     * @param int $id
     * @return Article|null
     */
    public function getArticleById($id)
    {
        return Article::find($id);
    }

    public function getPersonalizedFeed($preferences, $perPage = 10)
    {
        // If preferences are not found, return an empty collection or handle as needed
        if (!$preferences) {
            return collect([]);
        }

        // $preferences = $preferences->toArray();

        // Build the query to filter articles based on the user's preferences
        $query = Article::query();

        if (!empty($preferences['preferred_categories'])) {
            $query->whereIn('category', $preferences['categories']);
        }

        if (!empty($preferences['preferred_sources'])) {
            $query->whereIn('source', $preferences['sources']);
        }

        if (!empty($preferences['preferred_authors'])) {
            $query->whereIn('author', $preferences['authors']);
        }

        // Return the paginated result
        return $query->paginate($perPage);
    }
}