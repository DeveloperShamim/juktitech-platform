@extends('layouts.admin')

@section('styles')
<style>
    .adm-pro .chat-box {
        height: 500px;
        overflow-y: auto;
        padding: 20px;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #edf2f7;
        scrollbar-width: thin;
        scroll-behavior: smooth;
    }
    .adm-pro .chat-box::-webkit-scrollbar { width: 6px; }
    .adm-pro .chat-box::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }

    .adm-pro .message-row { margin-bottom: 25px; display: flex; align-items: flex-end; gap: 10px; }
    .adm-pro .message-row.admin-row { flex-direction: row-reverse; }

    .adm-pro .chat-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        color: white;
        flex-shrink: 0;
    }
    .adm-pro .avatar-customer { background: #6366f1; }
    .adm-pro .avatar-admin { background: #10b981; }

    .adm-pro .bubble {
        max-width: 70%;
        padding: 12px 16px;
        font-size: 14px;
        line-height: 1.6;
        position: relative;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .adm-pro .bubble-customer {
        background: #f1f5f9;
        color: #334155;
        border-radius: 18px 18px 18px 4px;
    }
    .adm-pro .bubble-admin {
        background: #4f46e5;
        color: #ffffff;
        border-radius: 18px 18px 4px 18px;
    }

    .adm-pro .msg-meta { font-size: 10px; margin-top: 6px; opacity: 0.7; font-weight: 500; }
    .adm-pro .bubble-admin .msg-meta { text-align: right; color: #e0e7ff; }

    .adm-pro .reply-section { background: #fff; border-radius: 15px; padding: 20px; border: 1px solid #edf2f7; }
    .adm-pro .status-select { font-weight: 600; border-radius: 8px; border: 1px solid #e2e8f0; cursor: pointer; }

    .adm-pro .sound-control {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #eef2ff;
        color: #4f46e5;
        padding: 6px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        border: 1px solid #c7d2fe;
        transition: 0.3s;
    }
    .adm-pro .sound-control:hover { background: #e0e7ff; }
    .adm-pro .sound-control.muted { background: #fee2e2; color: #ef4444; border-color: #fecaca; }
</style>
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-comments',
        'title' => $ticket->subject,
        'subtitle' => __('Ticket') . ' #' . $ticket->id . ' — ' . __('Client') . ': ' . $ticket->user_name,
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Tickets'), 'url' => route('admin.tickets.index')],
            ['label' => '#' . $ticket->id, 'url' => null],
        ],
    ])

    <div class="adm-panel mb-4">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-cog"></i> {{ __('Ticket Controls') }}</h3>
            <div class="d-flex align-items-center flex-wrap adm-toolbar-gap">
                <div class="sound-control" id="soundToggleBtn" title="Click to mute/unmute">
                    <i class="fas fa-volume-up" id="soundIcon"></i>
                    <span id="soundText">Sound ON</span>
                </div>
                <select class="form-control status-select form-control-sm" id="updateStatus">
                    <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>🕒 Pending</option>
                    <option value="replied" {{ $ticket->status == 'replied' ? 'selected' : '' }}>✅ Replied</option>
                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>🔒 Closed</option>
                </select>
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-light btn-sm border">
                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                </a>
            </div>
        </div>
    </div>

    <div class="adm-panel">
        <div class="adm-panel__body p-0">
            <div class="chat-box" id="chatContainer">

                <div class="message-row">
                    <div class="chat-avatar avatar-customer" title="{{ $ticket->user_name }}">
                        {{ substr($ticket->user_name, 0, 1) }}
                    </div>
                    <div class="bubble bubble-customer">
                        <strong class="small d-block mb-1">{{ __('Issue Description') }}</strong>
                        {{ $ticket->message }}
                        <div class="msg-meta">{{ \Carbon\Carbon::parse($ticket->created_at)->format('h:i A') }}</div>
                    </div>
                </div>

                @foreach($replies as $reply)
                @php $isAdmin = !empty($reply->admin_id); @endphp
                <div class="message-row {{ $isAdmin ? 'admin-row' : '' }}">
                    <div class="chat-avatar {{ $isAdmin ? 'avatar-admin' : 'avatar-customer' }}">
                        {{ $isAdmin ? 'A' : substr($reply->customer_name ?? 'C', 0, 1) }}
                    </div>
                    <div class="bubble {{ $isAdmin ? 'bubble-admin' : 'bubble-customer' }}">
                        {{ $reply->message }}
                        <div class="msg-meta">{{ \Carbon\Carbon::parse($reply->created_at)->diffForHumans() }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="reply-section p-4">
                <form id="replyForm">
                    @csrf
                    <div class="form-group position-relative">
                        <textarea name="message" id="replyMessage" class="form-control p-3 rounded" rows="3" placeholder="Write your professional response here..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-muted small"><i class="fas fa-info-circle"></i> {{ __('Customer will be notified via email.') }}</span>
                        <button type="submit" class="btn btn-primary px-5 py-2 font-weight-bold shadow-sm" id="sendBtn">
                            {{ __('Send Reply') }} <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {

        var notificationSound = new Audio("{{ asset_image('assets/images/ring.mp3') }}");
        notificationSound.loop = true;
        notificationSound.volume = 1.0;

        var isSoundEnabled = true;

        $('#soundToggleBtn').click(function() {
            isSoundEnabled = !isSoundEnabled;
            if(isSoundEnabled) {
                $(this).removeClass('muted');
                $('#soundIcon').removeClass('fa-volume-mute').addClass('fa-volume-up');
                $('#soundText').text('Sound ON');
            } else {
                $(this).addClass('muted');
                $('#soundIcon').removeClass('fa-volume-up').addClass('fa-volume-mute');
                $('#soundText').text('Sound OFF');
                notificationSound.pause();
                notificationSound.currentTime = 0;
            }
        });

        $('#replyMessage').on('focus click keydown', function() {
            if(!notificationSound.paused) {
                notificationSound.pause();
                notificationSound.currentTime = 0;
            }
        });

        var container = document.getElementById("chatContainer");
        if(container) container.scrollTop = container.scrollHeight;

        var lastReplyId = "{{ $replies->count() > 0 ? $replies->last()->id : 0 }}";
        var baseCheckUrl = "{{ route('admin.tickets.check_new', $ticket->id) }}";

        $('#updateStatus').on('change', function() {
            let status = $(this).val();
            $.ajax({
                url: "{{ route('admin.tickets.status', $ticket->id) }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", status: status },
                success: function(res) {
                    Swal.fire({ icon: 'success', title: 'Status Updated', toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                }
            });
        });

        $('#replyForm').on('submit', function(e) {
            e.preventDefault();

            notificationSound.pause();
            notificationSound.currentTime = 0;

            var message = $('#replyMessage').val();
            if(message.trim() == '') return;

            var formData = $(this).serialize();
            $('#replyMessage').val('');

            var btnOriginal = $('#sendBtn').html();
            $('#sendBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

            var myMessageHtml = `
                <div class="message-row admin-row">
                    <div class="chat-avatar avatar-admin">A</div>
                    <div class="bubble bubble-admin">
                        ${message}
                        <div class="msg-meta">Just now</div>
                    </div>
                </div>
            `;
            $('#chatContainer').append(myMessageHtml);
            container.scrollTop = container.scrollHeight;

            $.ajax({
                url: "{{ route('admin.tickets.reply', $ticket->id) }}",
                type: "POST",
                data: formData,
                success: function(res) {
                    $('#sendBtn').prop('disabled', false).html(btnOriginal);
                    if(res.last_id) lastReplyId = res.last_id;
                },
                error: function() {
                    $('#sendBtn').prop('disabled', false).html(btnOriginal);
                    Swal.fire('Error!', 'Message sending failed.', 'error');
                }
            });
        });

        function checkNewMessages() {
            var finalUrl = baseCheckUrl + "?t=" + new Date().getTime();

            $.ajax({
                url: finalUrl,
                type: "GET",
                data: { last_id: lastReplyId },
                success: function(res) {
                    if(res.has_new) {
                        $('#chatContainer').append(res.html);
                        container.scrollTop = container.scrollHeight;
                        lastReplyId = res.last_id;

                        if(res.html.includes('bubble-customer') && isSoundEnabled) {
                            notificationSound.play().catch(e => console.log('Autoplay blocked'));
                        }
                    }
                }
            });
        }

        setInterval(checkNewMessages, 3000);

        $(document).one('click', function() {
            notificationSound.play().then(() => {
                notificationSound.pause();
                notificationSound.currentTime = 0;
            }).catch(e => {});
        });
    });
</script>
@endsection
