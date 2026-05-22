<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    private const MAIN_WIDTH      = 1200;
    private const THUMB_WIDTH     = 600;
    private const THUMB_HEIGHT    = 400;
    private const DISK            = 'public';
    private const PROPERTIES_PATH = 'properties';
    private const THUMB_PATH      = 'properties/thumbnails';

    public function uploadPropertyImage(UploadedFile $file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $this->ensureDirectoriesExist();

        // Resize main image to max 1200px wide, maintain aspect ratio
        $image = Image::read($file->getRealPath());
        if ($image->width() > self::MAIN_WIDTH) {
            $image->scale(width: self::MAIN_WIDTH);
        }
        $image->save(storage_path('app/public/' . self::PROPERTIES_PATH . '/' . $filename));

        // Generate thumbnail (600×400 crop)
        Image::read($file->getRealPath())
            ->cover(self::THUMB_WIDTH, self::THUMB_HEIGHT)
            ->save(storage_path('app/public/' . self::THUMB_PATH . '/' . $filename));

        return self::PROPERTIES_PATH . '/' . $filename;
    }

    public function deletePropertyImage(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk(self::DISK)->delete($path);

        $filename  = basename($path);
        $thumbPath = self::THUMB_PATH . '/' . $filename;
        Storage::disk(self::DISK)->delete($thumbPath);
    }

    public function thumbnailUrl(?string $path): string
    {
        if (! $path) {
            return asset('images/property-placeholder.jpg');
        }

        $filename  = basename($path);
        $thumbPath = self::THUMB_PATH . '/' . $filename;

        if (Storage::disk(self::DISK)->exists($thumbPath)) {
            return asset('storage/' . $thumbPath);
        }

        return asset('storage/' . $path);
    }

    private function ensureDirectoriesExist(): void
    {
        Storage::disk(self::DISK)->makeDirectory(self::PROPERTIES_PATH);
        Storage::disk(self::DISK)->makeDirectory(self::THUMB_PATH);
    }
}
