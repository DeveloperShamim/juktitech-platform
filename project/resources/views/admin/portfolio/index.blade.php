@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-briefcase',
        'title' => __('Portfolio'),
        'subtitle' => __('Manage portfolio projects'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Portfolio'), 'url' => route('admin.portfolio.index')],
        ],
        'action' => [
            'type' => 'link',
            'url' => route('admin.portfolio.create'),
            'label' => __('Add New Project'),
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')
    @include('includes.admin.form-both')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('Project List') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Photo') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th class="text-right">{{ __('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($portfolios as $data)
                        <tr>
                            <td>
                                <div class="adm-thumb adm-thumb--feature">
                                    <img src="{{ $data->photo ? asset_image('assets/images/portfolio/'.$data->photo) : asset_image('assets/images/noimage.png') }}" alt="{{ $data->title }}">
                                </div>
                            </td>
                            <td class="font-weight-bold">{{ $data->title }}</td>
                            <td><span class="badge badge-info">{{ $data->category->name }}</span></td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="{{ route('admin.portfolio.edit', $data->id) }}" class="btn-action edit" title="{{ __('Edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete-{{ $data->id }}" class="btn-action delete" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                                @include('admin.partials.delete-row-modal', [
                                    'id' => $data->id,
                                    'action' => route('admin.portfolio.destroy', $data->id),
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
