@php
    $categories = App\Models\Category::active()->orderBy('sort_order')->get();
@endphp
<div class="category-section py-70">
    <div class="container">
        <div class="row justify-content-center">
            <div class="category-silder">
                @foreach ($categories as $category)
                    <a href="{{ route('event.index', ['category' => $category->slug]) }}" class="category-item">
                        <div class="category-item__thumb">
                            <img src="{{ getImage(getFilePath('category') . '/' . @$category->image, getFileSize('category')) }}" alt="image">
                        </div>
                        <p class="category-item__title">
                            {{ __($category->name) }}
                        </p>
                    </a>
                @endforeach
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
            $('.category-silder').slick({
                slidesToShow: 7,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                speed: 1500,
                dots: false,
                pauseOnHover: true,
                arrows: true,
                prevArrow: '<button type="button" class="slick-prev"><i class="las la-angle-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="las la-angle-right"></i></button>',
                responsive: [
                    {
                        breakpoint: 1400,
                        settings: {
                            arrows: true,
                            slidesToShow: 6,
                            dots: false,
                        }
                    },
                    {
                        breakpoint: 1199,
                        settings: {
                            arrows: true,
                            slidesToShow: 5,
                            dots: false,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            arrows: true,
                            slidesToShow: 4
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            arrows: true,
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            arrows: true,
                            slidesToShow: 3
                        }
                    },
                    {
                        breakpoint: 400,
                        settings: {
                            arrows: true,
                            slidesToShow: 2
                        }
                    }
                ]
            });

        })(jQuery);
    </script>
@endpush
