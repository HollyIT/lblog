<?php

namespace App\Actions;

use App\Models\Image;
use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Str;

class SavePostAction
{
    protected Collection $attributes;
    protected ?Post $model;

    public function __construct($attributes, ?Post $model = null)
    {
        $this->attributes = collect($attributes);
        $this->model = $model ?: new Post();
    }

    public static function from($attributes, ?Post $model = null): Post
    {
        $attributes = $attributes instanceof Request ? $attributes->all() : $attributes;
        $instance = new static($attributes, $model);

        return $instance->save();
    }

    public function save(): Post
    {
        $interests = ['title', 'body', 'body_format', 'description', 'published'];
        if (Auth::user()->can('assignOwner', $this->model)) {
            $interests[] = 'user';
        }
        $attributes = $this->attributes->only($interests)->toArray();
        if (isset($attributes['user'])) {
            $attributes['user_id'] = $attributes['user'];
        }
        if ($this->attributes->has('published')) {
            if (is_bool($this->attributes->get('published'))) {
                $attributes['published_at'] = $this->attributes->get('published') ? Carbon::now() : null;
            } elseif ($date = $this->attributes->get('published')) {
                $attributes['published_at'] = $date;
            }
        }

        $this->model->fill($attributes);
        if ($this->attributes->get('image_remove')) {
            $this->model->image_id = null;
        }

        if ($this->attributes->get('image') instanceof UploadedFile) {
            $this->attachImage($this->attributes->get('image'));
        } elseif ($this->attributes->get('image')) {
            $this->model->image_id = $this->attributes->get('image');
        }
        $this->model->save();
        if ($this->attributes->has('tags')) {
            $this->model->tags()->sync($this->transformTags($this->attributes->get('tags')));
        }


        return $this->model;
    }

    protected function attachImage(UploadedFile $upload): static
    {
        $image = Image::createFromUpload($upload);
        $this->model->image_id = $image->id;

        return $this;
    }

    protected function transformTags($tags): array
    {
        if (is_string($tags)) {
            $tags = collect(explode(',', $tags))
                ->map(fn ($tag) => trim($tag))
                ->filter(fn ($tag) => ! empty($tag))
                ->map(fn ($tag) => ['tag' => $tag]);
        }
        $new = [];
        $existing = [];
        foreach ($tags as $tag) {
            if (isset($tag['tag'])) {
                $new[Str::slug($tag['tag'])] = $tag['tag'];
            } else {
                $existing[$tag['id']] = $tag['id'];
            }
        }

        foreach ($new as $newItem) {
            $model = Tag::findOrCreateByTag($newItem);
            $existing[$model->id] = $model->id;
        }


        return $existing;
    }
}
