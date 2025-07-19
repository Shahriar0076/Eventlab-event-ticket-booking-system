@extends($activeTemplate . 'layouts.master')

@section('content')
    @php
        $ticketCancelled = $ticket->status == Status::ORDER_CANCELLED;
        $eventWillStart = $ticket->event->start_date > Carbon\Carbon::now();
        $onlineEvent = $ticket->event->type == Status::EVENT_ONLINE;
        $eventFeeNotPaid = $ticket->payment_status == Status::UNPAID;
    @endphp

    <div class="ticket-detail-actions">
        <div>
            <h4>
                @lang('Tickets') <a href="{{ route('event.ticket.download', encrypt($ticket->id)) }}"
                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="@lang('Download Ticket')"><i
                        class="las la-cloud-download-alt"></i></a>
            </h4>
        </div>

        <div class="detail-buttons">
            @if ($eventFeeNotPaid && !$ticketCancelled)
                <a href="{{ route('user.deposit.index', $ticket->id) }}" class="btn btn--warning">@lang('Make Payment')</a>
            @endif

            @if (!$ticketCancelled)
                @if (!$eventWillStart && $onlineEvent)
                    <a href="{{ $ticket->event->link }}" class="btn btn--base" target="_blank">@lang('Event Link')</a>
                @endif
            @endif
            <a href="{{ route('event.details', $ticket->event->slug) }}" class="btn btn--base ">@lang('View Event')</a>

            @if (!$ticketCancelled && $ticket->canCancel(gs('cancel_time')))
                <button class="btn btn--danger  confirmationBtn"
                    data-question="@lang('Are you sure to cancel this event?') @if ($ticket->payment_status == Status::PAID) {{ __('You will get a refund if you cancel this ticket.') }} @endif "
                    data-action="{{ route('user.event.ticket.cancel', $ticket->id) }}">@lang('Cancel Ticket')</button>
            @endif
        </div>
    </div>
    @if (!$ticketCancelled)
        @if ($eventWillStart && $onlineEvent)
            <div class="alert alert--primary mt-3" role="alert">
                @lang('You will get the event link once the event starts')
            </div>
        @endif
    @else
        <div class="alert alert--danger mt-3" role="alert">
            @lang('You ticket is cancelled')
        </div>
    @endif

    <div class="row mt-4">
        @foreach ($ticket->details as $key => $detail)
            <div class="col-sm-6 col-md-6 col-lg-4">
                <div class="tickets">
                    <p class="text--base heading">@lang('Ticket') {{ $loop->iteration }}</p>
                    <p>@lang('First Name'): <span>{{ $detail['first_name'] }}</span></p>
                    <p>@lang('Last Name'): <span>{{ $detail['last_name'] }}</span></p>
                    <p>@lang('Email'): <span>{{ $detail['email'] }}</span></p>
                    <p>@lang('Price'): <span>{{ showAmount($ticket->price) }}</span></p>
                </div>
            </div>
        @endforeach
    </div>
    <hr>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="invoice">
                <div>
                    <span>@lang('Organizer Name'):</span>
                    <span>
                        <a href="{{ route('organizer.details', $ticket->event->organizer->slug) }}">
                            {{ __($ticket->event->organizer->organization_name) }}
                        </a>
                    </span>
                </div>
                <div>
                    <span>@lang('Organizer Email'):</span>
                    <span>{{ $ticket->event->organizer->email }}</span>
                </div>
                <div>
                    <span>@lang('Event Name'):</span>
                    <span>
                        <a href="{{ route('event.details', $ticket->event->slug) }}">{{ __($ticket->event->title) }}</a>

                    </span>
                </div>
                <div>
                    <span>@lang('Ticket Price'):</span>
                    <span>{{ $ticket->price }}</span>
                </div>
                <div>
                    <span>@lang('Quantity'):</span>
                    <span>{{ $ticket->quantity }}</span>
                </div>
                <div>
                    <span>@lang('Total Price'):</span>
                    <span class="text--base total">{{ showAmount($ticket->total_price) }}</span>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection
