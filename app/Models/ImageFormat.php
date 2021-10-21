<?php

namespace App\Models;

use App\Actions\DeleteFileAction;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $image_id
 * @property Image $image
 * @property string $format
 * @property string $disk
 * @property string $path
 * @property-read string $url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @mixin Eloquent
 */
class ImageFormat extends Model
{
    use HasFactory;

    protected $fillable = [
        'format',
        'image_id',
        'disk',
        'path',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleted(function (ImageFormat $format) {
            DeleteFileAction::delete($format->disk, $format->path);
        });
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->disk && $this->path) {
            return Storage::disk($this->disk)->url($this->path);
        }

        return null;
    }
}
