@php
    $organizers = App\Models\Organizer::active()->profileCompleted()->featured()->withCount('followers')->get();
    $featuredOrganizersContent = getContent('featured_organizers.content', true);
@endphp

<div class="organized-section py-70 bg-white">
    <div class="container">
        <div class="row gy-3 gy-md-4">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="style-left">
                        <span class="section-heading__subtitle"> {{ __(@$featuredOrganizersContent->data_values->title) }} </span>
                        <h3 class="section-heading__title">{{ __(@$featuredOrganizersContent->data_values->heading) }} </h3>
                    </div>
                    <a href="{{ route('organizer.index') }}" class="section-button"> @lang('View all') <span class="section-icon"><i
                                class="la la-arrow-right"></i></span> </a>
                </div>
            </div>
            @foreach ($organizers as $organizer)
                @include($activeTemplate . '.partials.organaizer_card')
            @endforeach
        </div>
    </div>
</div>
