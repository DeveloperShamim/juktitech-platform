@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-clock',
        'title' => __('Cron Jobs / Scheduled Tasks'),
        'subtitle' => __('Run and manage scheduled system commands'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Cron Jobs'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            @if(session('output'))
                <pre class="mt-2 mb-0 small bg-white p-2 rounded adm-cron-output">{{ session('output') }}</pre>
            @endif
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="adm-panel mb-4">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('Scheduled Command List') }}</h3>
        </div>
        <div class="adm-panel__body p-0">
            <p class="text-muted small mb-0 px-3 pt-3">সার্ভারে ক্রন সেটআপ থাকলে এগুলো অটোমেটিক রান হয়। এখান থেকে এখনই রান করতে পারবেন।</p>
            <div class="table-responsiv px-3 pb-3 pt-2">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>{{ __('Command') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Schedule') }}</th>
                            <th class="text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commands as $cmd)
                        <tr>
                            <td><code class="bg-light px-2 py-1 rounded">{{ $cmd['signature'] }}</code></td>
                            <td>
                                <strong>{{ $cmd['name'] }}</strong>
                                <div class="small text-muted">{{ $cmd['desc'] }}</div>
                            </td>
                            <td><span class="badge badge-info">{{ $cmd['schedule'] }}</span></td>
                            <td class="text-right">
                                <form action="{{ route('admin.cron.run') }}" method="POST" class="d-inline" onsubmit="return confirm('এই কমান্ড এখনই রান করবেন?');">
                                    @csrf
                                    <input type="hidden" name="command" value="{{ $cmd['signature'] }}">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-play mr-1"></i> {{ __('Run Now') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">কোনো ক্রন কমান্ড সংজ্ঞায়িত নেই।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="adm-panel">
        <div class="adm-panel__body p-4">
            <div class="alert alert-light border mb-0">
                <strong><i class="fas fa-info-circle mr-2"></i>ক্রন সেটআপ (একটাই এন্ট্রি লাগে):</strong>
                <p class="mb-2 mt-2">উপরের <strong>সব কমান্ড</strong> অটোমেটিক চালাতে সার্ভারে শুধু <strong>একটি</strong> ক্রন এন্ট্রি দিন। Laravel প্রতিমিনিট চেক করে যেই কমান্ডের সময় হবে সেটাই রান করাবে।</p>
                <code class="d-inline-block mt-2 p-2 bg-dark text-white rounded">* * * * * cd {{ base_path() }} && php artisan schedule:run >> /dev/null 2>&1</code>
                <ul class="small text-muted mt-2 mb-0">
                    <li>একই ক্রন দিয়ে <code>sms:clean</code> (রাত ১২টা) ও <code>packages:process-expiry</code> (ভোর ১টা) দুটোই সময়মতো রান হবে।</li>
                    <li>ভবিষ্যতে নতুন শিডিউল যোগ করলেও এই একই ক্রন লাইন থাকলেই হবে।</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .adm-pro .adm-cron-output { max-height: 120px; overflow: auto; }
</style>
@endsection
