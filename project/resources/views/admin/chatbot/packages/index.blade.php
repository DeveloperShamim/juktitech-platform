@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-robot',
        'title' => __('Chatbot Packages'),
        'subtitle' => __('Manage chatbot subscription packages'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Chatbot Packages'), 'url' => null],
        ],
        'action' => [
            'type' => 'link',
            'url' => route('admin.chatbot.packages.create'),
            'label' => __('Add New Package'),
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('Manage Chatbot Packages') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table class="table table-hover w-100">
                    <thead>
                        <tr>
                            <th>{{ __('Package Name') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Duration') }}</th>
                            <th>{{ __('Page Limit') }}</th>
                            <th>{{ __('Message Limit') }}</th>
                            <th>{{ __('Features') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-right">{{ __('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <strong class="text-dark">{{ $package->name }}</strong>
                                    @if($package->is_popular)
                                        <span class="badge badge-warning ml-2">Popular</span>
                                    @endif
                                </div>
                                @if($package->name_bn)
                                    <small class="text-muted">{{ $package->name_bn }}</small>
                                @endif
                            </td>
                            <td><strong class="text-primary">৳{{ number_format($package->price, 0) }}</strong></td>
                            <td>
                                @if((int) $package->duration_days === 365)
                                    ১ বছর
                                @elseif((int) $package->duration_days === 30)
                                    ১ মাস
                                @else
                                    {{ $package->duration_days }} days
                                @endif
                            </td>
                            <td><span class="badge badge-info">{{ $package->page_limit }} pages</span></td>
                            <td><span class="badge badge-secondary">{{ number_format($package->message_limit) }}/month</span></td>
                            <td>
                                <div class="d-flex flex-wrap adm-toolbar-gap">
                                    @if($package->product_carousel)<span class="badge badge-success">Carousel</span>@endif
                                    @if($package->analytics)<span class="badge badge-success">Analytics</span>@endif
                                    @if($package->broadcast)<span class="badge badge-success">Broadcast</span>@endif
                                </div>
                            </td>
                            <td>
                                @if($package->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="{{ route('admin.chatbot.packages.edit', $package->id) }}" class="btn-action edit" title="{{ __('Edit') }}">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="{{ route('admin.chatbot.packages.toggle', $package->id) }}" class="btn-action view" title="{{ __('Toggle Status') }}">
                                        <i class="fas fa-{{ $package->status ? 'eye-slash' : 'eye' }}"></i>
                                    </a>
                                    <a href="javascript:;" data-href="{{ route('admin.chatbot.packages.delete', $package->id) }}" data-toggle="modal" data-target="#confirm-delete" class="btn-action delete" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                {{ __('No packages found. Create your first chatbot package!') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Confirm Delete') }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="text-center mb-0">{{ __('Are you sure you want to delete this package?') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a href="" class="btn btn-danger" id="delete-link">{{ __('Delete') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).on('click', '[data-toggle="modal"]', function() {
        var deleteUrl = $(this).data('href');
        $('#delete-link').attr('href', deleteUrl);
    });
</script>
@endsection
