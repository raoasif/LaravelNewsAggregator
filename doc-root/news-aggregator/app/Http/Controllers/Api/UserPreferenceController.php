<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetPreferencesRequest;
use App\Services\UserPreferenceService;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    protected $userPreferenceService;

    public function __construct(UserPreferenceService $userPreferenceService)
    {
        $this->userPreferenceService = $userPreferenceService;
    }

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

    public function getPreferences()
    {
        try {
            $preferences = $this->userPreferenceService->getPreferences(auth()->id());
            return response()->json($preferences, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch preferences'], 500);
        }
    }

    public function getPersonalizedFeed(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        try {
            $articles = $this->userPreferenceService->getPersonalizedFeed(auth()->id(), $perPage);
            return ArticleResource::collection($articles);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch personalized feed'], 500);
        }
    }
}
