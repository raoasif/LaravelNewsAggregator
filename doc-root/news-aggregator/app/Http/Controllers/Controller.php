<?php

namespace App\Http\Controllers;
/**
 * @OA\Info(
 *     title="News Aggregator API",
 *     version="1.0.0",
 *     description="API documentation for the News Aggregator application.",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * ),
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     in="header",
 *     description="Sanctum Bearer Token Authentication"
 * )
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", description="Unique identifier of the user"),
 *     @OA\Property(property="name", type="string", description="Name of the user"),
 *     @OA\Property(property="email", type="string", format="email", description="Email address of the user"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp")
 * ),
 *  * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     description="Article resource schema",
 *     @OA\Property(property="id", type="integer", description="ID of the article"),
 *     @OA\Property(property="source_name", type="string", description="Name of the article's source"),
 *     @OA\Property(property="title", type="string", description="Title of the article"),
 *     @OA\Property(property="description", type="string", description="Short description of the article"),
 *     @OA\Property(property="content", type="string", description="Full content of the article"),
 *     @OA\Property(property="url", type="string", description="URL to the article"),
 *     @OA\Property(property="url_to_image", type="string", description="URL to the article's image"),
 *     @OA\Property(property="category", type="string", description="Category of the article"),
 *     @OA\Property(property="author", type="string", description="Author of the article"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Publication date of the article"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 */
abstract class Controller
{
    //
}
