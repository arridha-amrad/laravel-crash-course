<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $categories = [];
        foreach ($this->categories as $category) {
            array_push($categories, $category["name"]);
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'coverUrl' => $this->cover_url,
            'stocks' => $this->stocks,
            'year' => $this->year,
            'categories' => $categories
        ];
    }
}
