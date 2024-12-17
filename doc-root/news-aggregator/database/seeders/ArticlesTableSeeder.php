<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Generate 50 fake articles
        for ($i = 0; $i < 50; $i++) {
            DB::table('articles')->insert([
                'title' => $faker->sentence(6, true),
                'description' => $faker->paragraph(3, true),
                'source_name' => $faker->randomElement(['New York Times', 'The Guardian', 'NewsAPI']),
                'author' => $faker->name,
                'url' => $faker->url,
                'url_to_image' => $faker->imageUrl(640, 480, 'news', true),
                'published_at' => $faker->dateTimeBetween('-1 month', 'now'),
                'category' => $faker->randomElement(['Business', 'Technology', 'Food', 'Sports', 'Politics', 'Health']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
