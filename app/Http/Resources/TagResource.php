<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'tag' => $this->resource->tag,
            'slug' => $this->resource->slug,
            'posts' => PostResource::collection($this->whenLoaded('posts')),
            'posts_count' => $this->whenLoaded('posts_count', $this->resource->posts_count),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
