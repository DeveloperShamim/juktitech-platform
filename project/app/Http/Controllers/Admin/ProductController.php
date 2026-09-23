<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Services\SiteVisitTracker;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'admin.otp']);
    }

    // ১. ডাটাটেবল (ওয়েবসাইট কালেকশন সহ)
    public function datatables()
    {
        $datas = Product::orderBy('sort_order', 'asc')->orderBy('id', 'desc');
        $productIds = Product::pluck('id')->map(fn ($id) => (int) $id)->all();
        $viewStats = SiteVisitTracker::productViewStats($productIds);

        return DataTables::of($datas)
            ->editColumn('photo', function (Product $data) {
                $photo = $data->photo ? asset_image('assets/images/products/'.$data->photo) : asset_image('assets/images/noimage.png');
                return '<img src="' . $photo . '" width="80" class="rounded shadow-sm">';
            })
            ->addColumn('category', function (Product $data) {
                $catName = e($data->category_name ?: 'অন্যান্য');
                $badgeHtml = $data->badge_text ? '<br><span class="badge badge-warning text-dark font-weight-normal mt-1" style="font-size:11px;">' . e($data->badge_text) . '</span>' : '';
                $count = count($data->clean_features);
                $featHtml = '<br><small class="text-muted"><i class="fas fa-check-circle text-success mr-1"></i>' . $count . ' টি ফিচার</small>';
                return '<span class="badge badge-primary font-weight-bold" style="font-size:12px; padding: 4px 8px;">' . $catName . '</span>' . $badgeHtml . $featHtml;
            })
            ->editColumn('price', function (Product $data) {
                return '৳ ' . number_format($data->price, 2);
            })
            ->addColumn('views', function (Product $data) use ($viewStats) {
                $total = (int) ($viewStats[$data->id]['total'] ?? 0);

                return '<div class="adm-views-badge" title="' . e(__('Total page views')) . '">'
                    . '<i class="fas fa-eye"></i>'
                    . '<strong>' . number_format($total) . '</strong>'
                    . '</div>';
            })
            ->addColumn('traffic_sources', function (Product $data) use ($viewStats) {
                $sources = $viewStats[$data->id]['sources'] ?? [];
                $total = (int) ($viewStats[$data->id]['total'] ?? 0);

                return SiteVisitTracker::renderTrafficSourcesHtml($sources, $data->name, $total);
            })
            ->addColumn('action', function (Product $data) {
                return '<div class="btn-group btn-group-sm">
                            <a href="' . route('admin.products.edit', $data->id) . '" class="btn btn-info shadow-sm"><i class="fas fa-edit"></i> এডিট</a>
                            <button type="button" data-href="' . route('admin.products.destroy', $data->id) . '" class="btn btn-danger shadow-sm ml-1" data-toggle="modal" data-target="#confirm-delete"><i class="fas fa-trash"></i> ডিলিট</button>
                        </div>';
            })
            ->rawColumns(['photo', 'category', 'views', 'traffic_sources', 'action'])
            ->make(true);
    }

    public function index()
    {
        $productIds = Product::pluck('id')->map(fn ($id) => (int) $id)->all();
        $viewStats = SiteVisitTracker::productViewStats($productIds);
        $totalViews = array_sum(array_column($viewStats, 'total'));

        return view('admin.product.index', compact('totalViews'));
    }

    public function create() {
        return view('admin.product.create');
    }

    // ২. স্টোর মেথড (SEO ও ওয়েবসাইট কালেকশন লজিক সহ)
    public function store(Request $request) {
        $request->validate([
            'name'  => 'required|unique:products,name',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric',
            'google_drive_file_id' => 'required_if:is_digital,1|nullable|string|max:500',
            'download_limit' => 'nullable|integer|min:1|max:50',
            'tutorials' => 'nullable|array|max:30',
            'tutorials.*.title' => 'nullable|string|max:255|required_with:tutorials.*.url',
            'tutorials.*.url' => 'nullable|url|max:2048|required_with:tutorials.*.title',
        ]);

        $input = $request->all();

        // ইমেজ আপলোড
        if ($file = $request->file('photo')) {
            $input['photo'] = admin_upload_image($file, 'assets/images/products');
        }

        // --- SEO লজিক (নতুন প্রোডাক্টের জন্য) ---
        // যদি meta_title ফাঁকা থাকে, প্রোডাক্টের নাম ব্যবহার করো
        $input['meta_title'] = !empty($request->meta_title) ? $request->meta_title : $request->name;

        // যদি meta_keyword ফাঁকা থাকে, নামগুলোকে কমা দিয়ে আলাদা করে কিওয়ার্ড বানাও
        $input['meta_keyword'] = !empty($request->meta_keyword) ? $request->meta_keyword : str_replace(' ', ', ', $request->name);

        // যদি meta_description ফাঁকা থাকে, details থেকে ১৬০ ক্যারেক্টার নাও
        if (empty($request->meta_description)) {
            // HTML ট্যাগ রিমুভ করে ক্লিন টেক্সট নেওয়া হচ্ছে
            $clean_text = strip_tags($request->description . ' ' . $request->details);
            $input['meta_description'] = Str::limit($clean_text, 160);
        } else {
            $input['meta_description'] = $request->meta_description;
        }

        $input['slug'] = Str::slug($request->name);
        $input['status'] = $request->boolean('status', true);
        $input['is_digital'] = $request->boolean('is_digital');
        $input['download_limit'] = max(1, (int) $request->input('download_limit', 3));
        $input['google_drive_file_id'] = $this->resolveDriveFileId($request);
        $input['digital_file_name'] = $request->input('digital_file_name');
        $input['tutorials'] = $this->normalizeTutorials($request);

        // কালেকশন ফিল্ডস
        $input['category_key'] = $request->input('category_key') ?: 'general';
        $input['category_name'] = $request->input('category_name') ?: 'অন্যান্য';
        $input['badge_text'] = $request->input('badge_text');
        $input['badge_class'] = $request->input('badge_class', 'cd-badge--hot');
        $input['license_type'] = $request->input('license_type', 'আজীবন লাইসেন্স');
        $input['sort_order'] = (int) $request->input('sort_order', 0);
        $input['features'] = $this->normalizeFeatures($request);

        // লাইভ টেস্ট ড্রাইভ ও প্যাকেজ প্রাইসিং
        $input['demo_admin_link'] = $request->input('demo_admin_link');
        $input['demo_admin_email'] = $request->input('demo_admin_email');
        $input['demo_admin_password'] = $request->input('demo_admin_password');
        if ($request->has('pricing_tiers')) {
            $input['pricing_tiers'] = $this->normalizePricingTiers($request);
        }

        Product::create($input);

        return redirect()->route('admin.products.index')->with('success', 'ওয়েবসাইট সফলভাবে যুক্ত হয়েছে।');
    }

    public function edit($id) {
        $data = Product::findOrFail($id);
        return view('admin.product.edit', compact('data'));
    }

    // ৩. আপডেট মেথড (SEO ও ওয়েবসাইট কালেকশন লজিক সহ)
    public function update(Request $request, $id) {
        $data = Product::findOrFail($id);

        $request->validate([
            'name'  => 'required|unique:products,name,' . $id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric',
            'google_drive_file_id' => 'required_if:is_digital,1|nullable|string|max:500',
            'download_limit' => 'nullable|integer|min:1|max:50',
            'tutorials' => 'nullable|array|max:30',
            'tutorials.*.title' => 'nullable|string|max:255|required_with:tutorials.*.url',
            'tutorials.*.url' => 'nullable|url|max:2048|required_with:tutorials.*.title',
        ]);

        $input = $request->all();

        if ($file = $request->file('photo')) {
            admin_delete_image('assets/images/products', $data?->photo);
            $input['photo'] = admin_upload_image($file, 'assets/images/products');
        }

        // --- SEO লজিক (আপডেটের সময়) ---
        $input['meta_title'] = !empty($request->meta_title) ? $request->meta_title : $request->name;
        
        $input['meta_keyword'] = !empty($request->meta_keyword) ? $request->meta_keyword : str_replace(' ', ', ', $request->name);

        if (empty($request->meta_description)) {
            $clean_text = strip_tags($request->description . ' ' . $request->details);
            $input['meta_description'] = Str::limit($clean_text, 160);
        } else {
            $input['meta_description'] = $request->meta_description;
        }

        $input['slug'] = Str::slug($request->name);
        $input['status'] = $request->boolean('status', true);
        $input['is_digital'] = $request->boolean('is_digital');
        $input['download_limit'] = max(1, (int) $request->input('download_limit', $data->download_limit ?: 3));
        $input['google_drive_file_id'] = $this->resolveDriveFileId($request) ?: $data->google_drive_file_id;
        $input['digital_file_name'] = $request->input('digital_file_name');
        $input['tutorials'] = $this->normalizeTutorials($request);

        // কালেকশন ফিল্ডস
        $input['category_key'] = $request->input('category_key') ?: $data->category_key ?: 'general';
        $input['category_name'] = $request->input('category_name') ?: $data->category_name ?: 'অন্যান্য';
        $input['badge_text'] = $request->input('badge_text');
        $input['badge_class'] = $request->input('badge_class', 'cd-badge--hot');
        $input['license_type'] = $request->input('license_type', 'আজীবন লাইসেন্স');
        $input['sort_order'] = (int) $request->input('sort_order', 0);
        $input['features'] = $this->normalizeFeatures($request);

        // লাইভ টেস্ট ড্রাইভ ও প্যাকেজ প্রাইসিং
        $input['demo_admin_link'] = $request->input('demo_admin_link');
        $input['demo_admin_email'] = $request->input('demo_admin_email');
        $input['demo_admin_password'] = $request->input('demo_admin_password');
        if ($request->has('pricing_tiers')) {
            $input['pricing_tiers'] = $this->normalizePricingTiers($request);
        }

        $data->update($input);

        return redirect()->route('admin.products.index')->with('success', 'ওয়েবসাইট সফলভাবে আপডেট হয়েছে।');
    }

    public function destroy($id) {
        $data = Product::findOrFail($id);
        admin_delete_image('assets/images/products', $data?->photo);
        $data->delete();
        return redirect()->route('admin.products.index')->with('success', 'ওয়েবসাইট সফলভাবে ডিলিট করা হয়েছে।');
    }

    private function resolveDriveFileId(Request $request): ?string
    {
        $raw = trim((string) $request->input('google_drive_file_id', ''));
        if ($raw === '') {
            return null;
        }

        return app(\App\Services\GoogleDriveService::class)->extractFileId($raw);
    }

    private function normalizeTutorials(Request $request): array
    {
        $raw = $request->input('tutorials', []);
        if (!is_array($raw)) {
            return [];
        }

        return collect($raw)
            ->filter(fn ($item) => is_array($item) && !empty($item['url']))
            ->map(function ($item) {
                return [
                    'title' => trim((string) ($item['title'] ?? '')),
                    'url' => trim((string) ($item['url'] ?? '')),
                ];
            })
            ->values()
            ->all();
    }

    private function normalizeFeatures(Request $request): array
    {
        $raw = $request->input('features', []);
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $raw = $decoded;
            } else {
                $raw = explode("\n", str_replace(["\r\n", "\r"], "\n", $raw));
            }
        }
        return collect($raw)
            ->map(function ($item) {
                if (is_array($item)) {
                    return trim((string) ($item['value'] ?? $item['text'] ?? reset($item) ?? ''));
                }
                return trim((string) $item);
            })
            ->filter(fn ($item) => $item !== '')
            ->values()
            ->all();
    }

    private function normalizePricingTiers(Request $request): ?array
    {
        $raw = $request->input('pricing_tiers');
        if (empty($raw)) {
            return null;
        }
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $raw = $decoded;
            } else {
                return null;
            }
        }

        if (!is_array($raw)) {
            return null;
        }

        $tiers = [];
        foreach ($raw as $t) {
            if (!is_array($t)) continue;
            $features = $t['features'] ?? [];
            if (is_string($features)) {
                $features = array_values(array_filter(array_map('trim', explode("\n", str_replace(["\r\n", "\r"], "\n", $features)))));
            } elseif (is_array($features)) {
                $features = array_values(array_filter(array_map('trim', $features)));
            }
            $t['features'] = (array) $features;
            $tiers[] = $t;
        }

        return !empty($tiers) ? $tiers : null;
    }
}