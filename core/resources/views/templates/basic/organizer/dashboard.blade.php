@extends($activeTemplate . 'organizer.layouts.master')
@section('content')
    @php
        $kyc = getContent('kyc.content', true);
        $organizer = authOrganizer();
    @endphp

    <div class="notice"></div>

    @if ($organizer->kv == Status::KYC_UNVERIFIED && $organizer->kyc_rejection_reason)
        <div class="dashboard-card mb-4">
            <div class="card-header d-flex justify-content-between">
                <h5 class="dashboard-card__title">@lang('KYC Documents Rejected')</h5>
                <button class="btn btn-outline--secondary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#kycRejectionReason">@lang('Show Reason')</button>
            </div>
            <p class="mb-0">{{ __(@$kyc->data_values->reject) }} <a href="{{ route('organizer.kyc.form') }}">
                    @lang('Click here to verify')</a></p>
        </div>
    @elseif ($organizer->kv == Status::KYC_UNVERIFIED)
        <div class="dashboard-card mb-4">
            <h5 class="dashboard-card__title">@lang('KYC Verification required')</h5>
            <p class="mb-0">{{ __(@$kyc->data_values->required) }} <a href="{{ route('organizer.kyc.form') }}">
                    @lang('Click here to verify')</a></p>
        </div>
    @elseif($organizer->kv == Status::KYC_PENDING)
        <div class="dashboard-card mb-4">
            <h5 class="dashboard-card__title">@lang('KYC Verification Pending')</h5>
            <p class="mb-0">{{ __(@$kyc->data_values->pending) }} <a
                    href="{{ route('organizer.kyc.data') }}">@lang('See KYC Data')</a>
            </p>
        </div>
    @endif

    <div class="row gy-4">
        <div class="col-12">
            <div class="row gy-4 dashboard-widget-wrapper">
                <div class="col-xxl-3 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('organizer.transactions') }}" class="dashboard-widget">
                        <div class="dashboard-widget__content">
                            <h3 class="dashboard-widget__number">
                                {{ showAmount($organizer->balance) }} </h3>
                            <span class="dashboard-widget__text"> @lang('Balance') </span>
                        </div>
                        <div class="dashboard-widget__icon">
                            <i class="fas fa-money-check"></i>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-3 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('organizer.event.index') }}" class="dashboard-widget">
                        <div class="dashboard-widget__content">
                            <h3 class="dashboard-widget__number"> {{ $widget['total_events'] }} </h3>
                            <span class="dashboard-widget__text"> @lang('Total Events') </span>
                        </div>
                        <div class="dashboard-widget__icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-3 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('organizer.event.upcoming') }}" class="dashboard-widget">
                        <div class="dashboard-widget__content">
                            <h3 class="dashboard-widget__number"> {{ $widget['upcoming_events'] }} </h3>
                            <span class="dashboard-widget__text"> @lang('Upcoming Events') </span>
                        </div>
                        <div class="dashboard-widget__icon">
                            <i class="far fa-calendar-plus"></i>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-3 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('organizer.event.running') }}" class="dashboard-widget">
                        <div class="dashboard-widget__content">
                            <h3 class="dashboard-widget__number"> {{ $widget['running_events'] }} </h3>
                            <span class="dashboard-widget__text"> @lang('Running Events') </span>
                        </div>
                        <div class="dashboard-widget__icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-3 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content">
                            <h3 class="dashboard-widget__number"> {{ $widget['followers_count'] }} </h3>
                            <span class="dashboard-widget__text"> @lang('Followers')</span>
                        </div>
                        <div class="dashboard-widget__icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                    <div class="dashboard-widget">
                        <div class="dashboard-widget__content">
                            <h3 class="dashboard-widget__number"> {{ $widget['total_sold_count'] }} </h3>
                            <span class="dashboard-widget__text"> @lang('Ticket Sale') </span>
                        </div>
                        <div class="dashboard-widget__icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('organizer.withdraw.history') }}" class="dashboard-widget">
                        <div class="dashboard-widget__content">
                            <h3 class="dashboard-widget__number"> {{ $widget['pending_withdraw'] }} </h3>
                            <span class="dashboard-widget__text">@lang('Pending Withdrawals') </span>
                        </div>
                        <div class="dashboard-widget__icon">
                            <i class="fas fa-arrow-alt-circle-down"></i>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-3 col-lg-6 col-md-4 col-sm-6 col-xsm-6">
                    <a href="{{ route('organizer.event.pending') }}" class="dashboard-widget">
                        <div class="dashboard-widget__content">
                            <h3 class="dashboard-widget__number"> {{ $widget['pending_events'] }} </h3>
                            <span class="dashboard-widget__text"> @lang('Pending Events') </span>
                        </div>
                        <div class="dashboard-widget__icon">
                            <i class="fas fa-angle-double-down"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="dashboard-body__wrapper">
        <div class="row gy-4">
            <div class="col-xxl-6">
                <div class="dashboard-card">
                    <h5 class="dashboard-card__title">@lang('Total Revenue')</h5>
                    <div id="organizer-apex-bar-chart"> </div>
                </div>
            </div>
            <div class="col-xxl-6">
                <div class="dashboard-card">
                    <h5 class="dashboard-card__title"> @lang('Events') </h5>
                    <table class="table table--responsive--lg">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Seats')</th>
                                <th>@lang('Booked')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $key => $event)
                                <tr>
                                    <td> {{ $key + 1 }} </td>
                                    <td> {{ $event->title }} </td>
                                    <td>
                                        @php
                                            $bookedPercentage = ($event->seats_booked / $event->seats) * 100;
                                            $bookedPercentage = ceil($bookedPercentage);
                                        @endphp

                                        <div class="progress custom--progress">
                                            <div class="progress-bar bg--base" role="progressbar"
                                                aria-label="Success example" style="width: {{ $bookedPercentage }}%"
                                                aria-valuenow="{{ 100 - $bookedPercentage }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge--base"> {{ $bookedPercentage }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if ($organizer->kv == Status::KYC_UNVERIFIED && $organizer->kyc_rejection_reason)
        <div class="modal fade" id="kycRejectionReason">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('KYC Document Rejection Reason')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ $organizer->kyc_rejection_reason }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/chart.js.2.8.0.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var options = {
                series: [{
                    name: 'Total Revenue',
                    data: [
                        @foreach ($months as $month)
                            {{ getAmount(@$organizerOrderMonth->where('months', $month)->first()->orderTotal) }},
                        @endforeach
                    ]
                }],
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: @json($months),
                },
                yaxis: {
                    title: {
                        text: "{{ gs('cur_sym') }}",
                        style: {
                            color: '#0063ff'
                        }
                    }
                },
                grid: {
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    },
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return "{{ gs('cur_sym') }}" + val + " "
                        }
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#organizer-apex-bar-chart"), options);
            chart.render();
        })(jQuery);
    </script>
@endpush
