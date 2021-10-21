<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class WhereInFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $values = collect(implode(',', $value))
            ->map(fn ($item) => trim($item))
            ->filter(fn ($item) => ! empty($item))
            ->toArray();
        if (! empty($values)) {
            $query->whereIn($property, $values);
        }
    }
}
