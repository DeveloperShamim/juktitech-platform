@extends('layouts.front')

@section('contents')
<section class="consultation-section py-5 bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg rounded-lg overflow-hidden">
                    <div class="card-header text-white text-center py-4 bg-primary">
                        <h3 class="font-weight-bold mb-0">ফ্রি কনসালটেন্সি বুক করুন</h3>
                        <p class="small mb-0 opacity-75">আমাদের এক্সপার্ট টিম আপনাকে কল করবে</p>
                    </div>
                    <div class="card-body p-4 p-lg-5">

                        @if(Session::has('success'))
                            <div class="alert alert-success border-0 shadow-sm mb-4">
                                {{ Session::get('success') }}
                            </div>
                        @endif

                        <form action="{{ route('frontend.consultancy.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">আপনার নাম *</label>
                                <input type="text" name="name" class="form-control p-3 border-0 bg-light rounded" placeholder="পুরো নাম লিখুন" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">ফোন নাম্বার *</label>
                                    <input type="text" name="phone" class="form-control p-3 border-0 bg-light rounded" placeholder="আপনার ফোন" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label font-weight-bold">ইমেইল</label>
                                    <input type="email" name="email" class="form-control p-3 border-0 bg-light rounded" placeholder="আপনার ইমেইল">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label font-weight-bold">আপনার বার্তা (ঐচ্ছিক)</label>
                                <textarea name="message" class="form-control p-3 border-0 bg-light rounded" rows="4" placeholder="আপনার প্রয়োজনীয় সেবা সম্পর্কে লিখুন..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-lg w-100 text-white font-weight-bold py-3 shadow btn-primary rounded">
                                সাবমিট করুন <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
