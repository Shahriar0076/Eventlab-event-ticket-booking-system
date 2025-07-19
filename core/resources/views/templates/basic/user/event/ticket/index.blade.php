@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-table">
        <div class="dashbaord-table-header">
            <h6 class="card-header__title mb-0 text-dark">@lang('Tickets')</h6>
            <form class="search-form active">
                <input type="text" name="search" class="form--control" value="{{ request()->search }}"
                    placeholder="@lang('Search by event name')">
                <button type="submit" class="search-form__btn"><i class="las la-search"></i></button>
            </form>
        </div>

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

        @if ($tickets->hasPages())
            <div class="card-footer">
                {{ paginateLinks($tickets) }}
            </div>
        @endif

    </div>
@endsection
