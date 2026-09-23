@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-users',
        'title' => __('Team Members'),
        'subtitle' => __('Manage your team members'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Team'), 'url' => route('admin.team.index')],
        ],
        'action' => [
            'type' => 'link',
            'url' => route('admin.team.create'),
            'label' => __('Add New Member'),
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('Manage Members') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Member') }}</th>
                            <th>{{ __('Designation') }}</th>
                            <th>{{ __('Social Links') }}</th>
                            <th class="text-right">{{ __('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teams as $team)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="adm-thumb adm-thumb--avatar mr-3">
                                        <img src="{{ asset_image('assets/images/team/'.$team->photo) }}" alt="Member">
                                    </div>
                                    <span class="font-weight-bold">{{ $team->name }}</span>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="badge badge-light p-2">{{ $team->designation }}</span>
                            </td>
                            <td class="align-middle">
                                <div class="adm-social-links">
                                    @if($team->facebook)
                                        <a href="{{ $team->facebook }}" target="_blank" class="adm-link mr-2"><i class="fab fa-facebook-f"></i></a>
                                    @endif
                                    @if($team->twitter)
                                        <a href="{{ $team->twitter }}" target="_blank" class="adm-link mr-2"><i class="fab fa-twitter"></i></a>
                                    @endif
                                    @if($team->linkedin)
                                        <a href="{{ $team->linkedin }}" target="_blank" class="adm-link"><i class="fab fa-linkedin-in"></i></a>
                                    @endif
                                    @if(!$team->facebook && !$team->twitter && !$team->linkedin)
                                        <small class="text-muted">N/A</small>
                                    @endif
                                </div>
                            </td>
                            <td class="align-middle text-right">
                                <div class="action-btns">
                                    <a href="{{ route('admin.team.edit', $team->id) }}" class="btn-action edit" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete-{{ $team->id }}" class="btn-action delete" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                                @include('admin.partials.delete-row-modal', [
                                    'id' => $team->id,
                                    'action' => route('admin.team.destroy', $team->id),
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
