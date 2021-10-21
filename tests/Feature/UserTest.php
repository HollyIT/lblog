<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;

test('it clears avatar_id in the user table when the associated image is deleted', function () {
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
    $avatar = $original->avatar;
    $this->assertModelExists($avatar);
    $avatar->delete();
    $original->refresh();
    $this->assertNull($original->avatar_id);
});
