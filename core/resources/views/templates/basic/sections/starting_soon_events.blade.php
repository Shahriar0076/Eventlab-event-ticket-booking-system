@php

    $startingSoonEventsContent = getContent('starting_soon_events.content', true);

    $latestEvents = App\Models\Event::approved()
        ->where('start_date', '>', today())
        ->where('start_date', '<=', today()->addDays(7))
        ->orderBy('start_date', 'asc')
        ->with('location', 'organizer', 'likes', 'category', 'likes')
        ->limit(8)
        ->get();
@endphp

<div class="popular-section py-70">
    <div class="container">
        <div class="row gy-3 gy-md-4">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="style-left">
                        <span class="section-heading__subtitle">{{ __(@$startingSoonEventsContent->data_values->title) }} </span>
                        <h3 class="section-heading__title">
                            {{ __(@$startingSoonEventsContent->data_values->heading) }}
                        </h3>
                    </div>
                </div>
            </div>
            @foreach ($latestEvents as $event)
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <x-event :event="$event" />
                </div>
            @endforeach
        </div>
    </div>
</div>

@if (!app()->offsetExists('share_modal'))
    <x-share-modal />
    @php app()->offsetSet('share_modal',true) @endphp
@endif
