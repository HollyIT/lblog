<?php

namespace App\Http\Resources;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property Image $resource
 */
class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $formats = [];
        foreach ($this->resource->formats as $format) {
            $formats[$format->format] = Storage::disk($format->disk)->url($format->path);
        }

        return [
            'id' => $this->resource->id,
            'url' => $this->resource->url,
            'formats' => $formats,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
