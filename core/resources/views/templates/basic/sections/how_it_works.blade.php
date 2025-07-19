@php
    $howItWorks = getContent('how_it_works.content', true);
    $howItWorksSteps = getContent('how_it_works.element', orderById: true);
@endphp

<div class="how-work-section py-70 section-bg">
    <div class="container">
        <div class="row gy-3 gy-md-4">
            <div class="col-lg-12">
                <div class="text-center">
                    <span class="span section-heading__subtitle"> {{ __(@$howItWorks->data_values->title) }} </span>
                    <h3 class="section-heading__title"> {{ __(@$howItWorks->data_values->heading) }}</h3>
                </div>
            </div>
            @foreach ($howItWorksSteps as $howItWorksStep)
                <div class="col-lg-3 col-sm-6 col-xsm-6">
                    <div class="how-work">
                        <span class="how-work__icon">
                            @php echo @$howItWorksStep->data_values->icon @endphp
                        </span>
                        <div class="how-work__content">
                            <h6 class="how-work__title"> {{ __(@$howItWorksStep->data_values->title) }}</h6>
                            <p class="how-work__desc"> {{ __(@$howItWorksStep->data_values->description) }} </p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
