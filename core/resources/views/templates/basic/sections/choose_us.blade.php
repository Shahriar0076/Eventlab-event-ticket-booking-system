@php
    $chooseUs = getContent('choose_us.content', true);
@endphp

<div class="why-choose-section py-70">
    <div class="container">
        <div class="row justify-content-center gy-4 align-items-center">
            <div class="col-lg-6">
                <div class="why-choose__left">
                    <div class="section-heading style-left">
                        <span class="section-heading__subtitle"> @lang('WHY CHOOSE US')</span>
                        <h3 class="section-heading__title"> {{ __(@$chooseUs->data_values->heading) }} </h3>
                        <p class="section-heading__desc"> {{ __(@$chooseUs->data_values->description) }} </p>
                    </div>
                    <div class="section-heading__button">
                        <a href="{{ __(@$chooseUs->data_values->button_url) }}" class="btn btn--base">
                            {{ __(@$chooseUs->data_values->button_text) }}
                            <span class="btn--icon two">
                                <img src="{{ asset($activeTemplateTrue . 'images/icons/ticket-icon.svg') }}"
                                    alt="image">
                            </span></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="why-choose__right">
                    <div class="why-choose__thumb">
                        <img src="{{ frontendImage('choose_us', @$chooseUs->data_values->image, '640x460') }}"
                            alt="image" class="fit-image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
