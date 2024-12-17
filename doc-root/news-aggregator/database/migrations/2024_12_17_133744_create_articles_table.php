<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Articles Table
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('source_name')->index(); // Indexed for optimization
            $table->string('source_api_id')->nullable(); // Unique ID from the source API
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('url'); // URL of the article
            $table->string('url_to_image')->nullable(); // URL to article image
            $table->string('category')->index(); // Indexed for filtering
            $table->string('section')->nullable();
            $table->string('author')->index(); // Indexed for filtering by author
            $table->timestamp('published_at')->index(); // Indexed for sorting/filtering
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
