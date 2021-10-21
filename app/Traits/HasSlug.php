<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * @mixin \Eloquent
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            if ($model->makeSlugOnSave() && ! $model->{$model->slugField()}) {
                $model->{$model->slugField()} = $model->makeUniqueSlug();
            }
        });
    }

    abstract protected function slugField(): string;

    abstract protected function makeSlugOnSave(): bool;

    abstract protected function slugFromAttribute(): string;
    public function makeUniqueSlug(): string
    {
        $slug = Str::slug($this->slugFromAttribute());
        $originalSlug = $slug;
        $i = 0;
        while ($this->slugExists($slug)) {
            $slug = $originalSlug.$i++;
        }

        return $slug;
    }

    protected function slugExists(string $slug): bool
    {
        $query = static::where($this->slugField(), $slug)
            ->withoutGlobalScopes();


        if ($this->exists) {
            $query->where($this->getKeyName(), '!=', $this->getKey());
        }

        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this), true)) {
            $query->withTrashed();
        }

        return $query->exists();
    }
}
