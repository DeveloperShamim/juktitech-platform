@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-link',
        'title' => __('Connected Facebook Pages'),
        'subtitle' => __('Manage user-connected Facebook pages'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Chatbot'), 'url' => route('admin.chatbot.packages.index')],
            ['label' => __('Connected Pages'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2">{{ __('Total Pages') }}</h6>
                    <h2 class="mb-0 font-weight-bold">{{ $pages->total() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2">{{ __('Active Pages') }}</h6>
                    <h2 class="mb-0 font-weight-bold">{{ $pages->where('is_connected', true)->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2">{{ __('Active Users') }}</h6>
                    <h2 class="mb-0 font-weight-bold">{{ $pages->unique('user_id')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2">{{ __('Bot Replies') }}</h6>
                    <h2 class="mb-0 font-weight-bold">{{ \App\Models\BotReply::count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('All Connected Pages') }}</h3>
        </div>
        <div class="adm-panel__body p-0">
            <div class="table-responsive">
                <table class="table default-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Page Name') }}</th>
                            <th>{{ __('Page ID') }}</th>
                            <th>{{ __('User Details') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Connected At') }}</th>
                            <th class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $page)
                        <tr>
                            <td><strong class="text-primary">#{{ $page->id }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-primary mr-2 p-2"><i class="fab fa-facebook-f"></i></span>
                                    <strong>{{ $page->page_name }}</strong>
                                </div>
                            </td>
                            <td><code>{{ $page->page_id }}</code></td>
                            <td>
                                @if($page->user)
                                    <strong>{{ $page->user->name }}</strong>
                                    <br>
                                    <small class="text-muted"><i class="fas fa-envelope mr-1"></i>{{ $page->user->email }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($page->is_connected)
                                    <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> {{ __('Connected') }}</span>
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-times-circle mr-1"></i> {{ __('Disconnected') }}</span>
                                @endif
                            </td>
                            <td>
                                <i class="fas fa-calendar mr-1"></i>{{ $page->created_at->format('d M Y') }}
                                <br>
                                <small class="text-muted"><i class="fas fa-clock mr-1"></i>{{ $page->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="action-btns justify-content-center">
                                    @if($page->is_connected)
                                        <button type="button"
                                                class="btn-action btn-action--danger"
                                                data-href="{{ route('admin.chatbot.pages.disconnect', $page->id) }}"
                                                data-toggle="modal"
                                                data-target="#confirm-disconnect"
                                                title="{{ __('Disconnect Page') }}">
                                            <i class="fas fa-unlink"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn-action btn-action--info" title="{{ __('View Details') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                <h5>{{ __('No Connected Pages Found') }}</h5>
                                <p class="text-muted mb-0">{{ __("Users haven't connected any Facebook pages yet.") }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pages->hasPages())
            <div class="p-3">
                {{ $pages->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-disconnect" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>{{ __('Confirm Disconnect') }}</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-unlink fa-2x text-danger mb-3"></i>
                <h5>{{ __('Are you sure?') }}</h5>
                <p class="text-muted mb-0">{{ __('This will disconnect the Facebook page from the user\'s account.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> {{ __('Cancel') }}</button>
                <form id="disconnect-form" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger"><i class="fas fa-unlink mr-1"></i> {{ __('Disconnect') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).on('click', '[data-toggle="modal"]', function() {
        var disconnectUrl = $(this).data('href');
        $('#disconnect-form').attr('action', disconnectUrl);
    });
</script>
@endsection
