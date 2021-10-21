<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SampleHtmlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $image = new Image();
        $image->image = __DIR__ . '/../../assets/sampleData/post/SampleHtml/image.jpg';
        $image->save();
        Post::create([
            'title' => 'Sample html',
            'description' => 'Some sample html to check things out for developing themes, etc.',
            'user_id' => User::first()->id,
            'image_id' => $image->id,
            'body' => file_get_contents(__DIR__ . '/../../assets/sampleData/post/SampleHtml/body.html'),
            'body_format' => 'raw',
            'published_at' => Carbon::now(),
        ]);
    }
}
