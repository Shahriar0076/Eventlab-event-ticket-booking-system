@php
    $counters = getContent('counter.element', false, orderById: true);
@endphp

<div class="counter-up-section section-bg">
    <div class="container">
        <div class="row">
            <div class="counterup-item ">
                @foreach ($counters as $counter)
                    <div class="counterup-item__content">
                        <div class="d-flex align-items-center counterup-wrapper">
                            <span class="counterup-item__icon">
                                <img src="{{ frontendImage('counter', @$counter->data_values->image, '50x50') }}"
                                    alt="image">
                            </span>
                            <div class="content">
                                <div class="counterup-item__number">
                                    <h3 class="counterup-item__title mb-0"><span
                                            class="odometer">{{ @$counter->data_values->value }}</span></h3>
                                </div>
                                <span class="counterup-item__text mb-0"> {{ __(@$counter->data_values->title) }} </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
