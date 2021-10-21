<?php

namespace App\Actions;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Arr;
use Intervention\Image\ImageManager;
use League\Flysystem\FileNotFoundException;
use RuntimeException;

class GenerateImageFormatAction
{
    protected int $maxWidth = 150;
    protected int $maxHeight = 300;
    protected string $sourceDisk;
    protected string $sourcePath;
    protected FilesystemManager $manager;
    protected ImageManager $imageManager;
    protected string $formatName;
    protected array $formatOptions;

    public function __construct(
        FilesystemManager $manager,
        ImageManager $imageManager,
        string $sourceDisk,
        string $sourcePath,
        string $formatName,
        array $formatOptions
    ) {
        $this->manager = $manager;
        $this->imageManager = $imageManager;
        $this->sourceDisk = $sourceDisk;
        $this->sourcePath = $sourcePath;
        $this->formatName = $formatName;
        $this->formatOptions = $formatOptions;
    }

    public static function make($disk, $path, $formatName, $config): string
    {
        /** @var static $instance */
        $instance = app()->make(static::class, [
            'sourceDisk' => $disk,
            'sourcePath' => $path,
            'formatName' => $formatName,
            'formatOptions' => $config,
        ]);

        return $instance->save();
    }

    public function getConfig($key, $default = null)
    {
        return Arr::get($this->formatOptions, $key, $default);
    }

    /**
     * @throws FileNotFoundException
     */
    public function save(string $disk = 'public', string $path = null): string
    {
        if (! $path) {
            $extension = pathinfo($this->sourcePath, PATHINFO_EXTENSION);
            $path = str_replace('.'.$extension, '.' . $this->formatName . '.' . $this->getConfig('type', 'jpg'), $this->sourcePath);
        }

        $width = $this->getConfig('width');
        $height = $this->getConfig('height');

        $image = $this->imageManager->make($this->manager->disk($this->sourceDisk)->read($this->sourcePath));
        switch ($this->getConfig('mode')) {
            case 'scale':
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                break;

            case 'fit':
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                break;

            default:
                throw new RuntimeException('Unknown image format mode ' . $this->getConfig('mode'));
        }

        $this->manager->disk($disk)->put($path, $image->encode(
            $this->getConfig('type', 'jpg'),
            $this->getConfig('quality', 90)
        ));

        return $path;
    }
}
