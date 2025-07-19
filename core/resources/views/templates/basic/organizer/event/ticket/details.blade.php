@extends($activeTemplate . 'organizer.layouts.master')

@section('content')
    <div class="row justify-content-space-betweeb">
        <div class="col-4">
            <div class="card custom--card">
                <div class="card-body">
                    <h4>@lang('Actions')</h4>
                    <a href="{{ getEventRoute($ticket->event) }}" class="btn btn--base w-100 my-3">@lang('Go to Event')</a>
                    @if ($ticket->status == Status::ORDER_CANCELLED)
                        <h6 class="text--danger mt-2">@lang('Ticket is Cancelled')</h6>
                    @else
                        <button class="btn btn--base w-100 my-3 confirmationBtn"
                            data-question="@lang('Are you sure to cancel this event?') @if ($ticket->payment_status == Status::PAID) {{ __('User will get a refund if you cancel this ticket.') }} @endif "
                            data-action="{{ route('organizer.event.ticket.cancel', $ticket->id) }}">
                            @lang('Cancel Event')
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-8">
            <div class="card custom--card">
                <div class="card-body">
                    <h4>@lang('Ticket Details')</h4>
                    <p>@lang('Organizer email'): {{ $ticket->event->organizer->email }}</p>

                    @foreach ($ticket->details as $key => $detail)
                        <hr>
                        <p>@lang('Ticket') {{ $loop->iteration }}</p>
                        <p>@lang('First Name'): {{ $detail['first_name'] }}</p>
                        <p>@lang('Last Name'): {{ $detail['last_name'] }}</p>
                        <p>@lang('Email'): {{ $detail['email'] }}</p>
                        <p>@lang('Price'): {{ showAmount($ticket->price) }}</p>
                    @endforeach
                    <hr>
                    @lang('Total Price'): {{ showAmount($ticket->total_price) }}
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection
