<?php

namespace App\Observers;

use App\Actions\DeleteFileAction;
use App\Models\Image;

class ImageFormatObserver
{
    public function saved(Image $image)
    {
        if (! $image->exists || $image->isDirty(['disk', 'path', 'group'])) {
            DeleteFileAction::delete($image->getOriginal('disk'), $image->getOriginal('path'));
            $image->makeFormats();
        }
    }


    public function deleted(Image $image)
    {
        foreach ($image->formats as $format) {
            $format->delete();
        }
    }
}
