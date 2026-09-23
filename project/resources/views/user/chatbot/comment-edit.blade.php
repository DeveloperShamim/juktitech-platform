@extends('layouts.front')
@section('title', 'কমেন্ট অটোমেশন এডিট')
@section('contents')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
    .stat-card { background: #fff; border-radius: 20px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #edf2f7; }
    .page-header { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 20px; padding: 30px; color: #fff; margin-bottom: 30px; }
</style>

<div class="section-padding py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="page-header">
                    <h2 class="mb-2 fw-bold"><i class="fas fa-edit me-2"></i>কমেন্ট অটোমেশন এডিট করুন</h2>
                    <p class="mb-0 text-white-50">পেজ: {{ $automation->facebookPage->page_name }}</p>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif

                <div class="stat-card">
                    <form action="{{ route('user.chatbot.comment.update', $automation->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- PUT ও CSRF ফর্ম সাবমিটের জন্য প্রয়োজন --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">ট্রিগার শব্দ</label>
                            <input type="text" name="trigger_word" class="form-control" value="{{ old('trigger_word', $automation->trigger_word) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">পাবলিক রিপ্লাই (কমেন্টে)</label>
                            <textarea name="reply_comment" class="form-control" rows="3">{{ old('reply_comment', $automation->reply_comment) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">প্রাইভেট মেসেজ (ইনবক্সে)</label>
                            <textarea name="private_reply" class="form-control" rows="3">{{ old('private_reply', $automation->private_reply) }}</textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning text-white"><i class="fas fa-save me-2"></i>আপডেট করুন</button>
                            <a href="{{ route('user.chatbot.comment.automation') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>ফিরে যান</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
