@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    @forelse($notifications as $notification)
                        <div class="notification-item @if (!$notification->user_read) notification-item--unread @endif">
                            <div class="notification-item__icon">
                                @if ($notification->notification_type == 'email')
                                    <i class="las la-envelope"></i>
                                @elseif ($notification->notification_type == 'sms')
                                    <i class="las la-sms"></i>
                                @else
                                    <i class="las la-bell"></i>
                                @endif
                            </div>
                            <div class="notification-item__body">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <h6 class="mb-1">
                                        {{ __($notification->subject) }}
                                        @if (!$notification->user_read)
                                            <span class="badge badge--success ms-1">@lang('New')</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted">{{ showDateTime($notification->created_at) }}</small>
                                </div>
                                <p class="mb-0 text-muted small">{{ Str::limit(strip_tags($notification->message), 200) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="alert dashboard-card text-center">
                            @lang('No notifications yet')
                        </div>
                    @endforelse

                    @if ($notifications->hasPages())
                        {{ paginateLinks($notifications) }}
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .notification-item {
            display: flex;
            gap: 14px;
            padding: 16px;
            border: 1px solid rgb(229 229 229 / 15%);
            border-radius: 10px;
            margin-bottom: 12px;
            background: rgba(255,255,255,.02);
        }

        .notification-item--unread {
            border-color: rgba(172,230,0,.45);
            background: rgba(172,230,0,.05);
        }

        .notification-item__icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(172,230,0,.12);
            color: #ACE600;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex: none;
        }

        .notification-item__body {
            flex: 1;
            min-width: 0;
        }
    </style>
@endpush
