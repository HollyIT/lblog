<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $tag
 * @property string $slug
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection | Post[] $posts
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 */
class Tag extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'tag',
        'slug',
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    protected function slugField(): string
    {
        return 'slug';
    }

    protected function makeSlugOnSave(): bool
    {
        return true;
    }

    public static function findOrCreateByTag($tag): static
    {
        $existing = static::query()->where('tag', 'LIKE', trim($tag))->get();
        if ($existing->isNotEmpty()) {
            return $existing->first();
        }

        return static::create([
            'tag' => trim($tag),
        ]);
    }

    protected function slugFromAttribute(): string
    {
        return $this->tag;
    }

    public function getRouteKeyName(): string
    {
        if (request()->expectsJson() || str_starts_with(request()->route()->getName(), 'admin.')) {
            return 'id';
        }

        return 'slug';
    }
}
