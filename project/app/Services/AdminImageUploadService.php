<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class AdminImageUploadService
{
    public const DEFAULT_MAX_BYTES = 409600; // 400 KB
    public const DEFAULT_MIN_BYTES = 204800; // 200 KB
    public const SMALL_MAX_BYTES = 204800; // 200 KB

    public function storePublic(UploadedFile $file, string $relativeDirectory, array $options = []): string
    {
        $relativeDirectory = trim(str_replace('\\', '/', $relativeDirectory), '/');
        $payload = $this->buildPayload($file, $options);
        $relativeFile = $relativeDirectory . '/' . $payload['filename'];

        $writtenPaths = [];
        $errors = [];

        foreach ($this->uploadTargetDirectories($relativeDirectory) as $directory) {
            try {
                $fullPath = $this->writePayload($payload, $directory);
                if ($fullPath !== null) {
                    $writtenPaths[] = $fullPath;
                }
            } catch (\Throwable $e) {
                $errors[] = $directory . ': ' . $e->getMessage();
            }
        }

        if ($writtenPaths === []) {
            throw new \RuntimeException(
                'Image upload failed on server. cPanel → public_html/project/storage (775) permission দিন. '
                . implode(' | ', $errors)
            );
        }

        $primaryPath = $writtenPaths[0];
        $this->mirrorAssetFile($primaryPath, $relativeFile);

        return $payload['filename'];
    }

    /**
     * @deprecated Use storePublic() for admin uploads.
     */
    public function store(UploadedFile $file, string $directory, array $options = []): string
    {
        $payload = $this->buildPayload($file, $options);
        $directory = $this->ensureDirectory($directory);
        $fullPath = $this->writePayload($payload, $directory);

        if ($fullPath === null) {
            throw new \RuntimeException('Image upload failed: could not write file to ' . $directory);
        }

        return $payload['filename'];
    }

    public function delete(?string $directory, ?string $filename): void
    {
        $this->deletePublic($directory, $filename);
    }

    public function deletePublic(?string $relativeDirectory, ?string $filename): void
    {
        if (!$filename || !$relativeDirectory) {
            return;
        }

        $relativeDirectory = trim(str_replace('\\', '/', $relativeDirectory), '/');
        $relativeFile = $relativeDirectory . '/' . $filename;

        foreach (site_asset_candidates($relativeFile) as $path) {
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $storageRelative = site_image_storage_relative($relativeDirectory);
        $storagePath = storage_path(
            'app/public/' . str_replace('/', DIRECTORY_SEPARATOR, $storageRelative . '/' . $filename)
        );

        if (is_file($storagePath)) {
            @unlink($storagePath);
        }
    }

    public function resolvePublicAssetDirectory(string $relativeDirectory): string
    {
        return $this->storageDirectory($relativeDirectory);
    }

    /**
     * @return list<string>
     */
    private function uploadTargetDirectories(string $relativeDirectory): array
    {
        $targets = [$this->storageDirectory($relativeDirectory)];

        foreach (site_asset_storage_roots() as $root) {
            $dir = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativeDirectory);
            if (!in_array($dir, $targets, true)) {
                $targets[] = $dir;
            }
        }

        return $targets;
    }

    private function storageDirectory(string $relativeDirectory): string
    {
        $storageRelative = site_image_storage_relative($relativeDirectory);

        return storage_path('app/public/' . str_replace('/', DIRECTORY_SEPARATOR, $storageRelative));
    }

    /**
     * @return array{filename: string, contents: string}
     */
    private function buildPayload(UploadedFile $file, array $options): array
    {
        $maxBytes = (int) ($options['max_bytes'] ?? self::DEFAULT_MAX_BYTES);
        $minBytes = (int) ($options['min_bytes'] ?? self::DEFAULT_MIN_BYTES);
        $maxWidth = (int) ($options['max_width'] ?? 0);
        $maxHeight = (int) ($options['max_height'] ?? 0);
        $prefix = (string) ($options['prefix'] ?? time());
        $convert = $options['convert'] ?? true;

        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $mime = strtolower((string) $file->getMimeType());
        $source = $file->getRealPath();

        if (!$source || !is_readable($source)) {
            throw new \RuntimeException('Uploaded file is not readable.');
        }

        if ($this->shouldKeepOriginal($extension, $mime)) {
            return [
                'filename' => $this->buildFilename($prefix, $file->getClientOriginalName(), $extension),
                'contents' => (string) file_get_contents($source),
            ];
        }

        if (!$convert) {
            $rawContents = (string) file_get_contents($source);

            if (strlen($rawContents) <= $maxBytes) {
                return [
                    'filename' => $this->buildFilename($prefix, $file->getClientOriginalName(), $extension),
                    'contents' => $rawContents,
                ];
            }

            $image = $this->loadImage($source);
            $this->applyOptionalResize($image, $maxWidth, $maxHeight);

            return [
                'filename' => $this->buildFilename($prefix, $file->getClientOriginalName(), $extension),
                'contents' => $this->encodeOriginalUnderSize($image, $extension, $maxBytes),
            ];
        }

        try {
            $image = $this->loadImage($source);
            $this->applyOptionalResize($image, $maxWidth, $maxHeight);

            return [
                'filename' => $this->buildFilename($prefix, $file->getClientOriginalName(), 'webp'),
                'contents' => $this->encodeWebpTargetRange($image, $minBytes, $maxBytes),
            ];
        } catch (\Throwable $e) {
            throw new \RuntimeException('Image processing failed: ' . $e->getMessage(), 0, $e);
        }
    }

    private function loadImage(string $source)
    {
        $image = Image::make($source);
        if (method_exists($image, 'orientate')) {
            $image->orientate();
        }

        return $image;
    }

    private function applyOptionalResize($image, int $maxWidth, int $maxHeight): void
    {
        if ($maxWidth <= 0 || $maxHeight <= 0) {
            return;
        }

        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
    }

    private function encodeOriginalUnderSize($image, string $extension, int $maxBytes): string
    {
        $extension = strtolower($extension);

        if (in_array($extension, ['jpg', 'jpeg'], true)) {
            return $this->encodeRasterUnderSize($image, 'jpg', $maxBytes, 95);
        }

        if ($extension === 'png') {
            $encoded = $this->encodeRasterUnderSize($image, 'png', $maxBytes, 9);
            if (strlen($encoded) <= $maxBytes) {
                return $encoded;
            }
        }

        return $this->encodeWebpUnderSize($image, $maxBytes);
    }

    private function encodeRasterUnderSize($image, string $format, int $maxBytes, int $maxQuality): string
    {
        $width = $image->width();
        $height = $image->height();

        for ($attempt = 0; $attempt < 8; $attempt++) {
            $encoded = $this->encodeBestQualityUnderLimit($image, $format, $maxBytes, $maxQuality);

            if ($encoded !== null) {
                return $encoded;
            }

            $width = (int) max(480, $width * 0.9);
            $height = (int) max(480, $height * 0.9);

            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        return (string) $image->encode($format, $format === 'png' ? 9 : 82);
    }

    /**
     * @param array{filename: string, contents: string} $payload
     */
    private function writePayload(array $payload, string $directory): ?string
    {
        $directory = $this->ensureDirectory($directory);
        $fullPath = $directory . DIRECTORY_SEPARATOR . $payload['filename'];

        if (@file_put_contents($fullPath, $payload['contents'], LOCK_EX) === false) {
            throw new \RuntimeException('file_put_contents failed');
        }

        if (!is_file($fullPath) || filesize($fullPath) === 0) {
            @unlink($fullPath);
            throw new \RuntimeException('written file is empty');
        }

        @chmod($fullPath, 0644);

        return $fullPath;
    }

    private function ensureDirectory(string $directory): string
    {
        $directory = rtrim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $directory), DIRECTORY_SEPARATOR);

        if (!is_dir($directory) && !@mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new \RuntimeException('cannot create directory');
        }

        if (!is_writable($directory)) {
            @chmod($directory, 0755);
        }

        if (!is_writable($directory)) {
            throw new \RuntimeException('directory not writable');
        }

        return $directory;
    }

    private function mirrorAssetFile(string $savedFullPath, string $relativeFile): void
    {
        if (!is_file($savedFullPath) || filesize($savedFullPath) === 0) {
            return;
        }

        $relativeFile = trim(str_replace('\\', '/', $relativeFile), '/');

        foreach (site_asset_candidates($relativeFile) as $target) {
            if ($this->normalizePath($target) === $this->normalizePath($savedFullPath)) {
                continue;
            }

            $targetDir = dirname($target);
            if (!is_dir($targetDir)) {
                @mkdir($targetDir, 0755, true);
            }

            if (!is_dir($targetDir) || !is_writable($targetDir)) {
                continue;
            }

            if (is_file($target) && filesize($target) > 0) {
                continue;
            }

            @copy($savedFullPath, $target);
            @chmod($target, 0644);
        }
    }

    private function shouldKeepOriginal(string $extension, string $mime): bool
    {
        if (in_array($extension, ['gif', 'svg'], true)) {
            return true;
        }

        return str_contains($mime, 'gif') || str_contains($mime, 'svg');
    }

    private function normalizePath(string $path): string
    {
        return strtolower(str_replace('\\', '/', $path));
    }

    private function buildFilename(string $prefix, string $originalName, string $extension): string
    {
        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $base = preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $base);
        $base = trim((string) $base, '-');

        if ($base === '') {
            $base = 'image';
        }

        return $prefix . '_' . Str::limit($base, 40, '') . '.' . strtolower($extension);
    }

    private function encodeWebpUnderSize($image, int $maxBytes): string
    {
        return $this->encodeWebpTargetRange($image, 0, $maxBytes);
    }

    private function encodeWebpTargetRange($image, int $minBytes, int $maxBytes): string
    {
        $encoded = $this->encodeRasterUnderSize($image, 'webp', $maxBytes, 98);

        if ($minBytes <= 0) {
            return $encoded;
        }

        $pixelCount = $image->width() * $image->height();

        if ($pixelCount < 120000) {
            return $encoded;
        }

        for ($quality = 99; $quality <= 100; $quality++) {
            $candidate = (string) $image->encode('webp', $quality);

            if (strlen($candidate) > $maxBytes) {
                break;
            }

            if (strlen($candidate) >= $minBytes || strlen($candidate) > strlen($encoded)) {
                $encoded = $candidate;
            }
        }

        return $encoded;
    }

    private function encodeBestWebpUnderLimit($image, int $maxBytes): ?string
    {
        return $this->encodeBestQualityUnderLimit($image, 'webp', $maxBytes, 95);
    }

    private function encodeBestQualityUnderLimit($image, string $format, int $maxBytes, int $maxQuality): ?string
    {
        $low = $format === 'png' ? 0 : 40;
        $high = $maxQuality;
        $best = null;

        while ($low <= $high) {
            $quality = (int) floor(($low + $high) / 2);
            $encoded = (string) $image->encode($format, $quality);

            if (strlen($encoded) <= $maxBytes) {
                $best = $encoded;
                $low = $quality + 1;
            } else {
                $high = $quality - 1;
            }
        }

        return $best;
    }
}
