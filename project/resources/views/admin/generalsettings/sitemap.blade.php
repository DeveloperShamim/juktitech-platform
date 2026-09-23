@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-sitemap',
        'title' => __('Sitemaps'),
        'subtitle' => __('View generated sitemap URLs'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Sitemaps'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-both')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-sitemap"></i> {{ __('Sitemap URLs') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <div class="gocover"></div>
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Sitemap') }}</th>
                            <th class="text-right">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ __('All Site Maps') }}</td>
                            <td>{{ route('sitemap.index') }}</td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="{{ route('sitemap.index') }}" target="_blank" class="btn-action view" title="{{ __('View') }}"><i class="fas fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ __('Post') }}</td>
                            <td>{{ route('sitemap.posts') }}</td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="{{ route('sitemap.posts') }}" target="_blank" class="btn-action view" title="{{ __('View') }}"><i class="fas fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ __('Categories') }}</td>
                            <td>{{ route('sitemap.categories') }}</td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="{{ route('sitemap.categories') }}" target="_blank" class="btn-action view" title="{{ __('View') }}"><i class="fas fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ __('Subcategories') }}</td>
                            <td>{{ route('sitemap.subcategories') }}</td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="{{ route('sitemap.subcategories') }}" target="_blank" class="btn-action view" title="{{ __('View') }}"><i class="fas fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    var table = $('#geniustable').DataTable({ ordering: false });
</script>
@endsection
