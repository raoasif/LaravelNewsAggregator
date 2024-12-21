<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetPreferencesRequest;
use App\Services\UserPreferenceService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="User Preferences",
 *     description="APIs for user preferences"
 * )
 */
class UserPreferenceController extends Controller
{
    protected $userPreferenceService;

    public function __construct(UserPreferenceService $userPreferenceService)
    {
        $this->userPreferenceService = $userPreferenceService;
    }

    /**
     * @OA\Post(
     *     path="/preferences",
     *     summary="Set user preferences",
     *     tags={"User Preferences"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             example={
     *                 "categories": {"technology", "science"},
     *                 "authors": {"John Doe", "Jane Smith"},
     *                 "sources": {"BBC", "CNN"}
     *             },
     *             @OA\Property(property="categories", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="authors", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="sources", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Preferences saved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Preferences saved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to save preferences",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Failed to save preferences"),
     *             @OA\Property(property="error", type="string", example="An unexpected error occurred")
     *         )
     *     )
     * )
     */

    public function setPreferences(SetPreferencesRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->userPreferenceService->setPreferences(auth()->id(), $validated);
            return response()->json(['message' => 'Preferences saved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to save preferences', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/preferences",
     *     summary="Get user preferences",
     *     tags={"User Preferences"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User preferences retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             example={
     *                 "categories": {"technology", "science"},
     *                 "authors": {"John Doe", "Jane Smith"},
     *                 "sources": {"BBC", "CNN"}
     *             },
     *             @OA\Property(property="categories", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="authors", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="sources", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to fetch preferences",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Failed to fetch preferences")
     *         )
     *     )
     * )
     */
    public function getPreferences()
    {
        try {
            $preferences = $this->userPreferenceService->getPreferences(auth()->id());
            return response()->json($preferences, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch preferences'], 500);
        }
    }
}
