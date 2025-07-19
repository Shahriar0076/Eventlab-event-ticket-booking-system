@extends($activeTemplate . 'organizer.layouts.master')
@section('content')
    <div class="row justify-content-center gy-4">

        @forelse($tickets as $ticket)
            <div class="col-lg-4 col-md-6">
                <div class="ticket-item">
                    <ul class="ticket-list mt-0">
                        <li class="ticket-list__item"><span>@lang('Name'):</span><span
                                class="detail">{{ __($ticket->user->fullname) }}</span></li>
                        <li class="ticket-list__item"><span>@lang('Email'):</span><span
                                class="detail">{{ __($ticket->user->email) }}</span></li>
                        <li class="ticket-list__item"><span>@lang('Price'):</span><span
                                class="detail">{{ showAmount($ticket->price) }}</span></li>
                        <li class="ticket-list__item"><span>@lang('Quantity'):</span><span
                                class="detail">{{ $ticket->quantity }}</span></li>
                        <li class="ticket-list__item total"><span>@lang('Total'):</span><span
                                class="detail total">{{ showAmount($ticket->total_price) }}</span></li>
                    </ul>
                    <ul class="ticket-list">
                        <li class="ticket-list__item"><span>@lang('Payment'):</span>@php echo $ticket->paymentData @endphp</li>
                        <li class="ticket-list__item"><span>@lang('Status'):</span>@php echo $ticket->statusBadge @endphp</li>
                    </ul>
                    @if ($ticket->status == Status::ORDER_CANCELLED)
                        <span class="ticket-alert text--danger">@lang('Ticket is Cancelled')</span>
                    @endif
                    <div class="ticket-item__btn">
                        <button type="button" class="btn btn-outline--primary w-100 btn--sm openModal"
                            data-tickets='@json($ticket->details)'
                            data-id='{{ encrypt($ticket->id) }}'>@lang('View Tickets') </button>
                        @if ($ticket->status != Status::ORDER_CANCELLED)
                            <button class="btn btn-outline--danger w-100 btn--sm confirmationBtn"
                                data-question="@lang('Are you sure to cancel this event?') @if ($ticket->payment_status == Status::PAID) {{ __('User will get a refund if you cancel this ticket.') }} @endif "
                                data-action="{{ route('organizer.event.ticket.cancel', $ticket->id) }}">
                                @lang('Cancel Ticket')
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <tr>
                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
            </tr>
        @endforelse
        {{ paginateLinks($tickets) }}
    </div>

    <div class="modal fade" id="viewTicketsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('View Tickets')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <!-- Ticket details will be appended here -->
                </div>
                <div class="modal-footer">
                    <a class="btn btn--primary ticket-download-btn">@lang('Download Ticket')<i
                            class="las la-cloud-download-alt"></i></a>
                    <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('style')
    <style>
        .modal-body {
            max-height: 400px;
            /* Adjust the height as needed */
            overflow-y: auto;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.openModal').on('click', function() {
                var tickets = $(this).data('tickets');
                var id = $(this).data('id');

                var modalBody = $('#viewTicketsModal').find('.modal-body');
                modalBody.empty(); // Clear previous content

                // Iterate over tickets data and create HTML elementspublished for verification
                tickets.forEach(function(ticket, index) {
                    var ticketNumber = index + 1;
                    var ticketInfo = $('<div class="ticket-info"></div>');
                    ticketInfo.append('<p><strong>Ticket ' + ticketNumber + ' </strong></p>');
                    ticketInfo.append('<p><strong>Name:</strong> ' + ticket['first_name'] + ' ' +
                        ticket['last_name'] + '</p>');
                    ticketInfo.append('<p><strong>Email:</strong> ' + ticket['email'] + '</p>');
                    ticketInfo.append('<hr>');
                    modalBody.append(ticketInfo);
                });

                $('#viewTicketsModal').find('.ticket-download-btn').attr('href',
                    '{{ route('home') }}/ticket-download/' + id);
                $('#viewTicketsModal').modal('show');
            });
        })(jQuery);
    </script>
@endpush
