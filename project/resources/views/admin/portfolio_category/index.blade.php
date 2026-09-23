@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-folder-open',
        'title' => __('Portfolio Categories'),
        'subtitle' => __('Manage portfolio project categories'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Portfolio Categories'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')
    @include('includes.admin.form-both')

    <div class="row">
        <div class="col-lg-4">
            <div class="adm-panel">
                <div class="adm-panel__head">
                    <h3 class="adm-panel__title"><i class="fas fa-plus"></i> {{ __('Add New Category') }}</h3>
                </div>
                <div class="adm-panel__body">
                    <form action="{{ route('admin.portfolio-category.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="mb-2 font-weight-bold">{{ __('Category Name') }} *</label>
                            <input type="text" class="input-field form-control" name="name" placeholder="Ex: Web Design" required>
                        </div>
                        <button class="addProductSubmit-btn w-100" type="submit">{{ __('Create Category') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="adm-panel">
                <div class="adm-panel__head">
                    <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('All Categories') }}</h3>
                </div>
                <div class="adm-panel__body">
                    <div class="table-responsiv px-3 pb-3">
                        <table class="table table-hover dt-responsive w-100" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Slug') }}</th>
                                    <th class="text-right">{{ __('Options') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $cat)
                                <tr>
                                    <td class="font-weight-bold">{{ $cat->name }}</td>
                                    <td><code>{{ $cat->slug }}</code></td>
                                    <td class="text-right">
                                        <div class="action-btns">
                                            <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete-{{ $cat->id }}" class="btn-action delete" title="{{ __('Delete') }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                        @include('admin.partials.delete-row-modal', [
                                            'id' => $cat->id,
                                            'action' => route('admin.portfolio-category.destroy', $cat->id),
                                            'title' => __('Delete Category?'),
                                        ])
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <p class="mt-2 mb-0">{{ __('No Categories Found in Database.') }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
