@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4 ">
        @forelse ($events as $event)
            <div class="col-xl-4 col-sm-6">
                <x-event :event="$event" />
            </div>
        @empty
            <div class="col">
                <h4 class="text-center py-5">@lang('You didn\'t like any event yet')</h4>
            </div>
        @endforelse

        <div class="col-12">
            {{ paginateLinks($events) }}
        </div>
    </div>

    <x-share-modal />
@endsection
