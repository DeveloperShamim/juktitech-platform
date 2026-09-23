<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use App\Services\FrontendThemeService;
use Illuminate\Http\Request;

class FrontendThemeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function edit(FrontendThemeService $themeService)
    {
        $gs = GeneralSettings::findOrFail(1);
        $theme = $themeService->resolve($gs);
        $labels = $themeService->labels();
        $fields = $themeService->adminFields();

        return view('admin.frontend-theme.edit', compact('gs', 'theme', 'labels', 'fields'));
    }

    public function update(Request $request, FrontendThemeService $themeService)
    {
        $gs = GeneralSettings::findOrFail(1);

        $request->validate([
            'primary' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $payload = [];
        foreach ($themeService->adminFields() as $key) {
            $value = $request->input($key);
            if ($value) {
                $payload[$key] = $value;
            }
        }

        $gs->theme_color = $request->input('primary', $gs->theme_color);
        $gs->footer_color = $request->input('footer_end', $gs->footer_color);
        $gs->copyright_color = $request->input('footer_start', $gs->copyright_color);
        $gs->save();

        $themeService->saveSettings($payload);

        return redirect()->back()->with('success', 'ফ্রন্টএন্ড থিম রঙ সফলভাবে আপডেট হয়েছে।');
    }

    public function reset(FrontendThemeService $themeService)
    {
        $gs = GeneralSettings::findOrFail(1);
        $defaults = $themeService->defaults();

        $gs->theme_color = $defaults['primary'];
        $gs->footer_color = $defaults['footer_end'];
        $gs->copyright_color = $defaults['footer_start'];
        $gs->save();

        $themeService->resetSettings();

        return redirect()->route('admin.frontend-theme.edit')->with('success', 'ডিফল্ট থিম রঙে রিসেট করা হয়েছে।');
    }
}
