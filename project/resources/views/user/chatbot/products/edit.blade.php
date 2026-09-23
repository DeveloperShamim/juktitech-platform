@extends('layouts.front')
@section('title', 'প্রোডাক্ট এডিট')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #edf2f7; }
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; font-weight: 500; }
    .menu-link:hover, .menu-link.active { background: #f1f5f9; color: #4e73df; }
    .page-header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 25px; color: #fff; margin-bottom: 25px; }
    .stat-card { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #edf2f7; }
</style>

<div class="section-padding py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="sidebar-card py-3">
                    <a href="{{ route('user.chatbot.products.index') }}" class="menu-link"><i class="fa-solid fa-arrow-left me-2"></i>প্রোডাক্ট লিস্ট</a>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="page-header">
                    <h2 class="mb-0"><i class="fas fa-edit me-2"></i>প্রোডাক্ট এডিট করুন</h2>
                </div>

                <div class="stat-card">
                    <form action="{{ route('user.chatbot.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-bold">প্রোডাক্টের নাম *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required maxlength="255">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">বিবরণ</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                            @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">মূল্য (৳) *</label>
                            <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" min="0" step="0.01" required>
                            @error('price') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        @if($product->image)
                        <div class="mb-2">
                            <label class="form-label fw-bold">বর্তমান ছবি</label>
                            <div>
                                <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('uploads/chatbot-products/'.$product->image) }}" alt="" style="max-width:120px;max-height:120px;object-fit:cover;border-radius:8px;">
                            </div>
                        </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label fw-bold">নতুন ছবি আপলোড</label>
                            <input type="file" name="image_file" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                            <small class="text-muted">JPG, PNG, GIF বা WebP (সর্বোচ্চ ২MB) — খালি রাখলে আগের ছবি থাকবে</small>
                            @error('image_file') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">অথবা ছবির URL</label>
                            <input type="text" name="image" class="form-control" value="{{ old('image', $product->image) }}" placeholder="https://...">
                            @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">প্রোডাক্ট লিংক</label>
                            <input type="url" name="product_url" class="form-control" value="{{ old('product_url', $product->product_url) }}">
                            @error('product_url') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">সর্ট অর্ডার</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $product->sort_order) }}" min="0">
                        </div>
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" value="1" class="form-check-input" id="status" {{ old('status', $product->status) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">চালু রাখুন</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>আপডেট করুন</button>
                        <a href="{{ route('user.chatbot.products.index') }}" class="btn btn-outline-secondary">বাতিল</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
