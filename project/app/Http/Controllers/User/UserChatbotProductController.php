<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserChatbotProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserChatbotProductController extends Controller
{
    // Note: This returns just the filename, not the full path
    // Display using: asset('uploads/chatbot-products/' . $filename)
    public function index()
    {
        $products = UserChatbotProduct::where('user_id', Auth::id())
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view('user.chatbot.products.index', compact('products'));
    }

    public function create()
    {
        return view('user.chatbot.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'product_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $originalName = $file->getClientOriginalName();
            $slugName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
            $name = ($slugName ?: 'product') . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/chatbot-products/', $name);
            $imagePath = $name; // শুধু filename save করি, full path না
        } elseif ($request->filled('image')) {
            $imagePath = $request->image; // URL এর জন্য
        }

        UserChatbotProduct::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'product_url' => $request->product_url,
            'sort_order' => (int) ($request->sort_order ?? 0),
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('user.chatbot.products.index')
            ->with('success', 'প্রোডাক্ট সফলভাবে যোগ হয়েছে।');
    }

    public function edit($id)
    {
        $product = UserChatbotProduct::where('user_id', Auth::id())->findOrFail($id);
        return view('user.chatbot.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = UserChatbotProduct::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'product_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image_file')) {
            // পুরনো ছবি মুছে ফেলুন
            if ($product->image && !str_starts_with($product->image, 'http') && file_exists(public_path('uploads/chatbot-products/'.$product->image))) {
                @unlink(public_path('uploads/chatbot-products/'.$product->image));
            }
            
            $file = $request->file('image_file');
            $originalName = $file->getClientOriginalName();
            $slugName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
            $name = ($slugName ?: 'product') . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move('uploads/chatbot-products/', $name);
            $imagePath = $name; // শুধু filename save করি
        } elseif ($request->filled('image')) {
            $imagePath = $request->image; // URL এর জন্য
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'product_url' => $request->product_url,
            'sort_order' => (int) ($request->sort_order ?? 0),
            'status' => $request->boolean('status', true),
        ]);

        return redirect()->route('user.chatbot.products.index')
            ->with('success', 'প্রোডাক্ট আপডেট হয়েছে।');
    }

    public function destroy($id)
    {
        $product = UserChatbotProduct::where('user_id', Auth::id())->findOrFail($id);
        
        // ছবি মুছে ফেলুন
        if ($product->image && !str_starts_with($product->image, 'http')) {
            $imagePath = public_path('uploads/chatbot-products/'.$product->image);
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }
        
        $product->delete();
        return redirect()->route('user.chatbot.products.index')
            ->with('success', 'প্রোডাক্ট মুছে ফেলা হয়েছে।');
    }
}
