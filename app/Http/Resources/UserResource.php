<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    protected array $extra = [];

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'avatar' => $this->whenLoaded('avatar', new ImageResource($this->resource->avatar)),
            'email' => $this->when(Auth::user()?->can('viewEmail', $this->resource), $this->resource->email),
            'role' => $this->when(Auth::user()?->role === 'admin', $this->resource->role),
            'posts' => PostResource::collection($this->whenLoaded('posts')),
            'posts_count' => $this->when($this->resource->posts_count !== null, $this->resource->posts_count),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }

    public function with($request): array
    {
        return ! empty($this->extra) ?
            [
                'meta' => $this->extra,
            ] : [];
    }


    public function setMeta($key, $value): static
    {
        Arr::set($this->extra, $key, $value);

        return $this;
    }
}
