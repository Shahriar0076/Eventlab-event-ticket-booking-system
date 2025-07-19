@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="organizer-details">
        <div class="organizer-details__thumb">
            <img src="{{ getImage(getFilePath('organizerCover') . '/' . $organizer->cover_image) }}" alt="image" class="fit-image">
        </div>
        <div class="organizer-wrapper pb-70">
            <div class="container">
                <div class="organizer-profile">
                    <div class="organizer-details__thumb-two">
                        <img src="{{ getImage(getFilePath('organizerProfile') . '/' . @$organizer->profile_image, getFileSize('organizerCover'), $avatar = true) }}"
                            alt="image" class="fit-image">
                    </div>
                    <div class="organizer-details__header">
                        <h2 class="organizer-details__title mb-0"> {{ __($organizer->title) }} </h2>
                        <p class="organizer-details__desc fs-18"> {{ __($organizer->short_description) }} </p>
                        @auth
                            @if (auth()->user()->isFollowing($organizer))
                                <a href="{{ route('user.unfollow.organizer', $organizer->id) }}" class="btn btn--base mb-3">
                                    @lang('Unfollow')
                                </a>
                            @else
                                <a href="{{ route('user.follow.organizer', $organizer->id) }}" class="btn btn--base mb-3">
                                    @lang('Follow')
                                </a>
                            @endif
                        @endauth
                        <ul class="popular-list">
                            <li class="popular-list__item"><span class="icon"><i class="las la-user"></i></span> {{ $organizer->followers->count() }}
                                @lang('followers') </li>
                            <li class="popular-list__item"><span class="icon"><i class="las la-calendar-alt"></i></span> @lang('Joined')
                                {{ showDateTime($organizer->created_at, 'F Y') }} </li>
                            <li class="popular-list__item"><span class="icon"><i class="las la-clipboard-check"></i></span> @lang('Total events')
                                {{ $organizer->events->count() }}
                            </li>
                        </ul>

                    </div>
                </div>
                <div class="organizer-details__info">
                    <h6 class="organizer-details__info-title"> @lang('About Us') </h6>
                    <p class="organizer-details__info-desc">
                        {{ __($organizer->long_description) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @if ($organizer->events->count())
        <div class="organized-section py-70 section-bg">
            <div class="container">
                <div class="row gy-4 justify-content-center">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="section-heading style-left">
                                <span class="section-heading__subtitle"> @lang('MORE EVENTS') </span>
                                <h2 class="section-heading__title mb-md-0"> @lang('More Events from This Organizer') </h2>
                            </div>
                            @if ($organizer->events->count() > 4)
                                <a href="{{ route('event.index', ['organizer' => $organizer->slug]) }}" class="section-button">
                                    @lang('View all') <span class="section-icon"><i class="la la-arrow-right"></i></span>
                                </a>
                            @endif
                        </div>
                    </div>
                    @foreach ($events as $event)
                        <div class="col-xl-3 col-lg-4 col-sm-6">
                            <x-event :event="$event" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <x-share-modal />
    @endif
@endsection
