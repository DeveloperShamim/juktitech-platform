@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-concierge-bell',
        'title' => __('Our Services'),
        'subtitle' => __('Manage all services displayed on your site'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Manage Services'), 'url' => null],
        ],
        'action' => [
            'type' => 'link',
            'url' => route('admin.service.create'),
            'label' => __('Add New Service'),
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span>&times;</span></button>
        </div>
    @endif

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('Services List') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Icon') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th class="text-right">{{ __('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td>
                                <div class="adm-icon-circle"><i class="{{ $service->icon }}"></i></div>
                            </td>
                            <td class="font-weight-bold">{{ $service->title }}</td>
                            <td>{{ Str::limit($service->description, 50) }}</td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="{{ route('admin.service.edit', $service->id) }}" class="btn-action edit" title="{{ __('Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete-{{ $service->id }}" class="btn-action delete" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                                @include('admin.partials.delete-row-modal', [
                                    'id' => $service->id,
                                    'action' => route('admin.service.destroy', $service->id),
                                ])
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
