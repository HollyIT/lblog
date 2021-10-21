<?php

namespace App\Models;

use App\Traits\HasSlug;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;

/**
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $body_format
 * @property string $preparedContent
 * @property string $description
 * @property int $user_id
 * @property User $user
 * @property int $image_id
 * @property Image $image
 * @property string $slug
 * @property Carbon $published_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection | Tag[] $tags
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 */
class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasSlug;

    protected $fillable = [
        'title',
        'description',
        'body',
        'body_format',
        'published_at',
        'image_id',
        'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Post $post) {
            if (! $post->user_id) {
                $post->user_id = (int)Auth::id();
            }
        });

        static::deleted(function (Post $post) {
            if ($post->image && $post->image->posts->isEmpty()) {
                $post->image->delete();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    protected function slugField(): string
    {
        return 'slug';
    }


    protected function makeSlugOnSave(): bool
    {
        return true;
    }

    public function scopePublished($query)
    {
        $query->where(function ($query) {
            $query->whereNotNull('published_at');
            $query->where('published_at', '<=', \Illuminate\Support\Carbon::now());
        });
    }

    /**
     * @param Builder $query
     * @param User|null $user
     * @return void
     */
    public function scopeVisible($query, User $user = null)
    {
        $user = $user ?: Auth::user();
        $query->where(function (Builder $query) use ($user) {
            if (! $user->hasRole(['editor', 'admin'])) {
                $query->where('user_id', $user->id);
                $query->orWhere(function (Builder $query) {
                    $query->published();
                });
            }
        });
    }

    public function scopeUnpublished($query)
    {
        $user = Auth::user();
        if (! $user || ! $user->role) {
            return;
        }
        $query->where(function (Builder $query) use ($user) {
            if (! $user->hasRole(['editor', 'admin'])) {
                $query->where('user_id', $user->id);
                $query->whereNull('published_at');
            } else {
                $query->whereNull('published_at');
            }
        });
    }

    protected function slugFromAttribute(): string
    {
        return $this->title;
    }

    public function getRouteKeyName(): string
    {
        if (request()->expectsJson() || str_starts_with(request()->route()->getName(), 'admin.')) {
            return 'id';
        }

        return 'slug';
    }

    public function getPreparedContentAttribute()
    {
        $format = config('posts.formats.' . $this->body_format);
        if (! $format) {
            $format = config('posts.formats.' . config('posts.default_format'));
        }
        $formatter = app()->make($format['formatter'], ['text' => $this->body]);

        return $formatter;
    }
}
