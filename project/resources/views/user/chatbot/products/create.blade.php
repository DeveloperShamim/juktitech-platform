@extends('layouts.front')
@section('title', 'প্রোডাক্ট যোগ করুন')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    .sidebar-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #edf2f7; }
    .menu-link { display: flex; align-items: center; padding: 14px 20px; color: #64748b; text-decoration: none; border-radius: 12px; margin: 5px 15px; font-weight: 500; }
    .menu-link:hover, .menu-link.active { background: #f1f5f9; color: #4e73df; }
    .submenu-container { margin: 2px 15px; }
    .ps-5 { padding-left: 45px !important; font-size: 14px !important; }
    .page-header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 25px; color: #fff; margin-bottom: 25px; }
    .stat-card { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #edf2f7; }
</style>

<div class="section-padding py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="sidebar-card py-3">
                    <a href="{{ route('user.chatbot.products.index') }}" class="menu-link"><i class="fa-solid fa-arrow-left me-2"></i>প্রোডাক্ট লিস্ট</a>
                    <a href="{{ route('user.chatbot.settings') }}" class="menu-link ps-5">চ্যাটবট ড্যাশবোর্ড</a>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="page-header">
                    <h2 class="mb-0"><i class="fas fa-plus me-2"></i>নতুন প্রোডাক্ট যোগ করুন</h2>
                </div>

                <div class="stat-card">
                    <form action="{{ route('user.chatbot.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">প্রোডাক্টের নাম *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required maxlength="255" placeholder="উদা: টি-শার্ট">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">বিবরণ</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="সংক্ষিপ্ত বিবরণ">{{ old('description') }}</textarea>
                            @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">মূল্য (৳) *</label>
                            <input type="number" name="price" class="form-control" value="{{ old('price', 0) }}" min="0" step="0.01" required>
                            @error('price') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">প্রোডাক্ট ছবি আপলোড</label>
                            <input type="file" name="image_file" class="form-control" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                            <small class="text-muted">JPG, PNG, GIF বা WebP (সর্বোচ্চ ২MB)</small>
                            @error('image_file') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">অথবা ছবির URL</label>
                            <input type="text" name="image" class="form-control" value="{{ old('image') }}" placeholder="https://... (আপলোড না করলে)">
                            @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">প্রোডাক্ট লিংক (অর্ডার/বিস্তারিত)</label>
                            <input type="url" name="product_url" class="form-control" value="{{ old('product_url') }}" placeholder="https://...">
                            @error('product_url') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">সর্ট অর্ডার (০ = প্রথম)</label>
                            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                        </div>
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" value="1" class="form-check-input" id="status" {{ old('status', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">চালু রাখুন (ক্যারোজেলে দেখাবে)</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>সেভ করুন</button>
                        <a href="{{ route('user.chatbot.products.index') }}" class="btn btn-outline-secondary">বাতিল</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
