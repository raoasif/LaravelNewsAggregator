<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleService $articleService){
        $this->articleService = $articleService;
    }

    /**
     * Fetch articles with pagination and filters
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): ResourceCollection{
        $perPage = $request->query('per_page', 10);
        $filters = $request->only(['search', 'category', 'source', 'date']);

        try {
            // Fetch articles via service class
            $articles = $this->articleService->getArticles($filters, $perPage);
            // Return paginated articles wrapped in a resource
            return ArticleResource::collection($articles);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch articles'], 500);
        }
    }

    /**
     * Fetch a single article by its ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(?Article $article): ArticleResource{
        try {
            if (!$article) {
                return response()->json(['message' => 'Article not found'], 404);
            }

            // Return single article wrapped in a resource
            return new ArticleResource($article);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch article'], 500);
        }
    }
}
