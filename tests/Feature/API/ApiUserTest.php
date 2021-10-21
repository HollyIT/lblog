<?php

namespace Tests\Feature\API;

use App\Models\Image;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;

test('it lists users via the api', function () {
    $this->asAdmin();
    $this->seed(UserSeeder::class);

    $response = $this->json('get', 'api/users?include=postsCount');
    $response->assertStatus(200);
    $response->assertJsonCount(10, 'data');
    $response->assertSeeText('posts_count');
});

test('it creates a user via the api', function () {
    $this->asAdmin();
    $response = $this->json('post', 'api/users', [
        'name' => 'new user',
        'email' => 'new@example.com',
        'password' => 'secret',
        'password_confirmation' => 'secret',
    ]);
    $response->assertStatus(201)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data')
                ->where('data.name', 'new user')
                ->where('data.email', 'new@example.com')
                ->etc();
        });
});

test('it updates a user via the api', function () {
    $this->asAdmin();
    $user = User::create(
        [
            'name' => 'new user',
            'email' => 'new@example.com',
            'password' => 'secret',
        ]
    );
    $response = $this->json('post', 'api/users/'.$user->id, [
        'name' => 'new user updated',
        'email' => 'newupdated@example.com',
        'role' => 'admin',
    ]);
    $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data')
                ->where('data.name', 'new user updated')
                ->where('data.email', 'newupdated@example.com')
                ->etc();
        });
    $user->refresh();
    $this->assertEquals('admin', $user->role);
});

test('it attaches user avatars via the api', function () {
    $this->asAdmin();
    $response = $this->json('post', 'api/users', [
        'name' => 'new user',
        'email' => 'new@example.com',
        'password' => 'secret',
        'password_confirmation' => 'secret',
        'avatar' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
    ]);

    $response->assertStatus(201)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data')
                ->whereType('data.avatar', 'array')
                ->whereType('data.avatar.url', 'string')
                ->etc();
        });
    $data = json_decode($response->getContent());
    $user = User::find($data->data->id);
    $this->assertInstanceOf(Image::class, $user->avatar);
    Storage::disk($user->avatar->disk)->assertExists($user->avatar->path);
    Storage::disk($user->avatar->thumb_disk)->assertExists($user->avatar->thumb_path);
});

test('it updates avatars via the api', function () {
    $this->asAdmin();
    $response = $this->json('post', 'api/users', [
        'name' => 'new user',
        'email' => 'new@example.com',
        'password' => 'secret',
        'password_confirmation' => 'secret',
        'avatar' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
    ]);
    $data = json_decode($response->getContent());
    $original = User::find($data->data->id);
    $this->assertModelExists($original->avatar);
    $response = $this->json('post', 'api/users/'.$data->data->id, [
        'name' => 'new user',
        'email' => 'new@example.com',
        'password' => 'secret',
        'password_confirmation' => 'secret',
        'avatar' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
    ]);
    $data = json_decode($response->getContent());
    $new = User::find($data->data->id);
    $this->assertModelExists($new->avatar);
    Storage::disk($new->avatar->disk)->assertExists($new->avatar->path);
    Storage::disk($new->avatar->thumb_disk)->assertExists($new->avatar->thumb_path);
    $this->assertModelMissing($original->avatar);
});
