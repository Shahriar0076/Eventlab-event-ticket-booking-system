@php
    $testimonialContent = getContent('testimonials.content', true);
    $testimonials = getContent('testimonials.element', orderById: true);
@endphp

<section class="testimonials py-70 bg-white">
    <div class="container">
        <div class="row gy-3 gy-md-4">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-end">
                    <div class="style-left">
                        <span class="section-heading__subtitle"> {{ __(@$testimonialContent->data_values->title) }}
                        </span>
                        <h3 class="section-heading__title"> {{ __(@$testimonialContent->data_values->heading) }} </h3>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="testimonial-slider">
                    @foreach ($testimonials as $testimonial)
                        <div class="testimonails-card">
                            <div class="testimonial-item">
                                <div class="testimonial-item__info">
                                    <div class="testimonial-item__thumb">
                                        <img src="{{ frontendImage('testimonials', @$testimonial->data_values->image, '60x60') }}"
                                            class="fit-image" alt="image">
                                    </div>
                                    <div class="testimonial-item__details">
                                        <h5 class="testimonial-item__name"> {{ __(@$testimonial->data_values->name) }}
                                        </h5>
                                        <span class="testimonial-item__designation">
                                            {{ __(@$testimonial->data_values->designation) }}</span>
                                    </div>
                                </div>
                                <div class="testimonial-item__content">
                                    <span class="testimonial-item__icon">
                                        <i class="fas fa-quote-right"></i>
                                    </span>
                                    <div class="testimonial-item__rating">
                                        <ul class="rating-list">
                                            @for ($i = 0; $i < @$testimonial->data_values->rating; $i++)
                                                <li class="rating-list__item"><i class="fas fa-star"></i></li>
                                            @endfor
                                        </ul>
                                    </div>
                                    <p class="testimonial-item__desc">{{ __(@$testimonial->data_values->description) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

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

            $('.testimonial-slider').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                speed: 1500,
                dots: false,
                pauseOnHover: true,
                arrows: true,
                prevArrow: '<button type="button" class="slick-prev"><i class="las la-angle-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="las la-angle-right"></i></button>',
                responsive: [{
                        breakpoint: 1199,
                        settings: {
                            arrows: false,
                            slidesToShow: 2,
                            dots: false,
                        }
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            arrows: false,
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            arrows: false,
                            slidesToShow: 1
                        }
                    }
                ]
            });
        })(jQuery);
    </script>
@endpush
