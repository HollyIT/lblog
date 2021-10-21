<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class PublishedFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if ($value === 'all') {
            $query->visible();
        } elseif ($value === 'unpublished') {
            $query->unpublished();
        } else {
            $query->published();
        }
    }
}
