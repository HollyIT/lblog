<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class PostTagFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $tags = collect(explode(',', $value))
            ->map(fn ($tag) => trim($tag))
            ->filter(fn ($tag) => ! empty($tag))
            ->toArray();
        if (! empty($tags)) {
            $query->whereHas('tags', function (Builder $query) use ($tags) {
                $query->whereIn('tag', $tags);
            });
        }
    }
}
