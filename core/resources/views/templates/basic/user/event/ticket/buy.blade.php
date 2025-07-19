@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="event-details-two-section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-xl-8">
                    <form action="{{ route('user.event.buy.ticket', $event->id, $quantity) }}" method="POST">
                        @csrf
                        <div class="information-box">
                            <h6 class="information-box__title"> @lang('Personal Information') </h6>
                            <div class="information-box__form">
                                <div class="information-box__wrapper">
                                    @for ($i = 0; $i < $quantity; $i++)
                                        <div class="information-box__item">
                                            <div class="flex-between information-box__top">
                                                <h6 class="information-box__item-title">@lang('Ticket number')
                                                    {{ $i + 1 }}
                                                </h6>
                                                <button class="close-icon text--danger"><i
                                                        class="far fa-trash-alt"></i></button>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 form-group">
                                                    <label for="name" class="form-label-two">@lang('First Name')</label>
                                                    <input type="text" name="details[{{ $i }}][first_name]"
                                                        class="form--control" required>
                                                </div>
                                                <div class="col-sm-6 form-group">
                                                    <label for="lastname" class="form-label-two"> @lang('Last Name')</label>
                                                    <input type="text" name="details[{{ $i }}][last_name]"
                                                        class="form--control" required>
                                                </div>
                                                <div class="col-sm-12 form-group">
                                                    <label for="mail" class="form-label-two">@lang('Email')</label>
                                                    <input type="email" name="details[{{ $i }}][email]"
                                                        class="form--control" required>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="ticket-box">
                                <ul class="ticket-list mt-2">
                                    <li class="ticket-list__item">
                                        @lang('Ticket Price'):
                                        <span class="number"><span id="ticketPrice"
                                                data-price="{{ $event->price }}">{{ showAmount($event->price) }}</span></span>
                                    </li>
                                    <li class="ticket-list__item">
                                        @lang('Number of Ticket'):
                                        <span class="number"> <span id="numberOfTickets"> </span> </span>
                                    </li>
                                    <li class="ticket-list__item">
                                        @lang('Total'):
                                        <span class="number"><span id="totalPrice">{{ showAmount($total) }}</span></span>
                                    </li>
                                </ul>
                                <p class="ticket-box__text"> @lang('You will get') {{ gs('payment_timeout') }}
                                    @lang('minutes to complete your payment') </p>
                                <input type="hidden" name="quantity" value="{{ $quantity }}">
                                <button type="submit" class="btn btn--base w-100"> @lang('Purchase') </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-4">
                    <div class="information-sidebar">
                        <h6 class="information-sidebar__title mb-0"> @lang('Ticket information') </h6>
                        <div class="information-sidebar__wrapper">
                            <div class="information-sidebar__thumb">
                                <img src="{{ getImage(getFilePath('eventCover') . '/thumb_' . $event->cover_image, getFileThumbSize('eventCover')) }}"
                                    alt="image">
                            </div>
                            <div class="location-information">
                                <div class="location-information__item">
                                    <p class="location-information__top"><span class="icon"><i
                                                class="las la-calendar-week"></i></span>
                                        @lang('Event Title') </p>
                                    <h6 class="location-information__title">
                                        <a class="text--base"
                                            href="{{ route('event.details', $event->slug) }}">{{ __($event->title) }}</a>
                                    </h6>
                                </div>
                                <div class="location-information__item">
                                    <p class="location-information__top"><span class="icon"> <i class="las la-clock"></i>
                                        </span>@lang('Event Date and Time') </p>
                                    <h6 class="location-information__title">
                                        {{ showDateTime($event->start_date, 'F j, Y') }}-{{ showDateTime($event->end_date, 'F j, Y') }}
                                    </h6>
                                </div>
                                <div class="location-information__item">
                                    <p class="location-information__top"><span class="icon"> <i
                                                class="las la-map-marker"></i> </span>
                                        @lang('Location') - <span class="text--base">
                                            @if ($event->type == Status::EVENT_OFFLINE)
                                                @lang('Offline Event')
                                            @else
                                                @lang('Online Event')
                                            @endif
                                        </span> </p>
                                    <h6 class="location-information__title"> {{ __($event->location_address) }}
                                    </h6>
                                    @if ($event->link && $event->type == Status::EVENT_OFFLINE)
                                        <div class="map">
                                            <iframe src="{{ $event->link }}" width="600" height="450"
                                                style="border:0;" allowfullscreen="" loading="lazy"
                                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                // Bind click event to close-icon
                $('.close-icon').on('click', function(event) {
                    event.preventDefault();
                    let items = $('.information-box__item');
                    if (items.length <= 1) {
                        notify('error', 'Mininum ticket limit is 1')
                    } else {
                        $(this).closest('.information-box__item').remove();
                        calculateTotal();
                    }

                    updateDeleteButtons()
                });

                function calculateTotal() {
                    var price = $('#ticketPrice').data('price');
                    var ticketPrice = parseFloat(price);

                    var totalTickets = $('.information-box__item').length;
                    var totalPrice = totalTickets * ticketPrice;
                    $('input[name="quantity"]').val(totalTickets);
                    $('#numberOfTickets').text(totalTickets);
                    $('#totalPrice').text('{{ gs('cur_sym') }}' + totalPrice.toFixed(2));
                }

                function updateDeleteButtons() {
                    var items = $('.information-box__item');
                    if (items.length == 1) {
                        $('.close-icon').hide();
                    }

                }
                updateDeleteButtons();

                calculateTotal();
            });
        })(jQuery);
    </script>
@endpush
