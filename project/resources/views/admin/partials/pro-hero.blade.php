{{--
    Professional page hero for admin pages.
    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-users',
        'title' => __('Team Members'),
        'subtitle' => __('Manage your team members'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Team'), 'url' => null],
        ],
        'action' => [
            'type' => 'link', // link | modal
            'url' => route('admin.team.create'),
            'label' => __('Add New Member'),
            'icon' => 'fas fa-plus',
            'modal_target' => '#modal1',
            'modal_href' => route('admin.team.create'),
            'modal_id' => 'add-data',
        ],
    ])
--}}
<div class="adm-hero">
    <div class="adm-hero__left">
        <div class="adm-hero__icon"><i class="{{ $icon ?? 'fas fa-layer-group' }}"></i></div>
        <div class="adm-hero__text">
            <h1>{{ $title }}</h1>
            @if(!empty($subtitle))
                <p>{{ $subtitle }}</p>
            @endif
            @if(!empty($breadcrumbs))
                <ul class="adm-breadcrumb">
                    @foreach($breadcrumbs as $crumb)
                        <li>
                            @if(!empty($crumb['url']))
                                <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                            @else
                                <span>{{ $crumb['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    @if(!empty($action))
        @if(($action['type'] ?? 'link') === 'modal')
            <a href="javascript:;"
               class="adm-add-btn add-btn{{ !empty($action['modal_id']) ? ' '.$action['modal_id'] : '' }}"
               @if(!empty($action['modal_href'])) data-href="{{ $action['modal_href'] }}" @endif
               @if(!empty($action['modal_id'])) id="{{ $action['modal_id'] }}" @endif
               data-toggle="modal"
               data-target="{{ $action['modal_target'] ?? '#modal1' }}">
                <i class="{{ $action['icon'] ?? 'fas fa-plus' }}"></i> {{ $action['label'] }}
            </a>
        @else
            <a href="{{ $action['url'] }}" class="adm-add-btn">
                <i class="{{ $action['icon'] ?? 'fas fa-plus' }}"></i> {{ $action['label'] }}
            </a>
        @endif
    @endif
</div>
