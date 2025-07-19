@php
    $cta = getContent('cta.content', true);
@endphp

<div class="cta-section py-70 ">
    <div class="container">
        <div class="cta-wrapper">
            <div class="cta-left">
                <div class="cta-left__content">
                    <h2 class="title">{{ __(@$cta->data_values->heading) }}</h2>
                    <div class="cta-btn">
                        <a href="{{ @$cta->data_values->button_url }}" class="btn btn--dark">
                            {{ __(@$cta->data_values->button_text) }}
                            <span class="btn--icon"><img
                                    src="{{ asset($activeTemplateTrue . 'images/icons/ticket-icon.svg') }}"
                                    alt="ticket-icon"></span> </a>
                        <div class="cta-btn__shape">
                            <img src="{{ getImage($activeTemplateTrue . 'images/shapes/cta-3.png') }}" alt="image">
                        </div>
                    </div>
                </div>
            </div>
            <div class="cta-right">
                <img src="{{ frontendImage('cta', @$cta->data_values->image, '750x360') }}" alt="image">
            </div>
        </div>
    </div>
</div>
