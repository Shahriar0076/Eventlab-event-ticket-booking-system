@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="event-details-banner">
        <div class="container">
            <div class="event-banner">
                <img src="{{ getImage(getFilePath('eventCover') . '/' . @$event->cover_image, getFileSize('eventCover')) }}"
                    alt="event_cover" class="fit-image rounded-3">
            </div>
        </div>
    </div>
    <div class="event-details-wrapper">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <div class="col-xl-8">
                    <div class="event-details-wrapper__body">
                        <div class="event-details-wrapper__tab">
                            <ul class="nav nav-pills custom--tab tab-two" id="pills-tabtwo" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-details-tab" data-bs-toggle="tab"
                                        data-bs-target="#pills-details" type="button" role="tab"
                                        aria-controls="pills-details" aria-selected="true"> @lang('Details')
                                    </button>
                                </li>
                                @if ($event->galleryImages->count())
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-gallery-tab" data-bs-toggle="tab"
                                            data-bs-target="#pills-gallery" type="button" role="tab"
                                            aria-controls="pills-gallery" aria-selected="false"> @lang('Gallery')</button>
                                    </li>
                                @endif
                                @if ($event->speakers->count())
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-speaker-tab" data-bs-toggle="tab"
                                            data-bs-target="#pills-speaker" type="button" role="tab"
                                            aria-controls="pills-speaker" aria-selected="false">
                                            @lang('Speakers')
                                        </button>
                                    </li>
                                @endif
                            </ul>
                            <div class="tab-content" id="pills-tabContenttwo">
                                <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                                    aria-labelledby="pills-details-tab" tabindex="0">
                                    <div class="details">
                                        <div class="details__header d-flex justify-content-between mb--4 ">
                                            <div class="w-100">
                                                <div class="d-flex flex-wrap justify-content-between">
                                                    <h4 class="details__header-title"> {{ __($event->title) }} </h4>
                                                    <span
                                                        class="details__header-date">{{ showDateTime($event->start_date, 'F j, Y') }}</span>
                                                </div>
                                                <p class="details__header-desc"> {{ __($event->short_description) }}</p>
                                            </div>
                                            <div class="popular-item__btn">
                                                <button class="popular-btn shareEventBtn" data-bs-toggle="modal"
                                                    data-bs-target="#shareModal"
                                                    data-url="{{ route('event.details', $event->slug) }}"><i
                                                        class="fas fa-share-alt"></i></button>
                                                @auth
                                                    @if (auth()->user()->isLiked($event))
                                                        <button id="heartIcon" data-toggle="tooltip" data-placement="top"
                                                            title="@if ($event->likes->count() > 0) {{ $event->likes->count() }} @lang('liked') @endif"
                                                            data-url="{{ route('user.like.event', $event->id) }}"
                                                            class="popular-btn wishlist wishlist-show"><i
                                                                class="fas fa-heart"></i>
                                                        </button>
                                                    @else
                                                        <button id="heartIcon" data-toggle="tooltip" data-placement="top"
                                                            title="@if ($event->likes->count() > 0) {{ $event->likes->count() }} @lang('liked') @endif"
                                                            data-url="{{ route('user.like.event', $event->id) }}"
                                                            class="popular-btn wishlist">
                                                            <i class="far fa-heart"></i>
                                                        </button>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mb--4 gap-3 flex-wrap">
                                            <div class="details__date">
                                                <h6 class="details__date-title"> @lang('Event Date & Time') </h6>
                                                <p class="details__date-desc"><span class="icon"><i
                                                            class="far fa-calendar"></i></span>
                                                    {{ showDateTime($event->start_date, 'F j, Y') }}-{{ showDateTime($event->end_date, 'F j, Y') }}
                                                    {{ showDateTime($event->start_time, 'h:i A') }}
                                                </p>
                                            </div>
                                            <div class="details__location">
                                                <h6 class="details__location-title"> @lang('Location') - <span
                                                        class="text--base">
                                                        @if ($event->type == Status::EVENT_OFFLINE)
                                                            @lang('Offline Event')
                                                        @else
                                                            @lang('Online Event')
                                                        @endif
                                                    </span></h6>
                                                <p class="details__location-desc"><span class="icon"><i
                                                            class="fas fa-map-marker-alt"></i></span>
                                                    @if (@$event->location_address)
                                                        {{ __($event->location_address) }},
                                                    @endif {{ __($event->location->name) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="details__info mb--4">
                                            <h6 class="details__info-title">@lang('Event Information') </h6>
                                            <p class="details__info-desc"> @php echo $event->description @endphp </p>
                                        </div>
                                        @if ($event->link && $event->type == Status::EVENT_OFFLINE)
                                            <div class="details__map">
                                                <button class="details__map-title">
                                                    <span class="map-btn-title"> @lang('Hide Map') </span>
                                                    <span class="icon"><i class="las la-angle-down"></i></span>
                                                </button>
                                                <div class="map">
                                                    <iframe src="{{ $event->link }}" width="600" height="450"
                                                        style="border:0;" allowfullscreen="" loading="lazy"
                                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    @if ($event->timeSlots->count())
                                        <div class="date-time">
                                            <div class="">
                                                <ul class="nav nav-pills custom--tab tab-three date-time__wrapper"
                                                    id="pills-tab" role="tablist">
                                                    @foreach (@$event->timeSlots ?? [] as $timeSlot)
                                                        <li class="nav-item" role="presentation">
                                                            <button
                                                                class="nav-link time-slot-area @if ($loop->first) active @endif"
                                                                id="time-slot-{{ $timeSlot->id }}-tab"
                                                                data-bs-toggle="tab"
                                                                data-bs-target="#time-slot-{{ $timeSlot->id }}"
                                                                type="button" role="tab"
                                                                aria-controls="time-slot-{{ $timeSlot->id }}">
                                                                <span class="title">
                                                                    {{ date('d M y', strtotime($timeSlot->date)) }}</span>
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="tab-content" id="pills-tabContent">
                                                @foreach (@$event->timeSlots ?? [] as $timeSlot)
                                                    <div class="tab-pane fade  @if ($loop->first) show active @endif"
                                                        id="time-slot-{{ $timeSlot->id }}" role="tabpanel"
                                                        aria-labelledby="time-slot-{{ $timeSlot->id }}-tab"
                                                        tabindex="0">
                                                        @foreach (@$timeSlot->slots ?? [] as $slot)
                                                            <div class="date-time__item">
                                                                <p class="date-time__time">{{ $slot->start_time }} -
                                                                    {{ $slot->end_time }}</p>
                                                                <div class="date-time__content">
                                                                    <h6 class="date-time__title"> {{ __($slot->title) }}
                                                                    </h6>
                                                                    <p class="date-time__desc">
                                                                        {{ __($slot->description) }} </p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if ($event->galleryImages->count())
                                    <div class="tab-pane fade" id="pills-gallery" role="tabpanel"
                                        aria-labelledby="pills-gallery-tab" tabindex="0">
                                        <div class="gallery">
                                            <div class="row justify-content-center gy-4">
                                                @forelse ($event->galleryImages as $image)
                                                    <div class="col-md-4 col-6">
                                                        <a href="{{ getImage(getFilePath('eventGallery') . '/' . $image->image, getFileSize('eventGallery')) }}"
                                                            class="gallery-thumb">
                                                            <img src="{{ getImage(getFilePath('eventGallery') . '/' . $image->image, getFileSize('eventGallery')) }}"
                                                                alt="image">
                                                            <span class="gallery-icon"> <i class="las la-plus"></i>
                                                            </span>
                                                        </a>
                                                    </div>
                                                @empty
                                                    <p>@lang('No images found')</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($event->speakers->count())
                                    <div class="tab-pane fade" id="pills-speaker" role="tabpanel"
                                        aria-labelledby="pills-speaker-tab" tabindex="0">
                                        <div class="speaker">
                                            <div class="row gy-4">
                                                @if ($event->speakers)
                                                    @forelse (@$event->speakers as $speaker)
                                                        <div class="col-md-4 col-sm-6">
                                                            <div class="speaker-item">
                                                                <a href="javascript:void(0);" class="speaker-item__thumb">
                                                                    <img src="{{ getImage(getFilePath('eventSpeaker') . '/' . $speaker->image, getFileSize('eventSpeaker'), $avatar = true) }}"
                                                                        alt="image">
                                                                </a>
                                                                <div class="speaker-item__content">
                                                                    <h6 class="speaker-item__name text--base">
                                                                        {{ __($speaker->name) }}
                                                                    </h6>
                                                                    <p class="speaker-item__title">
                                                                        {{ __($speaker->designation) }} </p>
                                                                    <ul class="social-list social-list-two">

                                                                        @if ($speaker->social['facebook_url'])
                                                                            <li class="social-list__item"><a
                                                                                    href="{{ $speaker->social['facebook_url'] }}"
                                                                                    class="social-list__link flex-center"
                                                                                    target="_blank"> <i
                                                                                        class="lab la-facebook"></i></a>
                                                                            </li>
                                                                        @endif

                                                                        @if ($speaker->social['youtube_url'])
                                                                            <li class="social-list__item"><a
                                                                                    href="{{ $speaker->social['youtube_url'] }}"
                                                                                    class="social-list__link flex-center"
                                                                                    target="_blank"> <i
                                                                                        class="la la-youtube"></i></a></li>
                                                                        @endif

                                                                        @if ($speaker->social['instagram_url'])
                                                                            <li class="social-list__item"><a
                                                                                    href="{{ $speaker->social['instagram_url'] }}"
                                                                                    class="social-list__link flex-center"
                                                                                    target="_blank"> <i
                                                                                        class="lab la-instagram"></i></a>
                                                                            </li>
                                                                        @endif

                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p>@lang('No Speakers added')</p>
                                                    @endforelse
                                                @else
                                                    <p>@lang('No Speakers added')</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="event-sidebar">
                        @php
                            $canBuy =
                                $event->seatsAvailable() > 0 &&
                                Carbon\Carbon::parse($event->start_date) > Carbon\Carbon::now();
                        @endphp
                        @if ($canBuy)
                            @auth
                                <a class="btn btn--base w-100 reserve-spot-btn"
                                    href="{{ route('user.event.buy.ticket', $event->id) }}?qty=1">
                                    @lang('Reserve a spot')
                                </a>
                            @else
                                <button class="btn btn--base w-100 reserve-spot-btn showLoginModal">
                                    @lang('Reserve a spot')
                                </button>
                            @endauth
                        @endif
                        <div class="event-sidebar__ticket sidebar-style  @if (!$canBuy) mt-0 @endif">
                            <h6 class="event-sidebar__ticket-title">
                                <span class="icon"><i class="la la-ticket"></i></span> @lang('Ticket') <span
                                    class="remaining">({{ $event->seatsAvailable() }}
                                    @lang('seats remaining')) </span>
                            </h6>
                            <div class="event-sidebar__qty">
                                <div class="ticket-price">
                                    <span class="ticket-price__title">@lang('Ticket Price') </span>
                                    @if ($event->price == 0)
                                        <h6 class="price text--base"> @lang('Free') </h6>
                                    @else
                                        <h6 class="price"> {{ showAmount($event->price) }} </h6>
                                    @endif
                                </div>
                                @if ($canBuy)
                                    <form action="#">
                                        <p class="qty">
                                            <button class="qtyminus" aria-hidden="true">&minus;</button>
                                            <input type="number" name="qty" id="qty" min="1"
                                                max="{{ $event->seatsAvailable() }}" step="1" value="1">
                                            <button class="qtyplus" aria-hidden="true">&plus;</button>
                                        </p>
                                    </form>
                                    @if ($event->price > 0)
                                        <div>
                                            <span class="ticket-price__title">@lang('Total')</span>
                                            <h6 class="price"> <span
                                                    id="total">{{ showAmount($event->price) }}</span>
                                            </h6>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="event-sidebar__countdown sidebar-style">
                            <h6 class="event-sidebar__countdown-title">
                                <span class="icon"><i class="las la-clock"></i></span> @lang('Event Starts in')
                            </h6>
                            <div class="remaining-time">
                                <div class="remaining-time__content">
                                    <p class="box"><span class="box__days box-style"></span> <span
                                            class="box__text">@lang('Days')</span></p>
                                    <p class="box"><span class="remaining-time__hrs box-style"></span> <span
                                            class="box__text">@lang('Hours')</span></p>
                                    <p class="box"><span class="remaining-time__min box-style"></span> <span
                                            class="box__text">@lang('Min')</span></p>
                                    <p class="box"><span class="remaining-time__sec box-style"></span> <span
                                            class="box__text">@lang('Sec')</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="event-organizer sidebar-style">
                            <h6 class="event-organizer__title">
                                <span class="icon"><i class="las la-users"></i></span> @lang('Event Organizer')
                            </h6>
                            <div class="event-organizer__item">
                                <a href="{{ route('organizer.details', $event->organizer->slug) }}"
                                    class="event-organizer__thumb">
                                    <img src="{{ getImage(getFilePath('organizerProfile') . '/' . $event->organizer->profile_image, getFileSize('organizerProfile'), $avatar = true) }}"
                                        alt="image" class="fit-image">
                                </a>
                                <div class="event-organizer__content">
                                    <h6 class="organizer-item__title">
                                        <a href="{{ route('organizer.details', $event->organizer->slug) }}">
                                            {{ @$event->organizer->organization_name }}
                                        </a>
                                    </h6>

                                    <p class="organizer-follower"> {{ $event->organizer->followers->count() }}
                                        @lang('followers')</p>

                                    @if (!auth()->guard('organizer')->check())

                                        @if (auth()->user() &&
                                                auth()->user()->isFollowing($event->organizer))
                                            <a href="{{ route('user.unfollow.organizer', $event->organizer->id) }}"
                                                class="organizer-button">
                                                @lang('Unfollow')
                                            </a>
                                        @else
                                            <a href="{{ route('user.follow.organizer', $event->organizer->id) }}"
                                                class="organizer-button">
                                                @lang('Follow')
                                            </a>
                                        @endif
                                    @endauth
                            </div>
                        </div>
                    </div>
                    <div class="event-sidebar__countdown sidebar-style">
                        <h6 class="event-sidebar__countdown-title">
                            <span class="icon"><i class="las la-share"></i></span> @lang('Share Event')
                        </h6>
                        <ul class="social__links mt-3">
                            <li>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                    target="_blank"><i class="fab fa-facebook-f"></i></a>
                            </li>
                            <li><a href="https://twitter.com/intent/tweet?text={{ __(@$event->title) }}&amp;url={{ urlencode(url()->current()) }}"
                                    target="_blank"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="https://pinterest.com/pin/create/bookmarklet/?media={{ getImage(getFilePath('eventCover') . '/' . @$event->cover_image, getFileSize('eventCover')) }}&url={{ urlencode(url()->current()) }}"
                                    target="_blank"><i class="fab fa-pinterest-p"></i></a></li>
                            <li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}"
                                    target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="existModalLongTitle">@lang('Login')</h5>
                <span class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </span>
            </div>
            <div class="modal-body">
                <p class="text-center">@lang('Please login to purchase tickets!')</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark btn--sm"
                    data-bs-dismiss="modal">@lang('Close')</button>
                <a href="{{ route('user.login') }}" class="btn btn--base btn--sm">@lang('Login')</a>
            </div>
        </div>
    </div>
</div>

<x-share-modal />
@endsection

@if (!app()->offsetExists('slick_script'))
@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
@endpush
@php app()->offsetSet('slick_script',true) @endphp
@endif

@push('style-lib')
<link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/magnific-popup.css') }}">
@endpush

@push('script-lib')
<script src="{{ asset($activeTemplateTrue . 'js/magnific-popup.js') }}"></script>
@endpush

@push('style')
<style>
    .slick-list.draggable {
        width: 100% !important;
    }

    .mb--4 {
        margin-bottom: 2rem !important;
    }

    @media screen and (max-width: 575px) {
        .mb--4 {
            margin-bottom: 1.5rem !important;
        }
    }

    #loginModal .btn {
        padding: 5px 10px !important;
    }
