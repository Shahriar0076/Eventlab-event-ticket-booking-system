@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="organizer-section py-70">
        <div class="container">
            <div class="row gy-4">
                @foreach ($organizers as $organizer)
                    @include($activeTemplate . '.partials.organaizer_card')
                @endforeach
                {{ paginateLinks($organizers) }}
            </div>
        </div>
    </div>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            $(".cta-section").addClass("pb-70").removeClass('py-70');
        })(jQuery);
    </script>
@endpush
