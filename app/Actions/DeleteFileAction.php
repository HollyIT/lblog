<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;

class DeleteFileAction
{
    public static function delete($disk, $path): bool
    {
        if ($disk && $path) {
            $storage = Storage::disk($disk);
            if ($storage->exists($path)) {
                $storage->delete($path);

                return true;
            }
        }

        return false;
    }
}
