<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller {
    public function __construct()
    {
        $this->middleware(['auth:admin', 'admin.otp']);
    }

    public function index() {
        $testimonials = Testimonial::orderBy('id', 'desc')->get();
        return view('admin.testimonial.index', compact('testimonials'));
    }

    public function create() {
        return view('admin.testimonial.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required', 'photo' => 'required|image']);
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $input['photo'] = admin_upload_image($file, 'assets/images/testimonials');
        }
        Testimonial::create($input);
        return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial added!');
    }

    public function edit($id) {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    public function update(Request $request, $id) {
        $testimonial = Testimonial::findOrFail($id);
        $input = $request->all();
        if ($file = $request->file('photo')) {
            admin_delete_image('assets/images/testimonials', $testimonial->photo ?? null);
            $input['photo'] = admin_upload_image($file, 'assets/images/testimonials');
        }
        $testimonial->update($input);
        return redirect()->route('admin.testimonial.index')->with('success', 'Updated!');
    }

    public function destroy($id) {
        $testimonial = Testimonial::findOrFail($id);
        admin_delete_image('assets/images/testimonials', $testimonial->photo ?? null);
        $testimonial->delete();
        return redirect()->back()->with('success', 'Deleted!');
    }
}