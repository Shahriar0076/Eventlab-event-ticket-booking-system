@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30 justify-content-center">
        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card  overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Order Details')</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Event Title')
                            <span class="fw-bold">{{ __($order->event->title) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Organized by')
                            <span class="fw-bold">
                                <a href="{{ route('admin.organizers.detail', $order->event->organizer->id) }}">
                                    {{ __($order->event->organizer->organization_name) }}
                                </a>
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('User Name')
                            <span class="fw-bold">
                                <a href="{{ route('admin.users.detail', $order->user_id) }}">
                                    {{ __($order->user->username) }}
                                </a>
                            </span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Price')
                            <span class="fw-bold">{{ gs('cur_sym') }}{{ $order->price }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Quantity')
                            <span class="fw-bold">{{ $order->quantity }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Total')
                            <span class="fw-bold">{{ gs('cur_sym') }}{{ $order->total_price }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Payment Status')
                            <span class="fw-bold">@php echo $order->paymentData @endphp</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            <span class="fw-bold">@php echo $order->statusBadge @endphp</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-md-6 mb-30">
            <div class="card  overflow-hidden box--shadow1 mb-3">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Tickets Details')</h5>
                    <ul class="list-group">
                        @foreach ($order->details as $key => $detail)
                            <li class="list-group-item mt-0">
                                <p>@lang('Ticket') {{ $loop->iteration }}</p>
                                <p>@lang('First Name'): {{ $detail['first_name'] }}</p>
                                <p>@lang('Last Name'): {{ $detail['last_name'] }}</p>
                                <p>@lang('Email'): {{ $detail['email'] }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $(document).ready(function() {
                $('#changeStatusForm select').on('change', function() {
                    $('#changeStatusForm').submit();
                });
            });
        })(jQuery);
    </script>
@endpush
