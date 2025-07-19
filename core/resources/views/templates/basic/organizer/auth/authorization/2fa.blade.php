@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="container py-70">
        <div class="d-flex justify-content-center">
            <div class="verification-code-wrapper">
                <div class="verification-area">
                    <form action="{{ route('organizer.2fa.verify') }}" method="POST" class="submit-form">
                        @csrf

                        @include($activeTemplate . 'organizer.partials.verification_code')

                        <div class="form--group">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
