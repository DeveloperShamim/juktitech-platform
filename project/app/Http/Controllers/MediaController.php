<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MediaController extends Controller
{
    public function show(string $path): BinaryFileResponse
    {
        $path = str_replace(['\\', "\0"], ['/', ''], $path);
        $path = ltrim($path, '/');

        if ($path === '' || str_contains($path, '..')) {
            abort(404);
        }

        $storageRelative = str_starts_with($path, 'images/')
            ? $path
            : 'images/' . ltrim($path, '/');

        $storageFull = storage_path('app/public/' . str_replace('/', DIRECTORY_SEPARATOR, $storageRelative));
        if (is_file($storageFull) && is_readable($storageFull)) {
            return $this->fileResponse($storageFull);
        }

        $publicDisk = Storage::disk('public')->path($storageRelative);
        if (is_file($publicDisk) && is_readable($publicDisk)) {
            return $this->fileResponse($publicDisk);
        }

        $legacyPath = 'assets/' . $storageRelative;
        foreach (site_asset_candidates($legacyPath) as $fullPath) {
            try {
                if (@is_file($fullPath) && @is_readable($fullPath)) {
                    return $this->fileResponse($fullPath);
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        abort(404);
    }

    private function fileResponse(string $fullPath): BinaryFileResponse
    {
        return response()->file($fullPath, [
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }
}
