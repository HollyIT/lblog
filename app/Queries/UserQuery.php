<?php

namespace App\Queries;

use App\Filters\WhereCaseInsensitive;
use App\Filters\WhereInFilter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class UserQuery extends QueryBuilder
{
    public function __construct($subject, ?Request $request = null)
    {
        parent::__construct($subject, $request);
        $this->setDefaults();
    }

    public static function start(): static
    {
        return static::for(User::class);
    }

    protected function setDefaults()
    {
        $filters = [
            AllowedFilter::custom('name', new WhereCaseInsensitive(), 'name'),
        ];

        if (Auth::user()?->role === 'admin') {
            $filters[] = AllowedFilter::custom('email', new WhereCaseInsensitive(), 'email');
            $filters[] = AllowedFilter::custom('roles', new WhereInFilter(), 'role');
        }

        $this->allowedFilters($filters)
            ->allowedSorts(['created_at', 'updated_at'])
            ->defaultSort('-updated_at')
            ->allowedIncludes(['posts', AllowedInclude::count('postsCount')]);
    }
}
