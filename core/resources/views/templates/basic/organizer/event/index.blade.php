@extends($activeTemplate . 'organizer.layouts.master')
@section('content')
    <div class="dashboard-card">
        <h4 class="dashboard-card__title"> {{ __($pageTitle) }} </h4>
        <table class="table table--responsive--xxl">
            <thead>
                <tr>
                    <th>@lang('Title')</th>
                    <th>@lang('Seats')</th>
                    <th>@lang('Booked')</th>
                    <th>@lang('Price')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td class="event-date"> <a href="{{ getEventRoute($event) }}"
                                target="_blank">{{ __(@$event->title) }}</a></td>
                        <td> {{ $event->seats }} </td>
                        <td> {{ $event->seats_booked }} </td>
                        <td> {{ showAmount($event->price) }} </td>
                        <td>
                            <div>
                                <span class="event-date">{{ showDateTime($event->start_date, 'd M Y') }}</span> - <span
                                    class="event-date">{{ showDateTime($event->end_date, 'd M Y') }}</span>
                            </div>
                        </td>
                        <td>
                            <div>
                                @php echo $event->statusBadge(true) @endphp
                                @if ($event->status == Status::EVENT_REJECTED && $event->verification_details)
                                    <span class="infoIcon" data-detail="{{ __($event->verification_details) }}">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('organizer.event.overview', $event->id) }}" type="button"
                                    class="action-btn">
                                    <i class="las la-pen"></i> @lang('Edit')
                                </a>
                                <a href="{{ route('organizer.event.ticket.index', $event->id) }}" type="button"
                                    class="action-btn">
                                    <i class="las la-ticket-alt"></i> @lang('Tickets')
                                </a>
                                <a href="{{ getEventRoute($event) }}" type="button" class="action-btn">
                                    <i class="las la-laptop"></i> @lang('View')
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
        <div class="mt-4">
            {{ paginateLinks($events) }}
        </div>
    </div>

    <!-- Modal HTML -->
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData">

                    </ul>
                    <div class="feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .infoIcon {
            cursor: pointer;
        }

        a.action-btn {
            color: #000000;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            $('.infoIcon').on('click', function() {
                var detail = $(this).data('detail');
                $('#detailModal .userData').text(detail);
                $('#detailModal').modal('show');
            });
        });
    </script>
@endpush
