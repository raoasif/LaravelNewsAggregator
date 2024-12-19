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

        $this->userPrefrence->updateOrCreate(
            ['user_id' => $userId],
            [
                'preferred_categories' => json_encode($preferences['categories'] ?? []),
                'preferred_sources' => json_encode($preferences['sources'] ?? []),
                'preferred_authors' => json_encode($preferences['authors'] ?? []),
            ]
        );
    }

    public function getPreferences($userId){
        $preferences = UserPreference::where('user_id', $userId)->first();

        return [
            'categories' => json_decode($preferences->preferred_categories ?? '[]', true),
            'sources' => json_decode($preferences->preferred_sources ?? '[]', true),
            'authors' => json_decode($preferences->preferred_authors ?? '[]', true),
        ];
    }
}