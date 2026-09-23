<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssetController extends Controller
{
    public function show(string $path): BinaryFileResponse
    {
        $path = str_replace(['\\', "\0"], ['/', ''], $path);
        $path = ltrim($path, '/');

        if ($path === '' || str_contains($path, '..')) {
            abort(404);
        }

        $relativePath = 'assets/' . ltrim(str_replace('assets/', '', $path), '/');

        foreach (site_asset_candidates($relativePath) as $fullPath) {
            try {
                if (@is_file($fullPath) && @is_readable($fullPath)) {
                    return response()->file($fullPath, [
                        'Cache-Control' => 'public, max-age=604800',
                    ]);
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        abort(404);
    }
}
