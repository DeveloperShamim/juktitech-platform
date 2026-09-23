<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Services\HomepageContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    // ১. সব সার্ভিস লিস্ট দেখার জন্য
    public function index()
    {
        $services = Service::orderBy('id', 'desc')->get();
        // সেকশন টেক্সট (টাইটেল ও সাব-টাইটেল) ডাটাবেস থেকে আনা
        $section_text = DB::table('section_contents')->where('section_key', 'service_section')->first();
        
        return view('admin.service.index', compact('services', 'section_text'));
    }

    // ২. নতুন সার্ভিস তৈরির ফর্ম
    public function create()
    {
        return view('admin.service.create');
    }

    // ৩. ডাটা সেভ করা
    public function store(Request $request)
    {
        $rules = [
            'title'       => 'required|max:191',
            'description' => 'required',
            'icon'        => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Service::create($request->all());

        return redirect()->route('admin.service.index')->with('success', 'নতুন সার্ভিস সফলভাবে যোগ হয়েছে।');
    }

    // ৪. এডিট ফর্ম
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.service.edit', compact('service'));
    }

    // ৫. আপডেট করা
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $rules = [
            'title'       => 'required|max:191',
            'description' => 'required',
            'icon'        => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $service->update($request->all());

        return redirect()->route('admin.service.index')->with('success', 'সার্ভিস সফলভাবে আপডেট হয়েছে।');
    }

    // ৬. ডিলিট করা
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.service.index')->with('success', 'সার্ভিস সফলভাবে ডিলিট করা হয়েছে।');
    }

    // --- সেকশন টেক্সট এডিট অপশন (Service Section Title) ---

    public function editContent()
    {
        $content = DB::table('section_contents')->where('section_key', 'service_section')->first();
        return view('admin.service.edit_content', compact('content'));
    }

    public function updateContent(Request $request)
    {
        $rules = ['title' => 'required|max:191', 'subtitle' => 'required|max:191'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::table('section_contents')->where('section_key', 'service_section')->update([
            'title'      => $request->title,
            'subtitle'   => $request->subtitle,
            'updated_at' => now()
        ]);

        return redirect()->route('admin.service.index')->with('success', 'সেকশন টেক্সট সফলভাবে আপডেট হয়েছে।');
    }

    // --- নতুন যোগ করা মেথড (About Section Edit - আপনার অনলাইন ব্যবসার সহযোগী) ---

    // ৯. About সেকশন এডিট পেজ
    public function editAboutSection(HomepageContentService $homepage)
    {
        $data = DB::table('about_section_contents')->where('id', 1)->first();
        $hero = $homepage->hero();

        return view('admin.service.edit_about', compact('data', 'hero'));
    }

    public function updateAboutSection(Request $request, HomepageContentService $homepage)
    {
        $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
            'hero_badge' => 'nullable|max:191',
            'mockup_type' => 'nullable|in:wireframe,image',
        ]);

        $stats = [];
        foreach ($request->input('stat_value', []) as $i => $value) {
            $value = trim((string) $value);
            if ($value === '') {
                continue;
            }
            $stats[] = [
                'icon' => $request->input("stat_icon.$i", 'fas fa-check'),
                'value' => $value,
                'label' => $request->input("stat_label.$i", ''),
            ];
        }

        $buttons = [];
        foreach ($request->input('btn_text', []) as $i => $text) {
            $text = trim((string) $text);
            if ($text === '') {
                continue;
            }
            $buttons[] = [
                'text' => $text,
                'url' => $request->input("btn_url.$i", '#'),
                'style' => $request->input("btn_style.$i", 'primary'),
                'icon' => $request->input("btn_icon.$i", ''),
            ];
        }

        $categories = array_values(array_filter(array_map('trim', $request->input('mockup_categories', []))));

        $products = [];
        foreach ($request->input('product_price', []) as $i => $price) {
            $price = trim((string) $price);
            if ($price === '') {
                continue;
            }
            $products[] = [
                'badge' => $request->input("product_badge.$i", ''),
                'badge_class' => $request->input("product_badge_class.$i", ''),
                'price' => $price,
                'rating' => $request->input("product_rating.$i", ''),
            ];
        }

        $mockup = [
            'type' => $request->input('mockup_type', 'wireframe'),
            'float_1' => $request->input('mockup_float_1'),
            'float_2' => $request->input('mockup_float_2'),
            'float_3' => $request->input('mockup_float_3'),
            'browser_url' => $request->input('mockup_browser_url'),
            'search_placeholder' => $request->input('mockup_search_placeholder'),
            'cart_count' => $request->input('mockup_cart_count'),
            'sale_text' => $request->input('mockup_sale_text'),
            'banner_btn' => $request->input('mockup_banner_btn'),
            'categories' => $categories ?: $homepage->defaultHeroData()['mockup']['categories'],
            'products' => $products ?: $homepage->defaultHeroData()['mockup']['products'],
            'checkout_text' => $request->input('mockup_checkout_text'),
            'checkout_btn' => $request->input('mockup_checkout_btn'),
        ];

        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'hero_badge' => $request->hero_badge,
            'hero_data' => $homepage->encodeHeroData([
                'stats' => $stats,
                'buttons' => $buttons,
                'mockup' => $mockup,
            ]),
            'updated_at' => now(),
        ];

        if ($request->hasFile('image')) {
            admin_delete_image('assets/images/about', DB::table('about_section_contents')->where('id', 1)->value('image'));
            $data['image'] = admin_upload_image($request->file('image'), 'assets/images/about');
        }

        if ($request->hasFile('hero_mockup_image')) {
            admin_delete_image('assets/images/hero', DB::table('about_section_contents')->where('id', 1)->value('hero_mockup_image'));
            $data['hero_mockup_image'] = admin_upload_image($request->file('hero_mockup_image'), 'assets/images/hero');
        }

        DB::table('about_section_contents')->where('id', 1)->update($data);

        return redirect()->back()->with('success', 'হিরো ও About সেকশন সফলভাবে আপডেট হয়েছে।');
    }
}