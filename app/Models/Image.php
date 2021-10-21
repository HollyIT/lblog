<?php

namespace App\Models;

use App\Actions\DeleteFileAction;
use App\Actions\GenerateImageFormatAction;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string $group
 * @property string $disk
 * @property string $path
 * @property string $url
 * @property int $user_id
 * @property Collection | ImageFormat[] $formats
 * @property array $groupConfig
 * @property User $user
 * @property Collection | Post[] $posts
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 */
class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'disk',
        'user_id',
    ];

    protected $with = ['formats'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Image $image) {
            if (! $image->user_id) {
                $image->user_id = \Auth::id();
            }
        });

        static::deleted(function (Image $image) {
            DeleteFileAction::delete($image->disk, $image->path);
        });

        static::saving(function (Image $image) {
            if (! $image->group) {
                $image->group = 'default';
            }
        });
    }

    public function formats()
    {
        return $this->hasMany(ImageFormat::class);
    }

    public function makeFormats()
    {
        if ($this->disk && $this->path) {
            foreach ($this->formats as $format) {
                $format->delete();
            }

            if (! empty($this->groupConfig['formats'])) {
                foreach ($this->groupConfig['formats'] as $name => $config) {
                    $path = GenerateImageFormatAction::make($this->disk, $this->path, $name, $config);
                    ImageFormat::create([
                        'image_id' => $this->id,
                        'format' => $name,
                        'disk' => $config['disk'],
                        'path' => $path,
                    ]);
                }
            }
            $this->load('formats');
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->disk && $this->path) {
            return Storage::disk($this->disk)->url($this->path);
        }

        return null;
    }


    public static function createFromUpload(UploadedFile $file, $group = 'default'): static
    {
        $instance = new static();
        $instance->attachUpload($file, $group);
        $instance->save();

        return $instance;
    }

    public function attachUpload(UploadedFile $file, $group = 'default'): static
    {
        $this->group = $group;
        $path = $file->store($this->groupConfig['path'], [
            'disk' => $this->groupConfig['disk'],
        ]);

        $this->path = $path;
        $this->disk = 'public';

        return $this;
    }

    public function getGroupConfigAttribute(): array
    {
        $name = $this->group ?: 'default';
        $config = config('images.groups.'.$name);
        if (! $config) {
            throw new Exception('Unknown image group '.$name);
        }

        return $config;
    }

    public function setImageAttribute($value)
    {
        $groupConfig = $this->groupConfig;
        DeleteFileAction::delete($this->disk, $this->path);
        $this->disk = $groupConfig['disk'];
        $this->path = $this->groupConfig['path'].'/'.pathinfo($value, PATHINFO_BASENAME);
        Storage::disk($groupConfig['disk'])->put($this->path, file_get_contents($value));
    }

    public function formatUrl($format)
    {
        $instance = $this->formats->first(fn ($model) => $model->format === $format);
        if ($instance) {
            return $instance->url;
        }

        return null;
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
