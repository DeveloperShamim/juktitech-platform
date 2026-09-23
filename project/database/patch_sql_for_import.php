<?php
/**
 * MySQL #1118 Row size too large — SQL dump patcher
 *
 * Usage (Windows XAMPP):
 *   c:\xampp\php\php.exe database\patch_sql_for_import.php "C:\path\to\backup.sql"
 *
 * Output: same folder, file named backup.fixed.sql
 * cPanel/phpMyAdmin এ ওই .fixed.sql import করুন।
 */

if (PHP_SAPI !== 'cli') {
    exit("CLI only.\n");
}

$file = $argv[1] ?? null;
if (!$file || !is_file($file)) {
    fwrite(STDERR, "Usage: php patch_sql_for_import.php <dump.sql>\n");
    exit(1);
}

$sql = file_get_contents($file);
if ($sql === false || $sql === '') {
    fwrite(STDERR, "Could not read file or file is empty.\n");
    exit(1);
}

$originalSize = strlen($sql);
$patched = patchDump($sql);

$dir  = dirname($file);
$base = pathinfo($file, PATHINFO_FILENAME);
$ext  = pathinfo($file, PATHINFO_EXTENSION);
$out  = $dir . DIRECTORY_SEPARATOR . $base . '.fixed.' . ($ext ?: 'sql');

if (!str_contains($patched, 'SET FOREIGN_KEY_CHECKS=0')) {
    $patched = importHeader() . $patched;
}

file_put_contents($out, $patched);

echo "OK — patched dump saved:\n  {$out}\n";
echo "Size: " . round($originalSize / 1024 / 1024, 2) . " MB → " . round(strlen($patched) / 1024 / 1024, 2) . " MB\n";
echo "\ncPanel/phpMyAdmin এ এই .fixed.sql ফাইল import করুন।\n";

function importHeader(): string
{
    return <<<'HDR'
-- Patched for MySQL/MariaDB error #1118 (row size too large)
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
SET UNIQUE_CHECKS=0;
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

HDR;
}

function patchDump(string $sql): string
{
    $sql = patchTableCreate($sql, 'generalsettings');
    // backup: other wide tables sometimes hit the same limit
    foreach (['users', 'products', 'pages', 'seotools'] as $table) {
        $sql = patchTableCreate($sql, $table, soft: true);
    }
    return $sql;
}

/**
 * @param bool $soft If true, only add ROW_FORMAT=DYNAMIC (no varchar→text)
 */
function patchTableCreate(string $sql, string $table, bool $soft = false): string
{
    $pattern = '/CREATE TABLE (IF NOT EXISTS )?`' . preg_quote($table, '/') . '`\s*\((.*?)\)\s*(ENGINE=[^;]+;)/is';

    if (!preg_match($pattern, $sql)) {
        return $sql;
    }

    return preg_replace_callback($pattern, function (array $m) use ($table, $soft) {
        $ifNotExists = $m[1] ?? '';
        $body        = $m[2];
        $engine      = trim(rtrim($m[3], ';'));

        if (!$soft) {
            // varchar(255/191) → text reduces calculated row size dramatically
            $body = preg_replace('/\bvarchar\s*\(\s*(?:191|255)\s*\)/i', 'text', $body);
            // mediumtext/longtext columns are fine; char(255) rare but fix if any
            $body = preg_replace('/\bchar\s*\(\s*255\s*\)/i', 'varchar(100)', $body);
        }

        if (!preg_match('/ROW_FORMAT\s*=/i', $engine)) {
            $engine .= ' ROW_FORMAT=DYNAMIC';
        }

        return 'CREATE TABLE ' . $ifNotExists . '`' . $table . '` (' . $body . ') ' . $engine . ';';
    }, $sql, 1);
}
