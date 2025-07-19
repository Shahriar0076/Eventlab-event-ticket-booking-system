<div class="popular-item">
    <div class="popular-item__btn">
        <button class="popular-btn shareEventBtn"
            data-image-url="{{ getImage(getFilePath('eventCover') . '/' . $event->cover_image, getFileThumbSize('eventCover')) }}"
            data-title="{{ __($event->title) }}" data-url="{{ route('event.details', $event->slug) }}">
            <i class="fas fa-share-alt"></i>
        </button>
        @auth
            @if (auth()->user()->isLiked($event))
                <button id="heartIcon" data-toggle="tooltip" data-placement="top"
                    title="@if ($event->likes->count() > 0) {{ $event->likes->count() }} @lang('liked') @endif"
                    data-url="{{ route('user.like.event', $event->id) }}" class="popular-btn wishlist wishlist-show"><i
                        class="fas fa-heart"></i></button>
            @else
                <button id="heartIcon" data-toggle="tooltip" data-placement="top"
                    title="@if ($event->likes->count() > 0) {{ $event->likes->count() }} @lang('liked') @endif"
                    data-url="{{ route('user.like.event', $event->id) }}" class="popular-btn wishlist"><i
                        class="far fa-heart"></i></button>
            @endif
        @endauth
    </div>

    <a href="{{ route('event.details', $event->slug) }}" class="popular-item__thumb">
        <img src="{{ getImage(getFilePath('eventCover') . '/thumb_' . $event->cover_image, getFileThumbSize('eventCover')) }}"
            alt="{{ $event->slug . '-image' }}" class="fit-image">
    </a>
    <div class="popular-item__wrapper">
        <div class="popular-item__content">
            <h6 class="popular-item__title">
                <a href="{{ route('event.details', $event->slug) }}">{{ strLimit(__($event->title), 50) }} </a>
            </h6>
            <ul class="popular-list flex-column">
                <li class="popular-list__item">
                    <span class="icon"><i class="la la-calendar-alt"></i></span>
                    {{ showDateTime($event->start_date, 'd M, Y') }} - {{ showDateTime($event->end_date, 'd M, Y') }}
                </li>
                <li class="popular-list__item">
                    <span class="icon"><i class="las la-map-marker-alt"></i></span> {{ __($event->location->name) }}
                </li>
            </ul>
            <div class="event-price-box">
                @if ($event->price == 0)
                    <h6 class="price text--base">@lang('Free')</h6>
                @else
                    <h6 class="price"> {{ showAmount($event->price) }} </h6>
                @endif
                @if ($event->isExpired())
                    <small>(@lang('Expired'))</small>
                @endif
            </div>
        </div>
        <div class="popular-item__left">
            <div class="d-flex align-items-center">
                <span class="info-left__icon">
                    <img src="{{ getImage(getFilePath('organizerProfile') . '/' . @$event->organizer->profile_image, getFileSize('organizerProfile'), $avatar = true) }}"
                        alt="organizer-profile-image">
                </span>
                <div class="info-left__content">
                    <span class="title">@lang('Organized By')</span>
                    <p class="name">
                        <a href="{{ route('organizer.details', $event->organizer->slug) }}"
                            class="text--base">{{ @$event->organizer->organization_name }}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
