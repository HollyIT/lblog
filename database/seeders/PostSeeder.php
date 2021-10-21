<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allUsers = User::all();
        if ($allUsers->isEmpty()) {
            User::factory()->count(5)->create();
            $allUsers = User::all();
        }

        $allTags = Tag::all();
        if ($allTags->isEmpty()) {
            Tag::factory()->count(30)->create();
            $allTags = Tag::all();
        }


        Post::factory()
            ->count(10)
            ->hasImage()
            ->afterCreating(function (Post $post) use ($allTags, $allUsers) {
                $myTags = $allTags->random(rand(0, 5))->pluck('id');
                $post->tags()->sync($myTags);
                $post->user()->associate($allUsers->random(1)->first());
                $post->user()->associate(User::find(1));
                $post->save();
            })
            ->create();
    }
}
