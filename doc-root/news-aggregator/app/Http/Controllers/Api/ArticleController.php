<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\UserPreference;
use App\Services\ArticleService;
use App\Services\UserPreferenceService;
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

     /**
     * @OA\Get(
     *     path="/api/articles",
     *     summary="Get paginated list of articles",
     *     description="Retrieve a list of articles with optional filters and pagination.",
     *     tags={"Articles"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for articles",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of articles per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/Article")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
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

    public function getPersonalizedFeed(Request $request)
    {
        $userPrefrenceService = new UserPreferenceService(new UserPreference());

        $userId = auth()->id();  // Get the authenticated user's ID
        $userPrefrences = $userPrefrenceService->getPreferences($userId);
        $perPage = $request->query('per_page', 10);  // Default to 10 per page if not provided

        try {
            $articles = $this->articleService->getPersonalizedFeed($userPrefrences, $perPage);
            return ArticleResource::collection($articles);  // Returning paginated results wrapped in ArticleResource
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch personalized feed'], 500);
        }
    }

    // TO DO: Needs to add one endpoint to show available options for prefrences
}
