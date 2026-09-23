<?php

namespace App\Http\Controllers;

use App\Models\WhyChooseUs;
use Illuminate\Http\Request;

class WhyChooseUsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'admin.otp']);
    }

    public function index()
    {
        $features = WhyChooseUs::orderBy('id', 'desc')->get();
        return view('admin.why_choose_us.index', compact('features'));
    }

    public function create()
    {
        return view('admin.why_choose_us.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'icon'        => 'required',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $input = $request->all();

        if ($file = $request->file('image')) {
            $input['image'] = admin_upload_image($file, 'assets/images/why-choose-us');
        }

        WhyChooseUs::create($input);

        return redirect()->route('admin.why-choose-us.index')->with('success', 'সফলভাবে যোগ হয়েছে!');
    }

    public function edit($id)
    {
        $data = WhyChooseUs::findOrFail($id);
        return view('admin.why_choose_us.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = WhyChooseUs::findOrFail($id);

        $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'icon'        => 'required',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $input = $request->all();

        if ($file = $request->file('image')) {
            admin_delete_image('assets/images/why-choose-us', $data?->image);
            $input['image'] = admin_upload_image($file, 'assets/images/why-choose-us');
        }

        $data->update($input);

        return redirect()->route('admin.why-choose-us.index')->with('success', 'সফলভাবে আপডেট করা হয়েছে!');
    }

    public function destroy($id)
    {
        $data = WhyChooseUs::findOrFail($id);

        admin_delete_image('assets/images/why-choose-us', $data?->image);

        $data->delete();

        return back()->with('success', 'সফলভাবে ডিলিট করা হয়েছে!');
    }
}
