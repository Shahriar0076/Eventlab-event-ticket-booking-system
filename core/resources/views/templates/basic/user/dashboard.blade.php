@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $kyc = getContent('kyc.content', true);
    @endphp
    <div class="notice"></div>
    <div class="row g-3 justify-content-center">
        @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
            <div class="col-12">
                <div class="card custom--card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title">@lang('KYC Documents Rejected')</h5>
                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#kycRejectionReason">@lang('Show Reason')</button>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            {{ __(@$kyc->data_values->reject) }}
                            <a href="{{ route('user.kyc.form') }}">@lang('Click Here to Re-submit Documents')</a>
                        </p>
                        <br>
                        <a href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->kv == Status::KYC_UNVERIFIED)
            <div class="col-12">
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('KYC Verification Required')</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ __(@$kyc->data_values->required) }} <a href="{{ route('user.kyc.form') }}">
                                @lang('Click Here to Submit Documents')</a></p>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->kv == Status::KYC_PENDING)
            <div class="col-12">
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('KYC Verification Pending')</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ __(@$kyc->data_values->pending) }} <a
                                href="{{ route('user.kyc.data') }}">@lang('See KYC Data')</a></p>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12">
            <div class="row gy-3 justify-content-center dashboard-widget-wrapper">
                <div class="col-xl-4 col-md-4 col-sm-6 col-xsm-6">
                    <a class="dashboard-widget flex-align" href="{{ route('user.transactions') }}">
                        <span class="dashboard-widget__icon flex-center"><i class="lar la-credit-card"></i></span>
                        <div class="dashboard-widget__content">
                            <span class="dashboard-widget__text">@lang('Balance')</span>
                            <h4 class="dashboard-widget__number">
                                {{ showAmount(auth()->user()->balance) }}</h4>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-6 col-xsm-6">
                    <a class="dashboard-widget flex-align" href="{{ route('user.event.ticket.index') }}">
                        <span class="dashboard-widget__icon flex-center"><i class="las la-calendar-check"></i></span>
                        <div class="dashboard-widget__content">
                            <span class="dashboard-widget__text">@lang('Total Tickets')</span>
                            <h4 class="dashboard-widget__number">{{ $widget['total_tickets'] }}</h4>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-6 col-xsm-6">
                    <a class="dashboard-widget flex-align" href="{{ route('user.event.ticket.refunded') }}">
                        <span class="dashboard-widget__icon flex-center"><i class="las la-truck-loading"></i></span>
                        <div class="dashboard-widget__content">
                            <span class="dashboard-widget__text">@lang('Refunded Tickets')</span>
                            <h4 class="dashboard-widget__number">{{ $widget['refunded_tickets'] }}</h4>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-6 col-xsm-6">
                    <a class="dashboard-widget flex-align" href="{{ route('user.liked.events') }}">
                        <span class="dashboard-widget__icon flex-center"><i class="las la-thumbs-up"></i></span>
                        <div class="dashboard-widget__content">
                            <span class="dashboard-widget__text">@lang('Liked Events')</span>
                            <h4 class="dashboard-widget__number">{{ $widget['liked_events'] }}</h4>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-6 col-xsm-6">
                    <a class="dashboard-widget flex-align" href="{{ route('user.following.organizers') }}">
                        <span class="dashboard-widget__icon flex-center"><i class="las la-user-plus"></i></span>
                        <div class="dashboard-widget__content">
                            <span class="dashboard-widget__text">@lang('Following Organizers')</span>
                            <h4 class="dashboard-widget__number">{{ $widget['following_organizers'] }}</h4>
                        </div>
                    </a>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-6 col-xsm-6">
                    <a class="dashboard-widget flex-align" href="{{ route('user.deposit.history') }}">
                        <span class="dashboard-widget__icon flex-center"><i class="las la-file-invoice-dollar"></i></span>
                        <div class="dashboard-widget__content">
                            <span class="dashboard-widget__text">@lang('Total Deposit')</span>
                            <h4 class="dashboard-widget__number">
                                {{ showAmount($widget['total_deposit']) }}</h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-table mt-5">
        <table class="table table--responsive--xl">
            <thead>
                <tr>
                    <th>@lang('Event Title')</th>
                    <th class="text-center">@lang('Price')</th>
                    <th class="text-center">@lang('Quantity')</th>
                    <th class="text-center">@lang('Total')</th>
                    <th class="text-center">@lang('Payment')</th>
                    <th class="text-center">@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>

                @forelse($tickets as $ticket)
                    <tr>
                        <td>
                            <span class="fw-bold"><a href="{{ route('event.details', $ticket->event->slug) }}"
                                    class="text--base"> {{ __(@$ticket->event->title) }}</a></span>
                        </td>
                        <td class="text-center">
                            {{ showAmount($ticket->price) }}
                        </td>
                        <td class="text-center">
                            {{ $ticket->quantity }}
                        </td>
                        <td class="text-center">
                            {{ showAmount($ticket->total_price) }}
                        </td>
                        <td class="text-center">
                            @php echo $ticket->paymentData @endphp
                        </td>
                        <td class="text-center">
                            @php echo $ticket->statusBadge @endphp
                        </td>
                        <td>
                            <a class="btn btn--sm btn--base detailBtn"
                                href="{{ route('user.event.ticket.details', $ticket->id) }}">
                                <i class="la la-desktop"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (auth()->user()->kv == Status::KYC_UNVERIFIED && auth()->user()->kyc_rejection_reason)
        <div class="modal fade" id="kycRejectionReason">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ auth()->user()->kyc_rejection_reason }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
