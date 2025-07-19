@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="grid-event-grid py-70">
        <div class="container">
            <form action="{{ route('event.index') }}" method="get" id="sortForm">
                <input type="hidden" name="category" value="{{ request()->category }}">
                <input type="hidden" name="sort" value="{{ request()->sort }}">
                <input type="hidden" name="page" value="{{ request()->page }}">
                <input type="hidden" name="organizer" value="{{ request()->organizer }}">
                <input type="hidden" name="location" value="{{ request()->location }}">
                <input type="hidden" name="search" value="{{ request()->search }}">
            </form>
            <div class="row gy-4">
                <div class="col-xl-3">
                    <div class="grid-sidebar">
                        <form action="{{ route('event.index') }}" class="search-box-wrapper mb-4">
                            <div class="search-box">
                                <input type="text" class="form--control" name="search"
                                    placeholder="@lang('Search any keyword')..." value="{{ request()->search }}">
                                <button type="submit" class="search-box__button"><i class="las la-search"></i></button>
                            </div>
                        </form>

                        <span class="sidebar-menu__close d-lg-none d-block"><i class="fas fa-times"></i></span>
                        <h6 class="grid-sidebar__title">@lang('Category')</h6>
                        <div class="category">
                            <div class="form-group">
                                <div class="form-check form--radio">
                                    <input class="form-check-input category-radio" type="radio" name="category"
                                        id="all" value="" checked>
                                    <label class="form-check-label" for="all">
                                        @lang('All')
                                    </label>
                                </div>
                            </div>

                            @foreach ($categories as $category)
                                <div class="form-group @if ($loop->last) mb-0 @endif">
                                    <div class="form-check form--radio">
                                        <input class="form-check-input category-radio" type="radio" name="category"
                                            id="{{ $category->slug }}" value="{{ $category->slug }}"
                                            @checked($category->slug == @$categories->where('slug', request()->category)->first()->slug)>
                                        <label class="form-check-label" for="{{ $category->slug }}">
                                            {{ __($category->name) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
                <div class="col-xl-9">
                    <span class="event-filter d-xl-none d-block">
                        <button class="event-filter__button">
                            <i class="las la-filter"></i>
                            <span class="text"> @lang('Filter') </span>
                        </button>
                    </span>
                    <div class="grid-event-header">

                        @if (count(request()->query()))
                            <span class="grid-event-header__title">
                                @lang('Result'): {{ $eventsCount }} {{ Str::plural('item', $eventsCount) }}
                                @lang('Found')
                            </span>
                        @endif

                        <div class="flex-between grid-event-header-content ms-auto gap-2">
                            <div class="grid-event-forn-inner">
                                <form action="#">
                                    <div class="flex-between">
                                    </div>
                                    <select class="form-select form--control form-two sort-select" aria-label="sort select">
                                        <option value="newestToOldest" @selected('newestToOldest' == request()->sort)>@lang('Newest to oldest')
                                        </option>
                                        <option value="oldestToNewest" @selected('oldestToNewest' == request()->sort)>@lang('Oldest to newest')
                                        </option>
                                        <option value="priceLowToHigh" @selected('priceLowToHigh' == request()->sort)>
                                            @lang('Price')(@lang('Low') - @lang('High'))
                                        </option>
                                        <option value="priceHighToLow" @selected('priceHighToLow' == request()->sort)>
                                            @lang('Price')(@lang('High') -
                                            @lang('Low'))</option>
                                        <option value="categories" @selected('categories' == request()->sort)>@lang('Categories')</option>

                                        @if (!request()->location_id)
                                            <option value="location" @selected('location' == request()->sort)>@lang('Location')
                                            </option>
                                        @endif

                                        @if (!request()->organizer_id)
                                            <option value="organizer" @selected('organizer' == request()->sort)>@lang('Organizer')
                                            </option>
                                        @endif
                                    </select>
                                </form>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="grid-event-header__tab">
                                    <ul class="nav gap-2 nav-pills m-0 custom--tab tab-four" id="pills-tab" role="tablist">
                                        <li class="nav-item p-0" role="presentation">
                                            <button class="nav-link active" id="pills-h-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-h" type="button" role="tab"
                                                aria-controls="pills-h" aria-selected="true">
                                                <i class="la la-th-large"></i>
                                            </button>
                                        </li>
                                        <li class="nav-item p-0" role="presentation">
                                            <button class="nav-link" id="pills-p-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-p" type="button" role="tab"
                                                aria-controls="pills-p" aria-selected="false">
                                                <i class="las la-list"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content " id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-h" role="tabpanel"
                            aria-labelledby="pills-h-tab" tabindex="0">
                            <div class="row gy-4">
                                @foreach ($events as $event)
                                    <div class="col-xl-4 col-sm-6">
                                        <x-event :event="$event" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-p" role="tabpanel" aria-labelledby="pills-p-tab"
                            tabindex="0">
                            <div class="row gy-4">
                                @foreach ($events as $event)
                                    <div class="col-lg-12">
                                        <div class="popular-item item-two">
                                            <a href="{{ route('event.details', $event->slug) }}"
                                                class="popular-item__thumb">
                                                <img src="{{ getImage(getFilePath('eventCover') . '/' . $event->cover_image, getFileSize('eventCover')) }}"
                                                    alt="image" class="fit-image">
                                            </a>
                                            <div class="popular-item__wrapper">
                                                <div class="popular-item__content">
                                                    <div class="content-header d-flex justify-content-between flex-wrap">
                                                        <h6 class="popular-item__title">
                                                            <a href="{{ route('event.details', $event->slug) }}">{{ __($event->title) }}
                                                            </a>
                                                        </h6>
                                                        <div class="popular-item__btn">
                                                            <button class="popular-btn shareEventBtn"
                                                                data-bs-toggle="modal" data-bs-target="#shareModal"
                                                                data-url="{{ route('event.details', $event->slug) }}"><i
                                                                    class="fas fa-share-alt"></i></button>
                                                            @auth
                                                                @if (auth()->user()->isLiked($event))
                                                                    <button id="heartIcon" data-toggle="tooltip"
                                                                        data-placement="top"
                                                                        title="@if ($event->likes->count() > 0) {{ $event->likes->count() }} @lang('liked') @endif"
                                                                        data-url="{{ route('user.like.event', $event->id) }}"
                                                                        class="popular-btn wishlist wishlist-show"><i
                                                                            class="fas fa-heart"></i></button>
                                                                @else
                                                                    <button id="heartIcon" data-toggle="tooltip"
                                                                        data-placement="top"
                                                                        title="@if ($event->likes->count() > 0) {{ $event->likes->count() }} @lang('liked') @endif"
                                                                        data-url="{{ route('user.like.event', $event->id) }}"
                                                                        class="popular-btn wishlist"><i
                                                                            class="far fa-heart"></i></button>
                                                                @endif
                                                            @endauth
                                                        </div>
                                                    </div>
                                                    <ul class="popular-list">
                                                        <li class="popular-list__item"><span class="icon"><i
                                                                    class="las la-calendar-alt"></i></span>
                                                            {{ showDateTime($event->start_date, 'd M Y') }} -
                                                            {{ showDateTime($event->end_date, 'd M Y') }}</li>
                                                        <li class="popular-list__item"><span class="icon"><i
                                                                    class="las la-map-marker-alt"></i></span>
                                                            {{ __($event->location->name) }}</li>
                                                    </ul>
                                                    <p class="popular-item__desc">
                                                        {{ __($event->short_description) }}
                                                    </p>
                                                </div>
                                                <div class="popular-item__left">
                                                    <div class="d-flex align-items-center">
                                                        <span class="info-left__icon">
                                                            <img src="{{ getImage(getFilePath('organizerProfile') . '/' . @$event->organizer->profile_image, getFileSize('organizerProfile')) }}"
                                                                alt="organizer-profile-image">
                                                        </span>
                                                        <div class="info-left__content">
                                                            <span class="title"> @lang('Organized By') </span>
                                                            <p class="name"> <a
                                                                    href="{{ route('organizer.details', $event->organizer->slug) }}"
                                                                    class="text--base">
                                                                    {{ @$event->organizer->organization_name }} </a> </p>
                                                        </div>
                                                    </div>
                                                    @if ($event->price == 0)
                                                        <h6 class="price text--base">@lang('Free')</h6>
                                                    @else
                                                        <h6 class="price"> @lang('Price'):
                                                            {{ showAmount($event->price) }}
                                                        </h6>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="event-pagination">
                        {{ paginateLinks($events) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-share-modal />
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('.category-radio').on('change', function() {
                    $('#sortForm').find('input[name="category"]').val(this.value);
                    $('#sortForm').find('input[name="page"]').val(null)
                    $('#sortForm').submit();
                });

                $('.sort-select').on('change', function() {
                    $('#sortForm').find('input[name="sort"]').val(this.value);
                    $('#sortForm').find('input[name="page"]').val(null)
                    $('#sortForm').submit();
                });

                $('.pagination a').each(function() {
                    $(this).removeAttr('href').attr('href', 'javascript:void(0)');
                });

                $('.pagination a').on('click', function() {
                    var page = this.innerText;
                    var currentActivePage = parseInt($('.page-item.active .page-link').text());
                    if (page == '‹') {
                        page = parseInt(currentActivePage - 1);
                    }
                    if (page == '›') {
                        page = parseInt(currentActivePage + 1);
                    }
                    $('#sortForm').find('input[name="page"]').val(page);
                    $('#sortForm').submit();
                });

                //set selected tab
                var tabSelected = localStorage.getItem('pillsTab');

                $('#pills-h-tab , #pills-p-tab').on('click', function() {
                    localStorage.setItem('pillsTab', $(this).attr('id'));
                });

                if (tabSelected) {
                    $('#' + tabSelected).click();
                }
            });
        })(jQuery);
    </script>
@endpush
