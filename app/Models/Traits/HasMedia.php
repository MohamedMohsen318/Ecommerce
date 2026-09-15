<?php

namespace App\Models\Traits;

use App\Enums\MediaType;
use App\Models\Relations\HasMediaRelation;
use Illuminate\Http\UploadedFile;

trait HasMedia
{
    use HasMediaRelation;

    public function getFirstImage(): ?string
    {
        if ($this->relationLoaded('media')) {
            return $this->media->firstWhere('type', MediaType::Image->value)?->file;
        }

        return $this->media()
            ->where('type', MediaType::Image->value)
            ->first()?->file;
    }

    public function getFirstImageUrl(): ?string
    {
        $image = $this->getFirstImage();

        return $image ? tenant_asset($image) : null;
    }

    public function setMedia(UploadedFile $file, MediaType|string $type, string $path): void
    {
        $type = $type instanceof MediaType ? $type->value : $type;

        $existing = $this->media()->where('type', $type)->first();

        if ($existing) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($existing->file);
        }

        $this->media()->updateOrCreate(
            ['type' => $type],
            ['file' => $file->store($path, 'public')]
        );
    }
}
