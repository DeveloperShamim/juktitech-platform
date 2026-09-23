@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-edit',
        'title' => __('Edit Website') . ': ' . $data->name,
        'subtitle' => __('Update website details, category, badge, and features'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Websites Collection'), 'url' => route('admin.products.index')],
            ['label' => __('Edit'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-edit"></i> {{ __('Website Details') }}</h3>
            <a href="{{ route('admin.products.index') }}" class="btn btn-dark btn-sm">{{ __('Back to List') }}</a>
        </div>
        <div class="adm-panel__body">
            <div class="product-description">
                <div class="body-area">
                    <form action="{{ route('admin.products.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Website Name') }} *</h4></div>
                            <div class="col-lg-7"><input type="text" name="name" class="input-field" value="{{ $data->name }}" required></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Price (৳)') }} *</h4></div>
                            <div class="col-lg-7"><input type="text" name="price" class="input-field" value="{{ $data->price }}" required></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Short Description') }}</h4></div>
                            <div class="col-lg-7"><textarea name="description" class="input-field" rows="2">{{ $data->description }}</textarea></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Full Details') }}</h4></div>
                            <div class="col-lg-7"><textarea name="details" id="details" class="input-field" rows="8">{{ $data->details }}</textarea></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Live Demo Link') }}</h4></div>
                            <div class="col-lg-7"><input type="url" name="demo_link" class="input-field" value="{{ $data->demo_link }}"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Purchase Link') }}</h4></div>
                            <div class="col-lg-7"><input type="url" name="purchase_link" class="input-field" value="{{ $data->purchase_link }}"></div>
                        </div>

                        @include('admin.product.partials.website-collection-fields', ['product' => $data])

                        @include('admin.product.partials.tutorial-fields', ['tutorials' => $data->tutorials ?? []])

                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7">
                                <h5 class="font-weight-bold text-primary mb-2">{{ __('Digital Delivery (Google Drive)') }}</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Digital Product') }}</h4></div>
                            <div class="col-lg-7">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_digital" value="1" id="is_digital" {{ old('is_digital', $data->is_digital) ? 'checked' : '' }}>
                                    <label class="form-check-label font-weight-bold" for="is_digital">{{ __('Digital product (payment paid → secure download)') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Google Drive File ID / Link') }}</h4></div>
                            <div class="col-lg-7"><input type="text" name="google_drive_file_id" class="input-field" value="{{ old('google_drive_file_id', $data->google_drive_file_id) }}"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Download Filename') }}</h4></div>
                            <div class="col-lg-7"><input type="text" name="digital_file_name" class="input-field" value="{{ old('digital_file_name', $data->digital_file_name) }}"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Download Limit') }}</h4></div>
                            <div class="col-lg-7"><input type="number" name="download_limit" class="input-field" value="{{ old('download_limit', $data->download_limit ?: 3) }}" min="1" max="50"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Change Image') }}</h4></div>
                            <div class="col-lg-7">
                                <input type="file" name="photo" class="input-field mb-2">
                                @if($data->photo)
                                    <div class="p-2 border d-inline-block rounded bg-light">
                                        <span class="d-block mb-1 text-muted small">{{ __('Current Image') }}:</span>
                                        <img src="{{ asset_image('assets/images/products/'.$data->photo) }}" alt="Current Image" width="150" class="rounded">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7">
                                <h5 class="font-weight-bold text-primary mb-2">{{ __('SEO Settings (Optional)') }}</h5>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Meta Title') }}</h4></div>
                            <div class="col-lg-7"><input type="text" name="meta_title" class="input-field" value="{{ $data->meta_title }}" placeholder="ফাঁকা রাখলে অটোমেটিক জেনারেট হবে"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Meta Keywords') }}</h4></div>
                            <div class="col-lg-7"><input type="text" name="meta_keyword" class="input-field" value="{{ $data->meta_keyword }}" placeholder="keyword1, keyword2, keyword3"></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4 text-right"><h4 class="heading">{{ __('Meta Description') }}</h4></div>
                            <div class="col-lg-7"><textarea name="meta_description" class="input-field" rows="3" placeholder="ফাঁকা রাখলে বিস্তারিত বিবরণ থেকে অটোমেটিক জেনারেট হবে">{{ $data->meta_description }}</textarea></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-7 text-center">
                                <button type="submit" class="addProductSubmit-btn">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .cke_notification_warning { display: none !important; }
    .cke_button__about { display: none !important; }
</style>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        CKEDITOR.replace('details', {
            height: 400,
            toolbar: [
                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat' ] },
                { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
                { name: 'links', items: [ 'Link', 'Unlink' ] },
                { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar' ] },
                { name: 'tools', items: [ 'Maximize', 'ShowBlocks', 'Source' ] }
            ],
            versionCheck: false
        });
    });
</script>
@endsection