</style>
@endpush

@push('script')
<script>
    (function($) {
            "use strict";

            $(document).ready(function() {

                    @guest
                    $(document).on('click', '.showLoginModal', function() {
                        var modal = $('#loginModal');
                        modal.modal('show');
                    });
                @endguest

                function setCowndownProgress(className, gradientAngle) {
                    var gradient = "conic-gradient(hsl(var(--base)) " + gradientAngle +
                        "deg, hsl(var(--black) / .1) 0deg)";
                    $(className).css("background", gradient);
                }
                // Set the date to countdown to
                var countDownDate = new Date("{{ $event->start_date }}").getTime();

                // Update the countdown every second
                var countdownInterval = setInterval(function() {
                    // Get the current date and time
                    var now = new Date().getTime();

                    // Calculate the remaining time
                    var distance = countDownDate - now;

                    // Calculate days, hours, minutes, and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Update the HTML content of the countdown
                    $(".box__days").text(days);
                    $(".remaining-time__hrs").text(hours);
                    $(".remaining-time__min").text(minutes);
                    $(".remaining-time__sec").text(seconds);

                    var createdDate = new Date("{{ $event->created_at }}");
                    var startDate = new Date("{{ $event->start_date }}");
                    // Calculate the difference in milliseconds
                    var timeDifference = startDate.getTime() - createdDate.getTime();
                    // Convert the time difference to days
                    var daysDifference = Math.floor(timeDifference / (1000 * 60 * 60 * 24));

                    setCowndownProgress(".box__days", (360 / daysDifference) * days);
                    setCowndownProgress(".remaining-time__hrs", (360 / 24) * hours);
                    setCowndownProgress(".remaining-time__min", (360 / 60) * minutes);
                    setCowndownProgress(".remaining-time__sec", (360 / 60) * seconds);

                    // If the countdown is over, clear the interval
                    if (distance < 0) {
                        clearInterval(countdownInterval);
                        $(".remaining-time__content").html("<p class='expired'>EXPIRED</p>");
                    }
                }, 1000);



                // Function to update total and link
                function updateTotalAndLink() {
                    var currentVal = parseInt($('input[name=qty]').val());
                    var totalPrice = parseFloat('{{ getAmount($event->price) }}'); // Ticket price
                    if (!isNaN(currentVal)) {
                        $('#total').text('{{ gs('cur_sym') }}' + (currentVal * totalPrice).toFixed(
                            2) + ' {{ gs('cur_text') }}'); // Update total
                        $('.reserve-spot-btn').attr('href',
                            "{{ route('user.event.buy.ticket', $event->id) }}?qty=" +
                            currentVal); // Update link
                    }
                }

                // Click event for plus button
                $('.qtyplus').on('click', function(e) {
                    e.preventDefault();
                    var currentVal = parseInt($('input[name=qty]').val());
                    var maxSeats = parseInt('{{ $event->seatsAvailable() }}');
                    if (!isNaN(currentVal) && currentVal < maxSeats) {
                        $('input[name=qty]').val(currentVal + 1);
                        updateTotalAndLink();
                    }
                });

                // Click event for minus button
                $(".qtyminus").on('click', function(e) {
                    e.preventDefault();
                    var currentVal = parseInt($('input[name=qty]').val());
                    if (!isNaN(currentVal) && currentVal > 1) {
                        $('input[name=qty]').val(currentVal - 1);
                        updateTotalAndLink();
                    }
                });

                // Change event for input field
                $('#qty').on('change', function() {
                    var maxSeats = parseInt('{{ $event->seatsAvailable() }}');
                    var val = $('input[name=qty]').val();
                    if (val > maxSeats) {
                        $('input[name=qty]').val(maxSeats)
                    }
                    if (val < 0) {
                        $('input[name=qty]').val(1)
                    }
                    updateTotalAndLink();
                });


                @if ($event->link && $event->type == Status::EVENT_OFFLINE)
                    //hide map
                    $(".details__map-title").on('click', function() {
                        // Toggle the icon class
                        $(".details__map-title .icon i").toggleClass(
                            "las la-angle-up las la-angle-down");

                        // Toggle the button text
                        var buttonText = $(".map").is(":visible") ? "@lang('Hide Map')" :
                            "@lang('Show Map')";
                        $(".details__map-title .map-btn-title").text(buttonText);
                    });
                @endif


                // =======Gallery magnific Popup Icon Js Start ===
                $('.gallery-thumb').magnificPopup({
                    type: 'image',
                    gallery: {
                        enabled: true
                    }
                });



                // ======= Gallery magnific Popup Icon Js End ===
                $('.date-time__wrapper').slick({
                    autoplay: true,
                    slidesToShow: {{ count(@$event->timeSlots ?? []) < 4 ? count(@$event->timeSlots ?? []) : 4 }},
                    slidesToScroll: 1,
                    autoplaySpeed: 2000,
                    speed: 1500,
                    dots: true,
                    pauseOnHover: true,
                    arrows: false,
                    prevArrow: '<button type="button" class="slick-prev"><i class="las la-angle-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="las la-angle-right"></i></button>',
                    responsive: [{
                            breakpoint: 991,
                            settings: {
                                arrows: false,
                                slidesToShow: {{ count(@$event->timeSlots ?? []) < 3 ? count(@$event->timeSlots ?? []) : 3 }}
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                arrows: false,
                                slidesToShow: {{ count(@$event->timeSlots ?? []) < 2 ? count(@$event->timeSlots ?? []) : 2 }}
                            }
                        }
                    ]
                });

                $('.time-slot-area').on('click', function() {
                    $('.time-slot-area').removeClass('active');
                    $(this).addClass('active');
                })
            });
    })(jQuery);
</script>
@endpush
