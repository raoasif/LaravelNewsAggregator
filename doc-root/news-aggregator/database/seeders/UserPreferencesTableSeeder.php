<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UserPreferencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch all user IDs
        $userIds = DB::table('users')->pluck('id');

        foreach ($userIds as $userId) {
            DB::table('user_preferences')->insert([
                'user_id' => $userId,
                'preferred_sources' => json_encode($faker->randomElements([
                    'New York Times', 'The Guardian', 'BBC News', 'NPR', 'CNN', 'Reuters'
                ], rand(1, 3))),
                'preferred_categories' => json_encode($faker->randomElements([
                    'Technology', 'Business', 'Entertainment', 'Sports', 'Food', 'Health'
                ], rand(1, 3))),
                'preferred_authors' => json_encode($faker->randomElements([
                    'John Doe', 'Jane Smith', 'Alice Brown', 'Robert White', 'Priya Patel'
                ], rand(1, 3))),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
