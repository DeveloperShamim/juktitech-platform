@extends('layouts.admin')

@section('styles')
@include('admin.stolen.partials.stolen-styles')
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-user-secret',
        'title' => __('Stolen Reports'),
        'subtitle' => __('অননুমোদিত ডোমেইন ও লাইসেন্স ব্যবহারের লগ মনিটর করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Stolen Reports'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="sr-stats">
        <div class="sr-stat">
            <span class="sr-stat__icon sr-stat__icon--total"><i class="fas fa-globe"></i></span>
            <div class="sr-stat__body">
                <span>{{ __('Total Domains') }}</span>
                <strong>{{ $stats['total'] ?? 0 }}</strong>
            </div>
        </div>
        <div class="sr-stat">
            <span class="sr-stat__icon sr-stat__icon--pending"><i class="fas fa-clock"></i></span>
            <div class="sr-stat__body">
                <span>{{ __('Pending') }}</span>
                <strong>{{ $stats['pending'] ?? 0 }}</strong>
            </div>
        </div>
        <div class="sr-stat">
            <span class="sr-stat__icon sr-stat__icon--blocked"><i class="fas fa-ban"></i></span>
            <div class="sr-stat__body">
                <span>{{ __('Blocked') }}</span>
                <strong>{{ $stats['blocked'] ?? 0 }}</strong>
            </div>
        </div>
        <div class="sr-stat">
            <span class="sr-stat__icon sr-stat__icon--resolved"><i class="fas fa-check-circle"></i></span>
            <div class="sr-stat__body">
                <span>{{ __('Resolved') }}</span>
                <strong>{{ $stats['resolved'] ?? 0 }}</strong>
            </div>
        </div>
    </div>

    <div class="sr-layout">
        <div class="sr-card">
            <div class="sr-card__head">
                <h3><i class="fas fa-shield-alt"></i> {{ __('Unauthorized Access Logs') }}</h3>
                <div class="sr-toolbar">
                    <button type="button" class="sr-tool sr-tool--danger btn-delete-selected" disabled title="{{ __('Delete selected entries') }}">
                        <i class="fas fa-trash-alt"></i>
                        {{ __('Delete Selected') }} (<span class="selected-count">0</span>)
                    </button>
                    <button type="button" class="sr-tool sr-tool--danger btn-delete-all">
                        <i class="fas fa-trash"></i> {{ __('Delete All') }}
                    </button>
                    <button type="button" class="sr-tool sr-tool--primary" onclick="location.reload();">
                        <i class="fas fa-sync-alt"></i> {{ __('Refresh') }}
                    </button>
                </div>
            </div>
            <div class="sr-card__body">
                <p class="sr-hint">
                    <i class="fas fa-info-circle"></i>
                    প্রতিটি ডোমেইনের <strong>সর্বশেষ রিপোর্ট</strong> দেখানো হয়। Cache Clear, Block বা Kill Switch অ্যাকশন ক্লায়েন্ট সাইটে সর্বোচ্চ ৩০ মিনিটের মধ্যে কার্যকর হতে পারে।
                </p>
                <div class="table-responsiv px-1 pb-1">
                    <table class="table table-hover dt-responsive w-100" id="geniustable" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="stolen-checkbox-col">
                                    <input type="checkbox" id="select-all" title="{{ __('Select All') }}">
                                </th>
                                <th>#</th>
                                <th>{{ __('Domain') }}</th>
                                <th>{{ __('Connection') }}</th>
                                <th class="text-center">{{ __('Status') }}</th>
                                <th>{{ __('Log Date') }}</th>
                                <th class="text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <aside class="sr-aside">
            <div class="sr-card sr-guide">
                <div class="sr-guide__head">
                    <h4><i class="fas fa-lightbulb text-warning"></i> {{ __('Action Guide') }}</h4>
                </div>
                <div class="sr-guide__body">
                    <div class="sr-guide__item">
                        <span class="sr-guide__icon sr-guide__icon--cache"><i class="fas fa-bolt"></i></span>
                        <div class="sr-guide__text">
                            <strong>{{ __('Cache Clear') }}</strong>
                            <small>{{ __('ক্লায়েন্ট সাইটের license cache মুছতে signal পাঠায়') }}</small>
                        </div>
                    </div>
                    <div class="sr-guide__item">
                        <span class="sr-guide__icon sr-guide__icon--kill"><i class="fas fa-skull"></i></span>
                        <div class="sr-guide__text">
                            <strong>{{ __('Kill Switch') }}</strong>
                            <small>{{ __('wipe_out চালু — ক্লায়েন্ট সেল্ফ-ডেস্ট্রাক্ট হতে পারে') }}</small>
                        </div>
                    </div>
                    <div class="sr-guide__item">
                        <span class="sr-guide__icon sr-guide__icon--block"><i class="fas fa-ban"></i></span>
                        <div class="sr-guide__text">
                            <strong>{{ __('Block / Unblock') }}</strong>
                            <small>{{ __('ডোমেইন ব্লক করলে verify-license reject হবে') }}</small>
                        </div>
                    </div>
                    <div class="sr-guide__item">
                        <span class="sr-guide__icon sr-guide__icon--status"><i class="fas fa-sync-alt"></i></span>
                        <div class="sr-guide__text">
                            <strong>{{ __('Toggle Status') }}</strong>
                            <small>{{ __('Pending ↔ Resolved স্ট্যাটাস পরিবর্তন') }}</small>
                        </div>
                    </div>
                    <div class="sr-guide__item">
                        <span class="sr-guide__icon sr-guide__icon--delete"><i class="fas fa-trash"></i></span>
                        <div class="sr-guide__text">
                            <strong>{{ __('Delete') }}</strong>
                            <small>{{ __('একটি বা একাধিক রিপোর্ট স্থায়ীভাবে মুছুন') }}</small>
                        </div>
                    </div>
                    <a href="{{ route('docs.license_security_api') }}" target="_blank" rel="noopener" class="sr-doc-link">
                        <i class="fas fa-book-open"></i> {{ __('Full Security API Docs') }}
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>

<div class="modal fade sr-modal adm-pro" id="confirm-delete" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="adm-delete-icon"><i class="fas fa-exclamation-circle"></i></div>
                <h5 class="font-weight-bold mb-2">{{ __('Are you sure?') }}</h5>
                <p class="text-muted small mb-0">{{ __('Are you sure you want to delete this record? This cannot be undone.') }}</p>
                <div class="mt-4">
                    <button type="button" class="btn btn-light btn-sm px-4 mr-2" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger btn-sm px-4 btn-ok">{{ __('Confirm Delete') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade sr-modal adm-pro" id="confirm-delete-all" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="adm-delete-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <h5 class="font-weight-bold mb-2">{{ __('Delete all reports?') }}</h5>
                <p class="text-muted small mb-0">{{ __('Are you sure you want to delete all security logs at once? This cannot be undone.') }}</p>
                <form id="form-delete-all" action="{{ route('admin.stolen.delete_all') }}" method="POST" class="d-inline">
                    @csrf
                    <div class="mt-4">
                        <button type="button" class="btn btn-light btn-sm px-4 mr-2" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger btn-sm px-4">{{ __('Delete All') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade sr-modal adm-pro" id="confirm-delete-selected" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="adm-delete-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <h5 class="font-weight-bold mb-2">{{ __('Delete selected reports?') }}</h5>
                <p class="text-muted small mb-0"><span class="delete-selected-count">0</span> {{ __('report(s) will be deleted. This cannot be undone.') }}</p>
                <form id="form-delete-selected" action="{{ route('admin.stolen.delete_selected') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="ids" id="hidden-ids" value="">
                    <div class="mt-4">
                        <button type="button" class="btn btn-light btn-sm px-4 mr-2" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger btn-sm px-4">{{ __('Delete Selected') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="cache-toast"></div>
@endsection

@section('scripts')
<script type="text/javascript">
$(function() {
    var selectedIds = [];

    function updateSelectedUI() {
        var count = selectedIds.length;
        $('.selected-count').text(count);
        $('.delete-selected-count').text(count);
        $('.btn-delete-selected').prop('disabled', count === 0);
    }

    function syncCheckboxes() {
        $('#geniustable tbody .row-checkbox').each(function() {
            var id = parseInt($(this).val(), 10);
            $(this).prop('checked', selectedIds.indexOf(id) !== -1);
        });
        var totalOnPage = $('#geniustable tbody .row-checkbox').length;
        var checkedOnPage = $('#geniustable tbody .row-checkbox:checked').length;
        $('#select-all').prop('checked', totalOnPage > 0 && checkedOnPage === totalOnPage);
    }

    var table = $('#geniustable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.stolen.index") }}',
        columns: [
            { data: 'checkbox', searchable: false, orderable: false, className: 'text-center' },
            { data: 'DT_RowIndex', name: 'id' },
            { data: 'domain_info', name: 'domain_name' },
            { data: 'details', name: 'full_url' },
            { data: 'status', name: 'status', className: 'text-center' },
            { data: 'date', name: 'created_at' },
            { data: 'action', searchable: false, orderable: false, className: 'text-right' }
        ],
        language: {
            processing: '<img src="{{ asset_image('assets/images/'.$gs->admin_loader) }}">',
            search: '_INPUT_',
            searchPlaceholder: '{{ __("Search domains, IP or URL...") }}'
        },
        drawCallback: function() {
            syncCheckboxes();
        }
    });

    $(document).on('change', '#select-all', function() {
        var checked = $(this).prop('checked');
        $('#geniustable tbody .row-checkbox').each(function() {
            var id = parseInt($(this).val(), 10);
            if (checked) {
                if (selectedIds.indexOf(id) === -1) selectedIds.push(id);
            } else {
                selectedIds = selectedIds.filter(function(x) { return x !== id; });
            }
            $(this).prop('checked', checked);
        });
        updateSelectedUI();
    });

    $(document).on('change', '.row-checkbox', function() {
        var id = parseInt($(this).val(), 10);
        if ($(this).prop('checked')) {
            if (selectedIds.indexOf(id) === -1) selectedIds.push(id);
        } else {
            selectedIds = selectedIds.filter(function(x) { return x !== id; });
        }
        var totalOnPage = $('#geniustable tbody .row-checkbox').length;
        var checkedOnPage = $('#geniustable tbody .row-checkbox:checked').length;
        $('#select-all').prop('checked', totalOnPage > 0 && checkedOnPage === totalOnPage);
        updateSelectedUI();
    });

    $(document).on('click', '.btn-delete-selected', function() {
        if (selectedIds.length === 0) return;
        $('#hidden-ids').val(selectedIds.join(','));
        $('.delete-selected-count').text(selectedIds.length);
        $('#confirm-delete-selected').modal('show');
    });

    $('#form-delete-selected').on('submit', function(e) {
        var ids = $('#hidden-ids').val();
        if (!ids) { e.preventDefault(); return false; }
        var form = $(this);
        form.find('input[name="ids[]"]').remove();
        ids.split(',').forEach(function(id) {
            var val = id.trim();
            if (val) form.append($('<input>').attr({ type: 'hidden', name: 'ids[]', value: val }));
        });
    });

    $(document).on('click', '.delete-btn', function() {
        var url = $(this).data('href');
        $('#confirm-delete').modal('show');
        $('#confirm-delete').find('.btn-ok').attr('href', url);
    });

    $(document).on('click', '.btn-delete-all', function() {
        $('#confirm-delete-all').modal('show');
    });

    $(document).on('click', '.btn-kill-switch-action', function() {
        var btn = $(this);
        if (parseInt(btn.data('db-ready'), 10) !== 1) {
            showCacheToast('warning', 'আগে প্রজেক্ট ফোল্ডারে <code>php artisan migrate</code> চালিয়ে <code>unauthorized_logs</code> টেবিলে <code>kill_switch</code> কলাম যোগ করুন। তারপর পেজ রিফ্রেশ করুন।');
            return;
        }
        var domain = btn.data('domain');
        var url = btn.data('url');
        var isOn = parseInt(btn.data('kill-active'), 10) === 1;
        var msg = isOn
            ? '"' + domain + '" এর Kill switch বন্ধ করবেন?\n\nক্লায়েন্ট আর wipe_out পাবে না।'
            : '"' + domain + '" এ Kill switch চালু করবেন?\n\nক্লায়েন্ট সাইট পরবর্তী cache-signal/লোডে action=wipe_out পেলে সেল্ফ-ডেস্ট্রাক্ট হতে পারে।';

        if (!confirm(msg)) return;

        btn.addClass('loading').html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: url,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.status === 'success') {
                    showCacheToast('success', res.message);
                    table.ajax.reload(null, false);
                } else {
                    btn.removeClass('loading');
                    restoreKillSwitchButton(btn, isOn);
                    showCacheToast('danger', res.message || 'Kill switch আপডেট করা যায়নি।');
                }
            },
            error: function(xhr) {
                btn.removeClass('loading');
                restoreKillSwitchButton(btn, isOn);
                showCacheToast('danger', ajaxErrorMessage(xhr, 'Kill switch আপডেট করা যায়নি।'));
            }
        });
    });

    function restoreKillSwitchButton(btn, wasOn) {
        btn.removeClass('loading');
        if (wasOn) {
            btn.removeClass('btn-kill-switch-off').addClass('btn-kill-switch-on')
                .html('<i class="fas fa-power-off"></i>')
                .attr('data-kill-active', '1')
                .attr('title', 'Kill switch বন্ধ করুন (ক্লায়েন্টে wipe_out বন্ধ)');
        } else {
            btn.removeClass('btn-kill-switch-on').addClass('btn-kill-switch-off')
                .html('<i class="fas fa-skull"></i>')
                .attr('data-kill-active', '0')
                .attr('title', 'Kill switch চালু — cache-signal এ action=wipe_out, ক্লায়েন্ট সেল্ফ-ডেস্ট্রাক্ট হতে পারে');
        }
    }

    $(document).on('click', '.btn-clear-cache-action', function() {
        var btn = $(this);
        var domain = btn.data('domain');
        var url = btn.data('url');

        if (!confirm('"' + domain + '" সাইটের cache clear signal পাঠাবেন?\n\nসর্বোচ্চ ৩০ মিনিটের মধ্যে client site এর license cache মুছে যাবে।')) {
            return;
        }

        btn.addClass('loading').html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: url,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.status === 'success') {
                    btn.removeClass('loading btn-clear-cache')
                       .addClass('btn-clear-cache-success')
                       .html('<i class="fas fa-check"></i>')
                       .attr('title', 'Cache Signal পাঠানো হয়েছে');
                    showCacheToast('success', res.message);
                } else {
                    btn.removeClass('loading').html('<i class="fas fa-bolt"></i>');
                    showCacheToast('danger', res.message || 'Cache signal পাঠাতে সমস্যা হয়েছে।');
                }
            },
            error: function() {
                btn.removeClass('loading').html('<i class="fas fa-bolt"></i>');
                showCacheToast('danger', 'Cache signal পাঠাতে সমস্যা হয়েছে।');
            }
        });
    });

    function restoreBlockButtonIcon(btn, wasBlocked) {
        btn.removeClass('loading');
        if (wasBlocked) {
            btn.removeClass('btn-block-domain').addClass('btn-unblock-domain')
                .html('<i class="fas fa-unlock"></i>')
                .attr('title', 'এই ডোমেইন Unblock করুন');
        } else {
            btn.removeClass('btn-unblock-domain').addClass('btn-block-domain')
                .html('<i class="fas fa-ban"></i>')
                .attr('title', 'এই ডোমেইন Block করুন');
        }
    }

    function ajaxErrorMessage(xhr, fallback) {
        var j = xhr.responseJSON;
        if (j && j.message) return j.message;
        if (j && j.errors) {
            var first = Object.keys(j.errors)[0];
            if (first && j.errors[first] && j.errors[first][0]) return j.errors[first][0];
        }
        if (xhr.status === 419) return 'সেশন মেয়াদ শেষ। পেজ রিফ্রেশ করে আবার চেষ্টা করুন।';
        if (xhr.status === 401 || xhr.status === 403) return 'লগইন বা অনুমতি সমস্যা।';
        return fallback;
    }

    $(document).on('click', '.btn-toggle-block-action', function() {
        var btn = $(this);
        var domain = btn.data('domain');
        var url = btn.data('url');
        var isBlocked = parseInt(btn.data('blocked'), 10) === 1;
        var actionText = isBlocked ? 'unblock' : 'block';

        if (!confirm('"' + domain + '" ডোমেইনটি ' + actionText + ' করতে চান?')) return;

        btn.addClass('loading').html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: url,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.status === 'success') {
                    btn.removeClass('loading');
                    showCacheToast('success', res.message);
                    table.ajax.reload(null, false);
                } else {
                    restoreBlockButtonIcon(btn, isBlocked);
                    showCacheToast('danger', res.message || 'ডোমেইন স্ট্যাটাস আপডেট করা যায়নি।');
                }
            },
            error: function(xhr) {
                restoreBlockButtonIcon(btn, isBlocked);
                showCacheToast('danger', ajaxErrorMessage(xhr, 'ডোমেইন স্ট্যাটাস আপডেট করা যায়নি।'));
            }
        });
    });

    function showCacheToast(type, message) {
        var toast = $('#cache-toast');
        toast.html(
            '<div class="alert alert-' + type + ' alert-dismissible shadow mb-0 cache-toast-alert" role="alert">' +
            '<i class="fas ' + (type === 'success' ? 'fa-bolt text-warning' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle') + ' mr-2"></i>' +
            message +
            '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>' +
            '</div>'
        ).show();
        setTimeout(function() { toast.fadeOut(400, function() { $(this).hide().empty(); }); }, 5000);
    }
});
</script>
@endsection
