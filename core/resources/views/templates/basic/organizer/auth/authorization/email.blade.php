@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="container py-70">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <form action="{{ route('organizer.verify.email') }}" method="POST" class="submit-form">
                        @csrf
                        <p class="verification-text mb-3">@lang('A 6 digit verification code sent to your email address'): {{ showEmailAddress(authOrganizer()->email) }}
                        </p>

                        @include($activeTemplate . 'organizer.partials.verification_code')

                        <div class="mb-3">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>

                        <div class="mb-3">
                            <p>
                                @lang('If you don\'t get any code'), <span class="countdown-wrapper">@lang('try again after') <span id="countdown"
                                        class="fw-bold">--</span> @lang('seconds')</span> <a
                                    href="{{ route('organizer.send.verify.code', 'email') }}" class="try-again-link d-none">
                                    @lang('Try again')</a>
                            </p>
                            <a href="{{ route('organizer.logout') }}">@lang('Logout')</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        var distance = Number("{{ @$organizer->ver_code_send_at->addMinutes(2)->timestamp - time() }}");
        var x = setInterval(function() {
            distance--;
            document.getElementById("countdown").innerHTML = distance;
            if (distance <= 0) {
                clearInterval(x);
                document.querySelector('.countdown-wrapper').classList.add('d-none');
                document.querySelector('.try-again-link').classList.remove('d-none');
            }
        }, 1000);
    </script>
@endpush
