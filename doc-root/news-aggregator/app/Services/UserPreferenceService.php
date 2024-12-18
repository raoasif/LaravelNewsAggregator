<?php
namespace App\Services;

use App\Models\UserPreference;

class UserPreferenceService
{
    protected $userPrefrence;

    public function __construct(UserPreference $userPrefrence) {
        $this->userPrefrence = $userPrefrence;
    }

    public function setPreferences($userId, array $preferences){
        UserPreference::updateOrCreate(
            ['user_id' => $userId],
            [
                'categories' => json_encode($preferences['categories'] ?? []),
                'sources' => json_encode($preferences['sources'] ?? []),
                'authors' => json_encode($preferences['authors'] ?? []),
            ]
        );
    }

    public function getPreferences($userId){
        $preferences = UserPreference::where('user_id', $userId)->first();

        return [
            'categories' => json_decode($preferences->categories ?? '[]', true),
            'sources' => json_decode($preferences->sources ?? '[]', true),
            'authors' => json_decode($preferences->authors ?? '[]', true),
        ];
    }
}