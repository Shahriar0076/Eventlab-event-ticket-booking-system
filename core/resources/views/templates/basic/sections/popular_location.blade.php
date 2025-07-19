@php
    $locations = App\Models\Location::withCount([
        'events' => function ($query) {
            $query->approved();
        },
    ])
        ->active()
        ->featured()
        ->orderBy('sort_order')
        ->get();

    $popularLocationContent = getContent('popular_location.content', true);
@endphp

<div class="location-section py-70 bg-white">
    <div class="container">
        <div class="row gy-3 gy-md-4">
            <div class="col-lg-12">
                <div class="text-start style-left">
                    <span class="section-heading__subtitle">{{ __(@$popularLocationContent->data_values->title) }}
                    </span>
                    <h3 class="section-heading__title"> {{ __(@$popularLocationContent->data_values->heading) }} </h3>
                </div>
            </div>
            <div class="col-12">
                <div class="location-slider">
                    @foreach ($locations as $location)
                        <div class="location-item">
                            <a href="{{ route('event.index', ['location' => $location->slug]) }}" class="location-item__thumb">
                                <img src="{{ getImage(getFilePath('location') . '/' . @$location->image, getFileSize('location')) }}" alt="image" class="fit-image">
                            </a>
                            <div class="location-item__content">
                                <h3 class="location-item__title">
                                    <a href="{{ route('event.index', ['location' => $location->slug]) }}">
                                        {{ __(@$location->name) }}
                                    </a>
                                </h3>
                                <span class="location-item__text"> {{ $location->events_count }} @lang('Events') </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
    </div>
</div>

@if (!app()->offsetExists('slick_script'))
    @push('style-lib')
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    @endpush

    @push('script-lib')
        <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    @endpush
    @php app()->offsetSet('slick_script',true) @endphp
@endif

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.location-slider').slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 1000,
                pauseOnHover: true,
                speed: 2000,
                dots: false,
                arrows: true,
                prevArrow: '<button type="button" class="slick-prev"><i class="las la-angle-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="las la-angle-right"></i></button>',
                responsive: [{
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: 3,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 3,
                            dots: false,
                            arrows: true,
                        }
                    },
                    {
                        breakpoint: 400,
                        settings: {
                            slidesToShow: 2,
                            dots: false,
                            arrows: true,
                        }
                    }
                ]
            });
        })(jQuery);
    </script>
@endpush
