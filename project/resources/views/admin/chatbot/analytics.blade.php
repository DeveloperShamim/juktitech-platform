@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-chart-line',
        'title' => __('Chatbot Analytics'),
        'subtitle' => __('Real-time chatbot performance metrics'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Chatbot'), 'url' => route('admin.chatbot.packages.index')],
            ['label' => __('Analytics'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2">{{ __('Total Pages') }}</h6>
                    <h2 class="mb-0 font-weight-bold">{{ $stats['total_pages'] }}</h2>
                    <i class="fas fa-link fa-2x position-absolute adm-card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2">{{ __('Active Users') }}</h6>
                    <h2 class="mb-0 font-weight-bold">{{ $stats['total_users'] }}</h2>
                    <i class="fas fa-users fa-2x position-absolute adm-card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2">{{ __('Bot Replies') }}</h6>
                    <h2 class="mb-0 font-weight-bold">{{ $stats['total_replies'] }}</h2>
                    <i class="fas fa-reply fa-2x position-absolute adm-card-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2">{{ __('Automations') }}</h6>
                    <h2 class="mb-0 font-weight-bold">{{ $stats['total_automations'] }}</h2>
                    <i class="fas fa-cogs fa-2x position-absolute adm-card-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="adm-panel h-100">
                <div class="adm-panel__head">
                    <h3 class="adm-panel__title"><i class="fas fa-clock"></i> {{ __('Recent Page Connections') }}</h3>
                </div>
                <div class="adm-panel__body p-0">
                    <div class="table-responsive">
                        <table class="table default-table mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Page Name') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Time') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPages as $page)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-primary mr-2 p-2"><i class="fab fa-facebook-f"></i></span>
                                            <div>
                                                <strong>{{ $page->page_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($page->page_id, 15) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><strong>{{ $page->user->name ?? 'N/A' }}</strong></td>
                                    <td><small class="text-muted"><i class="fas fa-clock mr-1"></i>{{ $page->created_at->diffForHumans() }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4"><span class="text-muted">{{ __('No recent activities') }}</span></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="adm-panel h-100">
                <div class="adm-panel__head">
                    <h3 class="adm-panel__title"><i class="fas fa-trophy"></i> {{ __('Top Users Leaderboard') }}</h3>
                </div>
                <div class="adm-panel__body p-0">
                    <div class="table-responsive">
                        <table class="table default-table mb-0">
                            <thead>
                                <tr>
                                    <th width="60">{{ __('Rank') }}</th>
                                    <th>{{ __('User Details') }}</th>
                                    <th width="100">{{ __('Pages') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topUsers as $index => $item)
                                <tr>
                                    <td>
                                        @if($index == 0)
                                            <i class="fas fa-crown text-warning fa-lg"></i>
                                        @else
                                            <span class="badge badge-secondary badge-pill">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item->user->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted"><i class="fas fa-envelope mr-1"></i>{{ $item->user->email ?? '' }}</small>
                                    </td>
                                    <td><span class="badge badge-success">{{ $item->pages_count }} <i class="fas fa-link ml-1"></i></span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4"><span class="text-muted">{{ __('No data available') }}</span></td>
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
