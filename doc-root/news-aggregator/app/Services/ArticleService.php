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
    public function getArticles(array $filters)
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
        return $query->paginate(10);
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
}