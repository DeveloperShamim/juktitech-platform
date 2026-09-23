<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HomepageContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomepageController extends Controller
{
    public function __construct(private HomepageContentService $homepage)
    {
    }

    public function sections()
    {
        $sections = $this->homepage->sections();
        $labels = $this->sectionLabels();

        return view('admin.homepage.sections', compact('sections', 'labels'));
    }

    public function updateSection(Request $request, string $key)
    {
        $allowed = array_keys($this->homepage->defaultSections());

        if (!in_array($key, $allowed, true)) {
            abort(404);
        }

        $request->validate([
            'badge' => 'nullable|max:191',
            'title' => 'required',
            'description' => 'nullable',
        ]);

        $extra = $this->buildExtraJson($request, $key);

        $payload = [
            'section_key' => $key,
            'badge' => $request->badge,
            'title' => $request->title,
            'subtitle' => $request->description,
            'description' => $request->description,
            'extra_json' => json_encode($extra, JSON_UNESCAPED_UNICODE),
            'updated_at' => now(),
        ];

        $exists = DB::table('section_contents')->where('section_key', $key)->exists();

        if ($exists) {
            DB::table('section_contents')->where('section_key', $key)->update($payload);
        } else {
            $payload['created_at'] = now();
            DB::table('section_contents')->insert($payload);
        }

        return redirect()->route('admin.homepage.sections', ['tab' => $key])
            ->with('success', 'সেকশন সফলভাবে আপডেট হয়েছে।');
    }

    private function buildExtraJson(Request $request, string $key): array
    {
        if ($key === 'service_section') {
            $highlights = [];
            foreach ($request->input('highlight_text', []) as $i => $text) {
                $text = trim((string) $text);
                if ($text === '') {
                    continue;
                }
                $highlights[] = [
                    'icon' => $request->input("highlight_icon.$i", 'fas fa-check'),
                    'text' => $text,
                ];
            }

            return ['highlights' => $highlights];
        }

        if ($key === 'steps_section') {
            $steps = [];
            foreach ($request->input('step_title', []) as $i => $title) {
                $title = trim((string) $title);
                if ($title === '') {
                    continue;
                }
                $steps[] = [
                    'num' => $request->input("step_num.$i", (string) ($i + 1)),
                    'icon' => $request->input("step_icon.$i", 'fas fa-check'),
                    'color' => $request->input("step_color.$i", 'is-purple'),
                    'title' => $title,
                    'desc' => $request->input("step_desc.$i", ''),
                ];
            }

            return ['steps' => $steps];
        }

        if ($key === 'portfolio_section') {
            return [
                'filter_all' => $request->input('filter_all', 'সকল'),
                'more_text' => $request->input('more_text'),
                'more_url' => $request->input('more_url'),
            ];
        }

        if ($key === 'team_section') {
            return [
                'more_text' => $request->input('more_text'),
                'more_url' => $request->input('more_url'),
            ];
        }

        if ($key === 'cta_section') {
            return [
                'call_text' => $request->input('call_text'),
                'message_text' => $request->input('message_text'),
            ];
        }

        return [];
    }

    private function sectionLabels(): array
    {
        return [
            'service_section' => 'সার্ভিস সেকশন',
            'pricing_section' => 'প্রাইসিং সেকশন',
            'choose_section' => 'Why Choose Us সেকশন',
            'portfolio_section' => 'পোর্টফোলিও সেকশন',
            'reviews_section' => 'রিভিউ সেকশন',
            'steps_section' => '৩ ধাপ সেকশন',
            'team_section' => 'টিম সেকশন',
            'brands_section' => 'ব্র্যান্ড সেকশন',
            'cta_section' => 'CTA সেকশন',
        ];
    }
}
