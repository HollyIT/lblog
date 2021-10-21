<?php

use App\Actions\SavePostAction;
use App\Models\Post;
use Carbon\Carbon;
use Database\Seeders\PostSeeder;
use Illuminate\Testing\Fluent\AssertableJson;

test('it lists posts', function () {
    $this->seed(PostSeeder::class);
    $this->asAdmin();
    $response = $this->getJson('api/posts?include=tags,user');

    $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data', 10)
                ->whereType('data.0.tags', 'array')
                ->whereType('data.0.tags.0.tag', 'string')
                ->whereType('data.0.user', 'array')
                ->whereType('data.0.user.name', 'string')
                ->etc();
        });
});

test('it filters posts based on visibility', function () {
    Post::create([
        'title' => 'Unpublished title',
        'description' => 'Post description',
        'body' => 'Post body',
        'body_format' => 'default',
    ]);

    Post::create([
        'title' => 'Published title',
        'description' => 'Post description',
        'body' => 'Post body',
        'body_format' => 'default',
        'published_at' => Carbon::now()->subMinutes(5),
    ]);

    $this->asRegularUser();

    $response = $this->getJson('api/posts');
    $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data', 1)
                ->where('data.0.title', 'Published title')
                ->etc();
        });


    $this->asAdmin();
    $response = $this->getJson('api/posts?filter[visibility]=all');
    $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data', 2)
                ->etc();
        });


    $response = $this->getJson('api/posts?filter[visibility]=unpublished');
    $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data', 1)
                ->where('data.0.title', 'Unpublished title')
                ->etc();
        });

    $response = $this->getJson('api/posts?filter[visibility]=published');
    $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data', 1)
                ->where('data.0.title', 'Published title')
                ->etc();
        });
});

test('it creates a new post via the api', function () {
    $this->asAdmin();
    $response = $this->postJson('api/posts', [
        'title' => 'Post title',
        'description' => 'Post description',
        'body' => 'Post body',
        'body_format' => 'default',
        'published' => true,
    ]);
    $response->assertStatus(201)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data')
                ->where('data.title', 'Post title')
                ->where('data.description', 'Post description')
                ->where('data.body', 'Post body')
                ->where('data.published_at', function ($val) {
                    return strtotime($val) !== false;
                })
                ->etc();
        });
});

test('it creates a post via the api with tags', function () {
    $this->asAdmin();
    $response = $this->postJson('api/posts', [
        'title' => 'Post title',
        'description' => 'Post description',
        'body' => 'Post body',
        'body_format' => 'default',
        'published' => '2020-02-01',
        'tags' => [
            [
                'tag' => 'Test',
            ],
            [
                'tag' => 'Test tag 2',
            ],
        ],
    ]);

    $response->assertStatus(201)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data')
                ->where('data.title', 'Post title')
                ->where('data.description', 'Post description')
                ->where('data.body', 'Post body')
                ->where('data.published_at', function ($val) {
                    return strtotime($val) !== false;
                })
                ->whereType('data.tags', 'array')
                ->count('data.tags', 2)
                ->etc();
        });
});

test('it updates posts via the api', function () {
    $this->asAdmin();
    $data = [
        'title' => 'Post title',
        'description' => 'Post description',
        'body' => 'Post body',
        'body_format' => 'default',
        'published' => '2020-02-01',
        'tags' => [
            [
                'tag' => 'Test',
            ],
            [
                'tag' => 'Test tag 2',
            ],
        ],
    ];
    $post = SavePostAction::from($data);

    $data['tags'] = [
        [
            'tag' => 'Test 3',
        ],
    ];

    $response = $this->postJson('api/posts/'.$post->id, $data);
    $response->assertStatus(200);
    $post->refresh();
    $this->assertCount(1, $post->tags);
    $this->assertEquals('Test 3', $post->tags->first()->tag);

    $data['tags'] = [];
    $response = $this->postJson('api/posts/'.$post->id, $data);
    $response->assertStatus(200);
    $post->refresh();
    $this->assertCount(0, $post->tags);
});
