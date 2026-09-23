<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotPackage;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class ChatbotPackageController extends Controller
{
    /**
     * Display a listing of chatbot packages
     * 
     * সব chatbot packages দেখানো
     */
    public function index()
    {
        $packages = ChatbotPackage::orderBy('sort_order', 'asc')->get();
        return view('admin.chatbot.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package
     * 
     * নতুন package তৈরির ফর্ম
     */
    public function create()
    {
        return view('admin.chatbot.packages.create');
    }

    /**
     * Store a newly created package
     * 
     * নতুন package সেভ করা
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'page_limit' => 'required|integer|min:1',
            'message_limit' => 'required|integer|min:100',
            'auto_reply_limit' => 'required|integer|min:1',
            'comment_automation_limit' => 'required|integer|min:1',
            'product_carousel' => 'nullable|boolean',
            'analytics' => 'nullable|boolean',
            'broadcast' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Boolean fields handle করা
        $validated['product_carousel'] = $request->has('product_carousel');
        $validated['analytics'] = $request->has('analytics');
        $validated['broadcast'] = $request->has('broadcast');
        $validated['is_popular'] = $request->has('is_popular');
        $validated['status'] = $request->has('status');

        ChatbotPackage::create($validated);

        Toastr::success('Chatbot package created successfully!', 'Success');
        return redirect()->route('admin.chatbot.packages.index');
    }

    /**
     * Show the form for editing a package
     * 
     * Package edit করার ফর্ম
     */
    public function edit($id)
    {
        $package = ChatbotPackage::findOrFail($id);
        return view('admin.chatbot.packages.edit', compact('package'));
    }

    /**
     * Update the specified package
     * 
     * Package আপডেট করা
     */
    public function update(Request $request, $id)
    {
        $package = ChatbotPackage::findOrFail($id);

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'page_limit' => 'required|integer|min:1',
            'message_limit' => 'required|integer|min:100',
            'auto_reply_limit' => 'required|integer|min:1',
            'comment_automation_limit' => 'required|integer|min:1',
            'product_carousel' => 'nullable|boolean',
            'analytics' => 'nullable|boolean',
            'broadcast' => 'nullable|boolean',
            'is_popular' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        // Boolean fields handle করা
        $validated['product_carousel'] = $request->has('product_carousel');
        $validated['analytics'] = $request->has('analytics');
        $validated['broadcast'] = $request->has('broadcast');
        $validated['is_popular'] = $request->has('is_popular');
        $validated['status'] = $request->has('status');

        $package->update($validated);

        Toastr::success('Chatbot package updated successfully!', 'Success');
        return redirect()->route('admin.chatbot.packages.index');
    }

    /**
     * Remove the specified package
     * 
     * Package ডিলিট করা
     */
    public function destroy($id)
    {
        $package = ChatbotPackage::findOrFail($id);
        $package->delete();

        Toastr::success('Chatbot package deleted successfully!', 'Success');
        return redirect()->route('admin.chatbot.packages.index');
    }

    /**
     * Toggle package status
     * 
     * Package এর status চেঞ্জ করা (Active/Inactive)
     */
    public function toggleStatus($id)
    {
        $package = ChatbotPackage::findOrFail($id);
        $package->status = !$package->status;
        $package->save();

        $status = $package->status ? 'activated' : 'deactivated';
        Toastr::success("Package {$status} successfully!", 'Success');
        
        return redirect()->route('admin.chatbot.packages.index');
    }
}
