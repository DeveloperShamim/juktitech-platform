@extends('layouts.front')
@section('title', 'অটো রিপ্লাই এডিট')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    .stat-card { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #edf2f7; }
    .page-header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 30px; color: #fff; margin-bottom: 30px; }
    .reply-type-btn { padding: 15px; border: 2px solid #e5e7eb; border-radius: 12px; text-align: center; cursor: pointer; transition: 0.3s; }
    .reply-type-btn:hover { border-color: #4e73df; background: #f8f9fc; }
    .reply-type-btn.active { border-color: #4e73df; background: #e7f0ff; }
</style>

<script>
    function selectReplyType(type) {
        document.querySelectorAll('.reply-type-btn').forEach(btn => btn.classList.remove('active'));
        var btn = document.querySelector('[data-type="' + type + '"]');
        if (btn) btn.classList.add('active');
        document.getElementById('reply_type').value = type;
        document.querySelectorAll('.reply-field').forEach(field => field.style.display = 'none');
        var el = document.getElementById(type + '-field');
        if (el) el.style.display = 'block';
    }
</script>

<div class="section-padding py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="page-header">
                    <h2 class="mb-2 fw-bold"><i class="fas fa-edit me-2"></i>অটো রিপ্লাই এডিট করুন</h2>
                    <p class="mb-0 text-white-50">পেজ: {{ $reply->facebookPage->page_name }}</p>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif

                <div class="stat-card">
                    <form action="{{ route('user.chatbot.reply.update', $reply->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- PUT মেথড ও সঠিক CSRF টোকেন ফর্ম সাবমিটের জন্য প্রয়োজন --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">ট্রিগার কীওয়ার্ড</label>
                            <input type="text" name="trigger_keyword" class="form-control" value="{{ old('trigger_keyword', $reply->keyword) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">রিপ্লাই টাইপ</label>
                            <input type="hidden" name="reply_type" id="reply_type" value="{{ old('reply_type', $reply->reply_type) }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="reply-type-btn {{ $reply->reply_type == 'text' ? 'active' : '' }}" data-type="text" onclick="selectReplyType('text')">
                                        <i class="fas fa-font fa-2x mb-2 text-primary"></i>
                                        <div class="fw-bold">টেক্সট</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="reply-type-btn {{ $reply->reply_type == 'image' ? 'active' : '' }}" data-type="image" onclick="selectReplyType('image')">
                                        <i class="fas fa-image fa-2x mb-2 text-success"></i>
                                        <div class="fw-bold">ইমেজ</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="reply-type-btn {{ $reply->reply_type == 'product_carousel' ? 'active' : '' }}" data-type="product_carousel" onclick="selectReplyType('product_carousel')">
                                        <i class="fas fa-shopping-bag fa-2x mb-2 text-warning"></i>
                                        <div class="fw-bold">প্রোডাক্ট</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="text-field" class="reply-field mb-3" style="display: {{ $reply->reply_type == 'text' ? 'block' : 'none' }};">
                            <label class="form-label fw-bold">রিপ্লাই টেক্সট</label>
                            <textarea name="reply_text" class="form-control" rows="4">{{ old('reply_text', $reply->reply_text) }}</textarea>
                        </div>
                        <div id="image-field" class="reply-field mb-3" style="display: {{ $reply->reply_type == 'image' ? 'block' : 'none' }};">
                            <label class="form-label fw-bold">ইমেজ URL</label>
                            <input type="url" name="image_url" class="form-control" value="{{ old('image_url', $reply->attachment['image_url'] ?? '') }}">
                        </div>
                        <div id="product_carousel-field" class="reply-field mb-3" style="display: {{ $reply->reply_type == 'product_carousel' ? 'block' : 'none' }};">
                            <label class="form-label fw-bold">প্রোডাক্ট নির্বাচন করুন</label>
                            <select name="product_ids[]" class="form-select" multiple size="5">
                                @php $products = \App\Models\UserChatbotProduct::where('user_id', Auth::id())->where('status', true)->orderBy('sort_order')->orderBy('id', 'desc')->get(); @endphp
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} - ৳{{ number_format($product->price) }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">একাধিক নির্বাচনে Ctrl চাপুন। <a href="{{ route('user.chatbot.products.create') }}">প্রোডাক্ট যোগ করুন</a></small>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i>আপডেট করুন</button>
                            <a href="{{ route('user.chatbot.reply.setup') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>ফিরে যান</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
