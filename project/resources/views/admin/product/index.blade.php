@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-laptop-code',
        'title' => __('Websites Collection'),
        'subtitle' => __('Manage ready-made websites for sale, categories, badges, and features'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Websites Collection'), 'url' => null],
        ],
        'action' => [
            'type' => 'link',
            'url' => route('admin.products.create'),
            'label' => __('Add New Website'),
            'icon' => 'fas fa-plus',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-laptop-code"></i> {{ __('সকল ওয়েবসাইট') }}</h3>
            <div class="adm-theme-summary">
                <span class="adm-theme-summary__item">
                    <i class="fas fa-eye"></i>
                    {{ __('Total Views') }}: <strong>{{ number_format($totalViews ?? 0) }}</strong>
                </span>
            </div>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Category & Badge') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th class="text-center">{{ __('Views') }}</th>
                            <th>{{ __('Traffic Sources') }}</th>
                            <th class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.partials.theme-traffic-modal')

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i> {{ __('Confirm Delete') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="adm-delete-icon"><i class="fas fa-trash-alt"></i></div>
                <h5 class="font-weight-bold">{{ __('Are you sure?') }}</h5>
                <p class="text-muted">{{ __('You are about to delete this website. This action cannot be undone!') }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <form action="" method="POST" id="delete-form" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Yes, Delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .cke_notification_warning { display: none !important; }
    .cke_button__about { display: none !important; }

    .adm-theme-summary {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .adm-theme-summary__item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .adm-theme-summary__item i {
        color: #2563eb;
    }

    .adm-views-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 72px;
        padding: 6px 12px;
        border-radius: 999px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .adm-views-badge i {
        color: #16a34a;
        font-size: 0.78rem;
    }

    .adm-traffic-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        max-width: 280px;
    }

    .adm-traffic-trigger {
        border: 0;
        background: transparent;
        padding: 0;
        cursor: pointer;
        text-align: left;
        transition: transform 0.15s ease;
    }

    .adm-traffic-trigger:hover {
        transform: translateY(-1px);
    }

    .adm-traffic-trigger:hover .adm-traffic-pill {
        border-color: #93c5fd;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.12);
    }

    .adm-traffic-pill--open {
        background: #eff6ff !important;
        border-color: #bfdbfe !important;
        color: #2563eb !important;
    }

    .adm-traffic-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 0.74rem;
        font-weight: 700;
        color: #334155;
        white-space: nowrap;
    }

    .adm-traffic-pill i {
        font-size: 0.82rem;
    }

    .adm-traffic-pill--more {
        background: #eef2ff;
        border-color: #c7d2fe;
        color: #4338ca;
    }

    .adm-traffic-empty {
        font-size: 0.85rem;
    }

    #geniustable td {
        vertical-align: middle !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#geniustable').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            ajax: {
                url: "{{ route('admin.products.datatables') }}",
                type: 'GET',
                error: function (xhr, error, thrown) {
                    Swal.fire({
                        icon: 'error',
                        title: 'এরর!',
                        text: 'ডাটা লোড হতে সমস্যা হচ্ছে। রাউট চেক করুন।'
                    });
                }
            },
            columns: [
                { data: 'photo', name: 'photo', className: 'text-center' },
                { data: 'name', name: 'name', className: 'font-weight-bold text-dark' },
                { data: 'category', name: 'category' },
                { data: 'price', name: 'price', className: 'text-center' },
                { data: 'views', name: 'views', searchable: false, orderable: false, className: 'text-center' },
                { data: 'traffic_sources', name: 'traffic_sources', searchable: false, orderable: false },
                { data: 'action', searchable: false, orderable: false, className: 'text-center' }
            ],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                search: "_INPUT_",
                searchPlaceholder: "এখানে খুঁজুন...",
                lengthMenu: "দেখান _MENU_ টি",
                info: "মোট _TOTAL_ টি ওয়েবসাইটের মধ্যে _START_ থেকে _END_ পর্যন্ত দেখানো হচ্ছে",
                paginate: {
                    first: "প্রথম",
                    last: "শেষ",
                    next: '<i class="fas fa-chevron-right"></i>',
                    previous: '<i class="fas fa-chevron-left"></i>'
                }
            }
        });

        $('#confirm-delete').on('show.bs.modal', function(e) {
            var href = $(e.relatedTarget).data('href');
            $(this).find('#delete-form').attr('action', href);
        });

        var themeTrafficModal = document.getElementById('themeTrafficModal');
        var themeTrafficChart = null;
        var pendingTrafficData = null;

        function formatNumber(n) {
            return Number(n || 0).toLocaleString();
        }

        function renderThemeTrafficModal(data) {
            if (!themeTrafficModal || !data) return;

            $('#themeTrafficTitle').html('<i class="fas fa-chart-pie mr-2"></i>' + (data.name || '{{ __("Theme") }}'));
            $('#themeTrafficSub').text('{{ __("Traffic source breakdown for this theme page") }}');
            $('#themeTrafficTotal').text(formatNumber(data.total));

            var $content = $('#themeTrafficContent');
            $content.empty();

            if (!data.sources || !data.sources.length) {
                $content.html(
                    '<div class="traffic-modal__empty">' +
                    '<div class="traffic-modal__empty-icon"><i class="fas fa-chart-line"></i></div>' +
                    '<div class="traffic-modal__empty-title">{{ __("No traffic data yet") }}</div>' +
                    '<p class="traffic-modal__empty-text">{{ __("Views will appear when visitors open this theme page.") }}</p>' +
                    '</div>'
                );
                return;
            }

            var listHtml = '';
            data.sources.forEach(function(source) {
                listHtml +=
                    '<div class="traffic-modal__item">' +
                    '<div class="traffic-modal__item-icon" style="background:' + source.color + '"><i class="' + source.icon + '"></i></div>' +
                    '<div class="traffic-modal__item-body">' +
                    '<div class="traffic-modal__item-name">' + source.label + '</div>' +
                    '<div class="traffic-modal__item-bar"><span style="width:' + Math.min(source.percent, 100) + '%;background:' + source.color + '"></span></div>' +
                    '</div>' +
                    '<div class="traffic-modal__item-stats">' +
                    '<div class="traffic-modal__item-count">' + formatNumber(source.count) + '</div>' +
                    '<div class="traffic-modal__item-pct">' + source.percent + '%</div>' +
                    '</div></div>';
            });

            $content.html(
                '<div class="traffic-modal__layout">' +
                '<div class="traffic-modal__chart-box">' +
                '<div class="traffic-modal__chart-wrap">' +
                '<canvas id="themeTrafficChart"></canvas>' +
                '<div class="traffic-modal__chart-center">' +
                '<div class="traffic-modal__chart-total">' + formatNumber(data.total) + '</div>' +
                '<div class="traffic-modal__chart-label">{{ __("Views") }}</div>' +
                '</div></div></div>' +
                '<div class="traffic-modal__list">' + listHtml + '</div>' +
                '</div>'
            );
        }

        function buildThemeTrafficChart(data) {
            if (typeof Chart === 'undefined' || !data || !data.sources || !data.sources.length) return;

            var canvas = document.getElementById('themeTrafficChart');
            if (!canvas) return;

            if (themeTrafficChart) {
                themeTrafficChart.destroy();
                themeTrafficChart = null;
            }

            themeTrafficChart = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: data.sources.map(function(s) { return s.label; }),
                    datasets: [{
                        data: data.sources.map(function(s) { return s.count; }),
                        backgroundColor: data.sources.map(function(s) { return s.color; }),
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            callbacks: {
                                label: function(ctx) {
                                    var total = ctx.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                    var pct = total ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                    return ctx.parsed.toLocaleString() + ' (' + pct + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        $(document).on('click', '.adm-traffic-trigger', function(e) {
            e.preventDefault();
            var raw = $(this).attr('data-traffic');
            if (!raw) return;

            try {
                pendingTrafficData = JSON.parse(raw);
            } catch (err) {
                return;
            }

            renderThemeTrafficModal(pendingTrafficData);
            $('#themeTrafficModal').modal('show');
        });

        $('#themeTrafficModal').on('shown.bs.modal', function() {
            buildThemeTrafficChart(pendingTrafficData);
        });

        $('#themeTrafficModal').on('hidden.bs.modal', function() {
            if (themeTrafficChart) {
                themeTrafficChart.destroy();
                themeTrafficChart = null;
            }
            pendingTrafficData = null;
            $('#themeTrafficContent').empty();
        });
    });
</script>
@endsection
