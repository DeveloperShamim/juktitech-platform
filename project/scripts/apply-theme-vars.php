<?php

/**
 * Replace hardcoded purple theme hex colors with CSS variables in frontend blade files.
 */
$base = __DIR__ . '/../resources/views';

$dirs = [
    $base . '/frontend',
    $base . '/partial/front',
];

$replacements = [
    'linear-gradient(90deg, #7c3aed 0%, #9333ea 52%, #ec4899 100%)' => 'var(--accent-gradient)',
    'linear-gradient(90deg, #6d28d9 0%, #7c3aed 52%, #db2777 100%)' => 'var(--accent-gradient-hover)',
    'linear-gradient(135deg, #7c3aed 0%, #9333ea 55%, #ec4899 100%)' => 'var(--accent-gradient-vertical)',
    'linear-gradient(135deg, #6d28d9 0%, #7c3aed 55%, #db2777 100%)' => 'var(--accent-gradient-vertical-hover)',
    'linear-gradient(135deg, #6d28d9 0%, #7c3aed 45%, #9333ea 100%)' => 'var(--cta-gradient)',
    'linear-gradient(135deg, #4c1d95 0%, #6d28d9 42%, #7c3aed 72%, #9333ea 100%)' => 'var(--stats-gradient)',
    'linear-gradient(135deg, #7c3aed, #6d28d9)' => 'var(--accent-gradient-dual)',
    'linear-gradient(135deg, #8b5cf6, #7c3aed)' => 'linear-gradient(135deg, var(--primary-lighter), var(--primary-color))',
    'linear-gradient(90deg, #7c3aed, #9333ea)' => 'var(--accent-gradient-dual)',
    'linear-gradient(90deg, #7c3aed, #6366f1, #3b82f6)' => 'var(--accent-gradient-text)',
    'linear-gradient(90deg, #7c3aed, #a855f7, #ec4899)' => 'var(--accent-gradient-dual)',
    'rgba(124, 58, 237, 0.34)' => 'var(--accent-shadow-lg)',
    'rgba(124, 58, 237, 0.28)' => 'var(--accent-shadow)',
    'rgba(124, 58, 237, 0.22)' => 'var(--accent-shadow-md)',
    'rgba(124, 58, 237, 0.18)' => 'var(--accent-shadow-card)',
    'rgba(124, 58, 237, 0.15)' => 'var(--accent-shadow-card)',
    'rgba(124, 58, 237, 0.12)' => 'var(--accent-shadow-xs)',
    'rgba(124, 58, 237, 0.10)' => 'var(--accent-shadow-xs)',
    'rgba(124, 58, 237, 0.08)' => 'var(--navbar-border)',
    'rgba(124, 58, 237, 0.06)' => 'var(--accent-shadow-xs)',
    'rgba(124, 58, 237, 0.05)' => 'var(--accent-shadow-xs)',
    '#fde047' => 'var(--hero-accent-color)',
    '#ec4899' => 'var(--accent-color)',
    '#db2777' => 'var(--accent-hover)',
    '#9333ea' => 'var(--primary-light)',
    '#8b5cf6' => 'var(--primary-lighter)',
    '#7c3aed' => 'var(--primary-color)',
    '#6d28d9' => 'var(--primary-hover)',
    '#6366f1' => 'var(--secondary-color)',
    '#a78bfa' => 'var(--primary-muted)',
    '#c4b5fd' => 'var(--primary-border)',
    '#ddd6fe' => 'var(--primary-border-soft)',
    '#e9d5ff' => 'var(--primary-bg-soft)',
    '#ede9fe' => 'var(--primary-bg)',
    '#faf8ff' => 'var(--primary-bg-light)',
    '#f5f3ff' => 'var(--primary-bg-lighter)',
    '#4c1d95' => 'var(--stats-start)',
    '#0f172a' => 'var(--text-dark)',
    '#64748b' => 'var(--text-muted)',
    '#334155' => 'var(--text-heading)',
    '#120a2e' => 'var(--dark-bg)',
];

$files = [];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        continue;
    }
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php') && $file->getFilename() !== 'theme.blade.php') {
            $files[] = $file->getPathname();
        }
    }
}

$layout = $base . '/layouts/front.blade.php';
if (is_file($layout)) {
    $files[] = $layout;
}

$total = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    $original = $content;
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    if ($content !== $original) {
        file_put_contents($file, $content);
        $count = 0;
        foreach ($replacements as $search => $replace) {
            $count += substr_count($original, $search);
        }
        $total += $count;
        echo "Updated: " . str_replace($base . '/', '', $file) . "\n";
    }
}

echo "Done. Approx replacements: {$total}\n";
