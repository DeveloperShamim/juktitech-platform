<?php 

if (!function_exists('slug_create') ) {
    function slug_create($val) {
        $slug = preg_replace('/\s+/u', '-', trim($val));
        return $slug;
    }
}

if(!function_exists('advertisement')){
    function advertisement(){
        $index_bottom = App\Models\Advertisement::inRandomOrder()
                                        ->where('add_placement','index_bottom')
                                        ->where('addSize','size_728')
                                        ->where('status',1)
                                        ->first(); 
        return $index_bottom;
    }
}

if(!function_exists('sidebar_banner')){
    function sidebar_banner(){
        $sidebar_banner = App\Models\Advertisement::inRandomOrder()
                                        ->where('add_placement','sidebar_bottom')
                                        ->where('addSize','size_468')
                                        ->where('status',1)
                                        ->first(); 
        return $sidebar_banner;
    }
}

if(!function_exists('sponsor_banner')){
    function sponsor_banner(){
        $sponsor_banners     = App\Models\Advertisement::inRandomOrder()
                                        ->where('add_placement','sponsor')
                                        ->where('addSize','size_468')
                                        ->where('status',1)
                                        ->take(2)
                                        ->get();
        return $sponsor_banners;
    }
}

if(!function_exists('header_ads')){

    function header_ads(){
        $header_ads   = App\Models\Advertisement::inRandomOrder()
                                ->where('add_placement','header')
                                ->where('addSize','size_728')
                                ->where('status',1)
                                ->inRandomOrder()
                                ->limit(1)
                                ->first(); 
        return $header_ads;
    }
}

if (! function_exists('convertUtf8')) {
    function convertUtf8( $value ) {
        return mb_detect_encoding($value, mb_detect_order(), true) === 'UTF-8' ? $value : mb_convert_encoding($value, 'UTF-8');
    }
}

if (! function_exists('site_asset_storage_roots')) {
    function site_asset_storage_roots(): array
    {
        $projectRoot = rtrim(base_path(), '/\\');
        $parentRoot = rtrim(dirname(base_path()), '/\\');
        $configured = rtrim((string) config('assets.web_root', $parentRoot), '/\\');

        if ($configured === $projectRoot && is_dir($parentRoot . DIRECTORY_SEPARATOR . 'assets')) {
            $configured = $parentRoot;
        }

        return array_values(array_unique(array_filter([
            $configured,
            $parentRoot,
            $projectRoot,
            rtrim(public_path(), '/\\'),
        ])));
    }
}

if (! function_exists('site_asset_candidates')) {
    function site_asset_candidates(string $relativePath): array
    {
        $relativePath = trim(str_replace('\\', '/', $relativePath), '/');
        if ($relativePath !== '' && ! str_starts_with($relativePath, 'assets/')) {
            $relativePath = 'assets/' . ltrim($relativePath, '/');
        }

        $candidates = [];
        foreach (site_asset_storage_roots() as $root) {
            $candidates[] = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        }

        $candidates[] = base_path(str_replace('/', DIRECTORY_SEPARATOR, $relativePath));
        $candidates[] = base_path('public' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath));

        return array_values(array_unique($candidates));
    }
}

if (! function_exists('site_image_storage_dir')) {
    function site_image_storage_dir(string $relativeDirectory): string
    {
        $relativeDirectory = trim(str_replace('\\', '/', $relativeDirectory), '/');
        $storageRelative = site_image_storage_relative($relativeDirectory);
        $dir = storage_path('app/public/' . str_replace('/', DIRECTORY_SEPARATOR, $storageRelative));

        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        return $dir;
    }
}

if (! function_exists('public_assets_writable_path')) {
    function public_assets_writable_path(string $path = ''): string
    {
        $relative = trim(str_replace('\\', '/', $path), '/');
        if ($relative === '') {
            $relative = 'assets';
        } elseif (! str_starts_with($relative, 'assets/') && $relative !== 'assets') {
            $relative = 'assets/' . ltrim($relative, '/');
        }

        $storageDir = site_image_storage_dir($relative);
        if (is_dir($storageDir) && is_writable($storageDir)) {
            return $storageDir;
        }

        $lastError = $storageDir;

        foreach (site_asset_storage_roots() as $root) {
            $dir = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);

            if (! is_dir($dir) && ! @mkdir($dir, 0755, true) && ! is_dir($dir)) {
                $lastError = $dir;
                continue;
            }

            if (is_writable($dir)) {
                return $dir;
            }

            $lastError = $dir;
        }

        throw new \RuntimeException(
            'Image upload folder is not writable. cPanel → public_html/project/storage (775) permission দিন. Last path: ' . $lastError
        );
    }
}

if (! function_exists('public_assets_path')) {
    function public_assets_path(string $path = ''): string
    {
        $roots = site_asset_storage_roots();
        $root = $roots[0] ?? rtrim(dirname(base_path()), '/\\');

        $path = trim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);

        return $path !== '' ? $root . DIRECTORY_SEPARATOR . $path : $root;
    }
}

if (! function_exists('site_image_storage_relative')) {
    function site_image_storage_relative(string $directory): string
    {
        $directory = trim(str_replace('\\', '/', $directory), '/');

        if (str_starts_with($directory, 'assets/images/')) {
            return 'images/' . substr($directory, strlen('assets/images/'));
        }

        if (str_starts_with($directory, 'assets/')) {
            return ltrim(substr($directory, strlen('assets/')), '/');
        }

        if (str_starts_with($directory, 'images/')) {
            return $directory;
        }

        return 'images/' . ltrim($directory, '/');
    }
}

if (! function_exists('uploaded_image')) {
    function uploaded_image(string $directory, ?string $filename): string
    {
        if ($filename === null || $filename === '') {
            return url('/media/images/noimage.png');
        }

        $relative = site_image_storage_relative($directory) . '/' . ltrim(str_replace('\\', '/', $filename), '/');

        return url('/media/' . ltrim($relative, '/'));
    }
}

if (! function_exists('asset_image')) {
    function asset_image(string $relativePath): string
    {
        $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');

        // Uploaded site images: serve via Laravel /media/ (finds public_html, project/assets, storage).
        if (str_starts_with($relativePath, 'assets/images/')) {
            $filename = basename($relativePath);
            $directory = dirname($relativePath);
            $mediaRelative = site_image_storage_relative($directory) . '/' . $filename;

            return url('/media/' . ltrim($mediaRelative, '/'));
        }

        return url('/' . $relativePath);
    }
}

if (! function_exists('admin_upload_image')) {
    function admin_upload_image(\Illuminate\Http\UploadedFile $file, string $directory, array $options = []): string
    {
        $options = array_merge([
            'convert' => true,
            'max_bytes' => 409600,  // 400 KB
            'min_bytes' => 204800,  // 200 KB target (large images)
        ], $options);

        return app(\App\Services\AdminImageUploadService::class)->storePublic($file, $directory, $options);
    }
}

if (! function_exists('admin_delete_image')) {
    function admin_delete_image(string $directory, ?string $filename): void
    {
        app(\App\Services\AdminImageUploadService::class)->deletePublic($directory, $filename);
    }
}

if(!function_exists('d_logo')){
    function d_logo($lid){
        $header_footer_logo = App\Models\Language::find($lid)->logos;
        return $header_footer_logo;
    }
}


