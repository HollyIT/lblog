<?php

use App\Models\Image;
use App\Models\ImageFormat;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;

test('it creates images', function () {
    $this->asAdmin();
    $response = $this->postJson('api/images', [
        'image' => UploadedFile::fake()->image('test.jpg'),
    ]);

    $response->assertStatus(201)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data')
                ->whereType('data.url', 'string')
                ->etc();
        });



    $image = Image::first();

    Storage::disk($image->disk)->assertExists($image->path);
    foreach ($image->formats as $format) {
        Storage::disk($format->disk)->assertExists($format->path);
    }
});

test('it updates images', function () {
    $this->asAdmin();
    $this->postJson('api/images', [
        'image' => UploadedFile::fake()->image('test.jpg'),
    ]);
    $image = Image::first();
    $this->postJson('api/images/' . $image->id, [
        'image' => UploadedFile::fake()->image('test.jpg'),
    ]);
    $updatedImage = Image::first();
    $this->assertNotEquals($image->path, $updatedImage->path);

    Storage::disk($image->disk)->assertMissing($image->path);
    foreach ($image->formats as $format) {
        Storage::disk($format->disk)->assertMissing($format->path);
    }
    Storage::disk($updatedImage->disk)->assertExists($updatedImage->path);
    foreach ($updatedImage->formats as $format) {
        Storage::disk($format->disk)->assertExists($format->path);
    }
});

test('it deletes images and all formats', function () {
    $this->asAdmin();
    $this->postJson('api/images', [
        'image' => UploadedFile::fake()->image('test.jpg'),
    ]);
    $image = Image::first();
    $response = $this->json('delete', 'api/images/' . $image->id);
    $response->assertStatus(204);
    $this->assertModelMissing($image);
    Storage::disk($image->disk)->assertMissing($image->path);

    $this->assertEmpty(ImageFormat::all());
});
