@php
    $categories = App\Models\Category::active()
        ->whereHas('events', function ($query) {
            $query->approved()->futureEvents()->featured();
        })
        ->orderBy('sort_order')
        ->get();

    $events = App\Models\Event::approved()
        ->futureEvents()
        ->featured()
        ->orderBy('start_date', 'asc')
        ->with('location', 'organizer', 'likes', 'category', 'likes')
        ->take(8)
        ->get();
    $featuredEventsContent = getContent('featured_events.content', true);
@endphp

<div class="feature-section py-70">
    <div class="container">
        <div class="row gy-3 gy-md-4">
            <div class="col-lg-12">
                <div class="style-left">
                    <span class="section-heading__subtitle"> {{ __(@$featuredEventsContent->data_values->title) }}
                    </span>
                    <div class="flex-between">
                        <h3 class="section-heading__title mb-0"> {{ __(@$featuredEventsContent->data_values->heading) }} </h3>
                        <div class="move-btn flex-wrap gap-3">
                            <button type="button" class="tab-align previous" disabled><i class="las la-angle-left"></i></button>
                            <button type="button" class="tab-align next"><i class="las la-angle-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-start flex-wrap tab-slider">
                    <ul class="nav nav-pills custom--tab" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all" type="button"
                                role="tab" aria-controls="pills-all" aria-selected="true">
                                @lang('All')
                            </button>
                        </li>
                        @foreach ($categories as $category)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-{{ $category->id }}-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-{{ $category->id }}" type="button" role="tab"
                                    aria-controls="pills-{{ $category->id }}" aria-selected="false">
                                    {{ __($category->name) }}
                                </button>
                            </li>
                        @endforeach
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="{{ route('event.index') }}">
                                @lang('All Events')
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab" tabindex="0">
                        <div class="row justify-content-center gy-4">
                            @foreach ($events as $event)
                                <div class="col-xl-3 col-lg-4 col-sm-6">
                                    <x-event :event="$event" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @foreach ($categories as $category)
                        <div class="tab-pane fade" id="pills-{{ $category->id }}" role="tabpanel" aria-labelledby="pills-{{ $category->id }}-tab"
                            tabindex="0">
                            <div class="row justify-content-center gy-4">
                                @foreach ($events->where('category_id', $category->id)->take(12) as $event)
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <x-event :event="$event" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@if (!app()->offsetExists('share_modal'))
    <x-share-modal />
    @php app()->offsetSet('share_modal',true) @endphp
@endif



@push('script')
    <script></script>
    <script>
        "use strict";
        (function($) {

            $('.tab-align').on('click', function() {
                let tablist = $(".tab-slider .custom--tab"),
                    x;

                if ($(this).hasClass('next')) {
                    x = ((tablist.width() / 6)) + tablist.scrollLeft();
                    tablist.animate({
                        scrollLeft: x,
                        behavior: 'smooth'
                    })
                } else if ($(this).hasClass('previous')) {
                    x = ((tablist.width() / 6)) - tablist.scrollLeft();
                    tablist.animate({
                        scrollLeft: -x,
                        behavior: 'smooth'
                    })
                }

                setTimeout(() => {
                    if (tablist.scrollLeft() >= tablist[0].scrollWidth - tablist.width()) {
                        $('.next').prop('disabled', true);
                    } else {
                        $('.next').removeAttr("disabled");
                    }
                    
                    if (tablist.scrollLeft() == 0) {
                        $('.previous').prop('disabled', true);
                    }else{
                        $('.previous').removeAttr("disabled");
                    }

                }, 200);

            });

            $(".custom--tab").on("wheel", function(e) {
                e.preventDefault();
                $(this).scrollLeft($(this).scrollLeft() + (e.originalEvent.deltaY > 0 ? 100 : -100));
            });


        })(jQuery);
    </script>
@endpush


