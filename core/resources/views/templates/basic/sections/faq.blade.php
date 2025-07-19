@php
    $faqContent = getContent('faq.content', true);
    $faqs = getContent('faq.element', false, orderById: true);
@endphp

<div class="faq-section pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="section-heading style-left">
                        <span class="section-heading__subtitle"> {{ __(@$faqContent->data_values->title) }} </span>
                        <h3 class="section-heading__title mb-0">{{ __(@$faqContent->data_values->heading) }} </h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="accordion custom--accordion" id="accordionExample">
                    @foreach ($faqs as $faq)
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="faqHeading{{ $loop->iteration }}">
                                <button class="accordion-button @if (!$loop->first) collapsed @endif"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faqCollapse{{ $loop->iteration }}"
                                    @if ($loop->first) aria-expanded="true" @else aria-expanded="false" @endif
                                    aria-controls="faqCollapse{{ $loop->iteration }}">{{ __(@$faq->data_values->question) }}
                                </button>
                            </h3>
                            <div id="faqCollapse{{ $loop->iteration }}"
                                class="accordion-collapse collapse @if ($loop->first) show @endif"
                                aria-labelledby="faqHeading{{ $loop->iteration }}" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p class="desc">
                                        {{ __(@$faq->data_values->answer) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
