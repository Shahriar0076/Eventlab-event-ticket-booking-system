@php
    $about_us = getContent('about_us.content', true);
@endphp

<div class="about-section py-70">
    <div class="container">
        <div class="row justify-content-center gy-4 align-items-center flex-wrap-reverse">
            <div class="col-lg-6 pe-lg-5">
                <div class="about-thumb">
                    <img src="{{ frontendImage('about_us', @$about_us->data_values->image, '600x400') }}" alt="image"
                        class="fit-image">
                    <a href="{{ @$about_us->data_values->youtube_embed_link }}" class="play-button"><span
                            class="icon"><i class="las la-play"></i></span></a>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <div class="about-right">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="section-heading style-left">
                                <span class="section-heading__subtitle"> {{ __(@$about_us->data_values->short_title) }}
                                </span>
                                <h3 class="section-heading__title"> {{ __(@$about_us->data_values->heading) }}</h3>
                                <p class="section-heading__desc"> {{ __(@$about_us->data_values->description) }} </p>
                                <div class="section-heading__button">
                                    <a href="{{ @$about_us->data_values->button_url }}" class="btn btn--base">
                                        {{ __(@$about_us->data_values->button_text) }} <span class="btn--icon two">
                                            <img src="{{ asset($activeTemplateTrue . 'images/icons/ticket-icon.svg') }}"
                                                alt="image">
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/magnific-popup.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/magnific-popup.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var videoItem = $(".play-button");
            if (videoItem) {
                videoItem.magnificPopup({
                    type: "iframe",
                });
            };
        })(jQuery);
    </script>
@endpush
