@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-pen',
        'title' => __('Edit Chatbot Package'),
        'subtitle' => __('Update chatbot package settings'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Chatbot Packages'), 'url' => route('admin.chatbot.packages.index')],
            ['label' => __('Edit'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-both')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-pen"></i> {{ __('Package Details') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <form action="{{ route('admin.chatbot.packages.update', $package->id) }}" method="POST">
                        @csrf

                        <div class="row">
                            {{-- Package Name --}}
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Package Name (English) <span class="text-danger">*</span></label>
                                    <input type="text" class="input-field" id="name" name="name" placeholder="e.g., Basic, Premium, Pro" value="{{ old('name', $package->name) }}" required>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Package Name Bangla --}}
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name_bn">Package Name (বাংলা)</label>
                                    <input type="text" class="input-field" id="name_bn" name="name_bn" placeholder="e.g., বেসিক, প্রিমিয়াম" value="{{ old('name_bn', $package->name_bn) }}">
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="input-field" id="description" name="description" rows="3" placeholder="Package features and benefits">{{ old('description', $package->description) }}</textarea>
                                </div>
                            </div>

                            {{-- Price --}}
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="price">Price (৳) <span class="text-danger">*</span></label>
                                    <input type="number" class="input-field" id="price" name="price" step="0.01" min="0" placeholder="0.00" value="{{ old('price', $package->price) }}" required>
                                    @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Duration --}}
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="duration_days">Duration (Days) <span class="text-danger">*</span></label>
                                    <select class="input-field" id="duration_days" name="duration_days" required>
                                        <option value="7" {{ old('duration_days', $package->duration_days) == 7 ? 'selected' : '' }}>7 Days (Trial)</option>
                                        <option value="30" {{ old('duration_days', $package->duration_days) == 30 ? 'selected' : '' }}>30 Days (1 Month)</option>
                                        <option value="90" {{ old('duration_days', $package->duration_days) == 90 ? 'selected' : '' }}>90 Days (3 Months)</option>
                                        <option value="180" {{ old('duration_days', $package->duration_days) == 180 ? 'selected' : '' }}>180 Days (6 Months)</option>
                                        <option value="365" {{ old('duration_days', $package->duration_days) == 365 ? 'selected' : '' }}>365 Days (1 Year)</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Sort Order --}}
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="sort_order">Display Order</label>
                                    <input type="number" class="input-field" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $package->sort_order) }}">
                                    <small class="text-muted">Lower number = displayed first</small>
                                </div>
                            </div>

                            {{-- Page Limit --}}
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="page_limit">Facebook Page Limit <span class="text-danger">*</span></label>
                                    <input type="number" class="input-field" id="page_limit" name="page_limit" min="1" value="{{ old('page_limit', $package->page_limit) }}" required>
                                    <small class="text-muted">How many pages user can connect</small>
                                </div>
                            </div>

                            {{-- Message Limit --}}
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="message_limit">Monthly Message Limit <span class="text-danger">*</span></label>
                                    <input type="number" class="input-field" id="message_limit" name="message_limit" min="100" value="{{ old('message_limit', $package->message_limit) }}" required>
                                </div>
                            </div>

                            {{-- Auto Reply Limit --}}
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="auto_reply_limit">Auto-Reply Rules Limit <span class="text-danger">*</span></label>
                                    <input type="number" class="input-field" id="auto_reply_limit" name="auto_reply_limit" min="1" value="{{ old('auto_reply_limit', $package->auto_reply_limit) }}" required>
                                </div>
                            </div>

                            {{-- Comment Automation Limit --}}
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="comment_automation_limit">Comment Automation Rules Limit <span class="text-danger">*</span></label>
                                    <input type="number" class="input-field" id="comment_automation_limit" name="comment_automation_limit" min="1" value="{{ old('comment_automation_limit', $package->comment_automation_limit) }}" required>
                                </div>
                            </div>

                            {{-- Features Checkboxes --}}
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="d-block mb-3">Package Features</label>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="product_carousel" name="product_carousel" value="1" {{ old('product_carousel', $package->product_carousel) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="product_carousel">
                                                    <i class="fas fa-layer-group text-primary mr-1"></i> Product Carousel
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="analytics" name="analytics" value="1" {{ old('analytics', $package->analytics) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="analytics">
                                                    <i class="fas fa-chart-line text-success mr-1"></i> Analytics
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="broadcast" name="broadcast" value="1" {{ old('broadcast', $package->broadcast) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="broadcast">
                                                    <i class="fas fa-broadcast-tower text-info mr-1"></i> Broadcast
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="is_popular" name="is_popular" value="1" {{ old('is_popular', $package->is_popular) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_popular">
                                                    <i class="fas fa-star text-warning mr-1"></i> Mark as Popular
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{ old('status', $package->status) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status">
                                            <strong>Active Status</strong>
                                            <small class="d-block text-muted">Uncheck to make package inactive</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="addProductSubmit-btn">
                                    <i class="fas fa-save mr-2"></i> Update Package
                                </button>
                                <a href="{{ route('admin.chatbot.packages.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times mr-2"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
