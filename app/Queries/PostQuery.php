<?php

namespace App\Queries;

use App\Filters\PublishedFilter;
use App\Filters\WhereCaseInsensitive;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

class PostQuery extends QueryBuilder
{
    public function __construct($subject, ?Request $request = null)
    {
        parent::__construct($subject, $request);
        $this->setDefaults();
    }

    protected function setDefaults()
    {
        $filters = [
            AllowedFilter::custom('title', new WhereCaseInsensitive(), 'title'),
        ];

        if (Auth::user()?->role === 'admin') {
            $filters[] = AllowedFilter::trashed();
        }

        if (Auth::user()?->role) {
            $filters[] = AllowedFilter::custom('visibility', new PublishedFilter())->default('published');
        } else {
            /** @noinspection PhpUndefinedMethodInspection */
            $this->published();
        }
        $this->allowedFilters($filters);
        $this->defaultSort('-published_at');
        $this->allowedSorts(['created_at', 'updated_at', 'published_at']);
        $this->allowedIncludes(
            [
                'tags',
                AllowedInclude::count('tagsCount'),
                'user',
                'image',
            ]
        );
    }

    public static function start(): static
    {
        return static::for(Post::class);
    }
}
