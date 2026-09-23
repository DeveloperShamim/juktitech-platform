<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SiteVisitTracker
{
    private static ?bool $tableReady = null;

    public static function sources(): array
    {
        return [
            'facebook'  => ['label' => 'Facebook',    'icon' => 'fab fa-facebook-f',  'color' => '#1877f2'],
            'twitter'   => ['label' => 'Twitter / X', 'icon' => 'fab fa-twitter',     'color' => '#1da1f2'],
            'instagram' => ['label' => 'Instagram',   'icon' => 'fab fa-instagram',   'color' => '#e4405f'],
            'youtube'   => ['label' => 'YouTube',     'icon' => 'fab fa-youtube',     'color' => '#ff0000'],
            'linkedin'  => ['label' => 'LinkedIn',    'icon' => 'fab fa-linkedin-in', 'color' => '#0a66c2'],
            'google'    => ['label' => 'Google',      'icon' => 'fab fa-google',      'color' => '#ea4335'],
            'direct'    => ['label' => 'Direct',      'icon' => 'fas fa-link',        'color' => '#64748b'],
            'other'     => ['label' => 'Other',       'icon' => 'fas fa-globe',       'color' => '#94a3b8'],
        ];
    }

    public function track(Request $request): void
    {
        if (!$this->tableReady()) {
            return;
        }

        if ($this->shouldSkip($request)) {
            return;
        }

        $path = '/' . ltrim($request->path(), '/');
        $pageType = 'other';
        $productId = null;

        if ($path === '/' || $path === '') {
            $pageType = 'home';
        } elseif (preg_match('#^/theme/([^/]+)$#', $path, $matches)) {
            $pageType = 'theme';
            $productId = self::resolveProductIdBySlug(urldecode($matches[1]));
        }

        $dedupeKey = 'site_visit:' . md5(($request->ip() ?? '') . '|' . $path);
        if (!Cache::add($dedupeKey, 1, now()->addMinutes(5))) {
            return;
        }

        DB::table('site_visits')->insert([
            'path'        => mb_substr($path, 0, 500),
            'page_type'   => $pageType,
            'product_id'  => $productId,
            'source'      => $this->detectSource($request),
            'referer'     => mb_substr((string) $request->headers->get('referer', ''), 0, 500) ?: null,
            'ip_address'  => $request->ip(),
            'created_at'  => now(),
        ]);
    }

    public static function resolveProductIdBySlug(string $slug): ?int
    {
        if ($slug === '') {
            return null;
        }

        $id = Cache::remember('product_id_by_slug:' . md5($slug), 3600, function () use ($slug) {
            return Product::where('slug', $slug)->value('id');
        });

        return $id ? (int) $id : null;
    }

    /**
     * @param  int[]  $productIds
     * @return array<int, array{total: int, sources: array<string, int>}>
     */
    public static function productViewStats(array $productIds): array
    {
        $empty = [];

        foreach ($productIds as $id) {
            $empty[(int) $id] = ['total' => 0, 'sources' => []];
        }

        if ($productIds === [] || !Schema::hasTable('site_visits')) {
            return $empty;
        }

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get(['id', 'slug']);

        if ($products->isEmpty()) {
            return $empty;
        }

        $stats = $empty;

        $byProductId = DB::table('site_visits')
            ->whereIn('product_id', $productIds)
            ->selectRaw('product_id, source, COUNT(*) as total')
            ->groupBy('product_id', 'source')
            ->get();

        foreach ($byProductId as $row) {
            $id = (int) $row->product_id;
            if (!isset($stats[$id])) {
                continue;
            }
            $count = (int) $row->total;
            $stats[$id]['total'] += $count;
            $stats[$id]['sources'][$row->source] = ($stats[$id]['sources'][$row->source] ?? 0) + $count;
        }

        foreach ($products as $product) {
            $path = '/theme/' . $product->slug;

            $byPath = DB::table('site_visits')
                ->whereNull('product_id')
                ->where('path', $path)
                ->selectRaw('source, COUNT(*) as total')
                ->groupBy('source')
                ->get();

            foreach ($byPath as $row) {
                $id = (int) $product->id;
                $count = (int) $row->total;
                $stats[$id]['total'] += $count;
                $stats[$id]['sources'][$row->source] = ($stats[$id]['sources'][$row->source] ?? 0) + $count;
            }
        }

        foreach ($stats as $id => $item) {
            arsort($stats[$id]['sources']);
        }

        return $stats;
    }

    /**
     * @return array{total: int, sources: array<int, array<string, mixed>>}
     */
    public static function formatSourcesForModal(array $sources): array
    {
        $meta = self::sources();
        $total = (int) array_sum($sources);
        $formatted = [];

        foreach ($sources as $key => $count) {
            $info = $meta[$key] ?? $meta['other'];
            $count = (int) $count;

            $formatted[] = array_merge($info, [
                'key'     => $key,
                'count'   => $count,
                'percent' => $total > 0 ? round($count / $total * 100, 1) : 0,
            ]);
        }

        usort($formatted, fn ($a, $b) => $b['count'] <=> $a['count']);

        return [
            'total'   => $total,
            'sources' => $formatted,
        ];
    }

    public static function renderTrafficSourcesHtml(array $sources, ?string $productName = null, int $total = 0, int $limit = 4): string
    {
        if ($sources === []) {
            return '<span class="text-muted adm-traffic-empty">—</span>';
        }

        $payload = self::formatSourcesForModal($sources);
        $total = $total > 0 ? $total : $payload['total'];
        $json = htmlspecialchars(json_encode([
            'name'    => $productName ?? '',
            'total'   => $total,
            'sources' => $payload['sources'],
        ], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');

        $meta = self::sources();
        $html = '<button type="button" class="adm-traffic-trigger" data-traffic="' . $json . '" title="' . e(__('Click to view traffic details')) . '">';
        $html .= '<div class="adm-traffic-pills">';

        $shown = 0;
        foreach ($sources as $key => $count) {
            if ($shown >= $limit) {
                break;
            }

            $info = $meta[$key] ?? $meta['other'];
            $label = e($info['label']);
            $icon = e($info['icon']);
            $color = e($info['color']);

            $html .= '<span class="adm-traffic-pill" title="' . $label . '">';
            $html .= '<i class="' . $icon . '" style="color:' . $color . '"></i>';
            $html .= '<span>' . number_format($count) . '</span>';
            $html .= '</span>';

            $shown++;
        }

        $remaining = count($sources) - $shown;
        if ($remaining > 0) {
            $html .= '<span class="adm-traffic-pill adm-traffic-pill--more">+' . $remaining . '</span>';
        }

        $html .= '<span class="adm-traffic-pill adm-traffic-pill--open"><i class="fas fa-chart-pie"></i></span>';
        $html .= '</div></button>';

        return $html;
    }

    private function tableReady(): bool
    {
        if (self::$tableReady !== null) {
            return self::$tableReady;
        }

        self::$tableReady = Cache::remember('site_visits_table_ready', 3600, function () {
            try {
                return Schema::hasTable('site_visits');
            } catch (\Throwable) {
                return false;
            }
        });

        return self::$tableReady;
    }

    public function detectSource(Request $request): string
    {
        $utm = strtolower((string) $request->query('utm_source', ''));

        if ($utm !== '') {
            return match (true) {
                str_contains($utm, 'facebook') || str_contains($utm, 'fb') => 'facebook',
                str_contains($utm, 'twitter') || $utm === 'x' => 'twitter',
                str_contains($utm, 'instagram') || str_contains($utm, 'ig') => 'instagram',
                str_contains($utm, 'youtube') || str_contains($utm, 'yt') => 'youtube',
                str_contains($utm, 'linkedin') => 'linkedin',
                str_contains($utm, 'google') => 'google',
                default => 'other',
            };
        }

        $referer = strtolower((string) $request->headers->get('referer', ''));

        if ($referer === '') {
            return 'direct';
        }

        return match (true) {
            str_contains($referer, 'facebook.com') || str_contains($referer, 'fb.com') => 'facebook',
            str_contains($referer, 'twitter.com') || str_contains($referer, 't.co') || str_contains($referer, 'x.com') => 'twitter',
            str_contains($referer, 'instagram.com') => 'instagram',
            str_contains($referer, 'youtube.com') || str_contains($referer, 'youtu.be') => 'youtube',
            str_contains($referer, 'linkedin.com') => 'linkedin',
            str_contains($referer, 'google.') || str_contains($referer, 'google.com') => 'google',
            default => 'other',
        };
    }

    private function shouldSkip(Request $request): bool
    {
        if ($request->is('admin/*') || $request->is('admin') || $request->is('api/*')) {
            return true;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return true;
        }

        $path = $request->path();

        if (preg_match('#\.(css|js|map|png|jpg|jpeg|gif|webp|svg|ico|woff|woff2|ttf|eot)$#i', $path)) {
            return true;
        }

        $ua = strtolower((string) $request->userAgent());

        if (preg_match('/bot|crawl|spider|slurp|facebookexternalhit|preview/i', $ua)) {
            return true;
        }

        return false;
    }
}
