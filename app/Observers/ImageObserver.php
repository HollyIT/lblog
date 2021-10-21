<?php

namespace App\Observers;

use App\Models\Image;
use App\Models\Post;
use App\Models\User;

class ImageObserver
{
    public function deleted(Image $image)
    {
        switch ($image->group) {
            case 'avatars':
                $user = User::where('avatar_id', $image->id)->first();

                if ($user && $user->exists) {
                    $user->update(['avatar_id' => null]);
                }

                break;

            default:
                $post = Post::where('image_id', $image->id)->first();
                if ($post && $post->exists) {
                    $post->update(['image_id' => null]);
                }

                break;
        }
    }
}
