<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PublishSiteImagesCommand extends Command
{
    protected $signature = 'site-images:publish {--from= : Legacy assets root (default: public_html/assets/images)}';

    protected $description = 'Copy legacy public_html/assets/images files into storage/app/public/images (run once on cPanel)';

    public function handle(): int
    {
        $from = $this->option('from') ?: public_assets_path('assets/images');
        $to = storage_path('app/public/images');

        if (!is_dir($from)) {
            $this->error('Source not found: ' . $from);

            return self::FAILURE;
        }

        if (!is_dir($to) && !mkdir($to, 0755, true) && !is_dir($to)) {
            $this->error('Cannot create: ' . $to);

            return self::FAILURE;
        }

        $count = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($from, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $relative = ltrim(str_replace($from, '', $file->getPathname()), '/\\');
            $relative = str_replace('\\', '/', $relative);
            $target = $to . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
            $targetDir = dirname($target);

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            if (!is_file($target) || filesize($target) === 0) {
                copy($file->getPathname(), $target);
                $count++;
            }
        }

        $this->info("Published {$count} image(s) to storage/app/public/images");

        return self::SUCCESS;
    }
}
