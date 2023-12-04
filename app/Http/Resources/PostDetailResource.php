<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category_name' => $this->category->name,
            'author' => $this->user->name,
            'created_at' => $this->created_at->format('Y-m-d h:i:s A'),
            'created_at_readable' => $this->created_at->diffForHumans(),
            'image_path' => $this->image ? asset('storage/media/' . $this->image->file_name) : null,
        ];
    }
}
