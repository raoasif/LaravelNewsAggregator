<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'content'       => $this->content,
            'author'        => $this->author,
            'url'           => $this->url,
            'image_url'     => $this->url_to_image,
            'source'        => $this->source_name,
            'category'      => $this->category,
            'section'       => $this->section,
            'published_date'=> $this->published_at
        ];
    }
}
