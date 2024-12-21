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
     *     path="/articles",
     *     summary="Get paginated list of articles",
     *     description="Retrieve a list of articles with optional filters and pagination.",
     *     tags={"Articles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term for articles",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter articles by category",
     *         required=false,
     *         @OA\Schema(type="string", example="Sports")
     *     ),
     *     @OA\Parameter(
     *         name="source",
     *         in="query",
     *         description="Filter articles by source",
     *         required=false,
     *         @OA\Schema(type="string", example="New York Times")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Filter articles by publication date (format: YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
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

     /**
     * @OA\Get(
     *     path="/articles/{id}",
     *     summary="Get article by ID",
     *     tags={"Articles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article details",
     *         @OA\JsonContent(ref="#/components/schemas/Article")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Article not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to fetch article",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Failed to fetch article")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
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

    /**
     * @OA\Get(
     *     path="/personalized-feed",
     *     summary="Get personalized feed for the authenticated user",
     *     tags={"Articles"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of articles per page (pagination)",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of personalized articles",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Article")),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="first", type="string", example="http://api.example.com/articles/personalized?page=1"),
     *                 @OA\Property(property="last", type="string", example="http://api.example.com/articles/personalized?page=10"),
     *                 @OA\Property(property="prev", type="string", nullable=true, example=null),
     *                 @OA\Property(property="next", type="string", nullable=true, example="http://api.example.com/articles/personalized?page=2")
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10),
     *                 @OA\Property(property="path", type="string", example="http://api.example.com/articles/personalized"),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="to", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=100)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to fetch personalized feed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Failed to fetch personalized feed")
     *         )
     *     )
     * )
     */

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
