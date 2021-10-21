<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class WhereCaseInsensitive implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where($property, 'LIKE', $value);
    }
}
