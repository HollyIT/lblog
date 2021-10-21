<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DefaultUsers::class);
        \App\Models\User::factory(10)->create();
        Tag::factory(60)->create();
        $allUsers = User::all();
        $allTags = Tag::all();
        Post::factory()->count(24)
            ->afterCreating(function (Post $post) use ($allTags, $allUsers) {
                $myTags = $allTags->random(rand(0, 5))->pluck('id');
                $post->tags()->sync($myTags);
                $post->user()->associate($allUsers->random(1)->first());
                $post->user()->associate(User::find(1));
                $image = Image::factory(1)->create();
                $post->image()->associate($image->first());
                $post->published_at = null;
                $post->published_at = Carbon::now()->subMinutes(rand(1, 60 * 24 * 30));
                $post->save();
            })
            ->create();
    }
}
