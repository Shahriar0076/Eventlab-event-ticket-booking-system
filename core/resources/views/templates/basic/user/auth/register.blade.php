@extends($activeTemplate . 'layouts.app')
@section('panel')
    @php
        $loginRegistration = getContent('login_registration.content', true);
    @endphp
    @if (gs('registration'))
        <section class="account">
            <div class="account-inner">
                <div class="account-inner__left">
                    <div class="account-thumb">
                        <img src="{{ frontendImage('login_registration', @$loginRegistration->data_values->image, '1280x950') }}"
                            alt="image">
                    </div>
                </div>
                <div class="account-inner__right">
                    <div class="account-form-wrapper">
                        <div class="account-form">
                            <div class="account-form__content mb-4">
                                <a href="{{ route('home') }}" class="account-form__logo">
                                    <img src="{{ siteLogo() }}" alt="image">
                                </a>
                                <h6 class="account-form__title"> @lang('User Register') </h6>
                                <div class="change-current-user-login">
                                    <a href="{{ route('organizer.login') }}" class="btn w-100"> <span
                                            class="change-current-user-login-icon"><i
                                                class="las la-sign-in-alt text--base"></i>
                                        </span> @lang('Continue as Organizer') </a>
                                </div>
                            </div>


                            @include($activeTemplate . 'partials.social_login')

                            <form method="POST" action="{{ route('user.register') }}"
                                class="verify-gcaptcha disableSubmission">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6 form-group">
                                        <label for="firstname" class="form--label">@lang('First Name')</label>
                                        <input type="text" class="form--control" name="firstname"
                                            value="{{ old('firstname') }}" id="firstname" required>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label for="lastname" class="form--label">@lang('Last Name')</label>
                                        <input type="text" class="form--control" name="lastname"
                                            value="{{ old('lastname') }}" id="lastname" required>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label for="email" class="form--label">@lang('Email')</label>
                                        <input type="text" class="form--control checkUser" name="email"
                                            value="{{ old('email') }}" id="email" required>
                                        <small class="text--danger emailExist"></small>
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label for="your-password" class="form--label">@lang('Password')</label>
                                        <div class="position-relative">
                                            <input id="your-password" type="password"
                                                class="form-control form--control @if (gs('secure_password')) secure-password @endif"
                                                value="" name="password" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 form-group">
                                        <label for="confirm-password" class="form--label">@lang('Confirm Password')</label>
                                        <div class="position-relative">
                                            <input id="confirm-password" type="password" class="form-control form--control"
                                                value="" name="password_confirmation" required>
                                        </div>
                                    </div>

                                    <x-captcha />

                                    @if (gs('agree'))
                                        @php
                                            $policyPages = getContent('policy_pages.element', false, orderById: true);
                                        @endphp
                                        <div class="col-sm-12 form-group">
                                            <div class="form--check">
                                                <input class="form-check-input" type="checkbox" id="agree"
                                                    @checked(old('agree')) name="agree" required>
                                                <label class="form-check-label" for="agree"> @lang('I agree with')
                                                    @foreach ($policyPages as $policy)
                                                        <a class="text--base"
                                                            href="{{ route('policy.pages', $policy->slug) }}"
                                                            target="_blank">
                                                            {{ __(@$policy->data_values->title) }}@if (!$loop->last)
                                                                ,
                                                            @endif
                                                        </a>
                                                    @endforeach
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-sm-12 form-group mt-2">
                                        <button type="submit" id="recaptcha" class="btn btn--base w-100">
                                            @lang('Register')
                                        </button>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="have-account text-center">
                                            <p class="have-account__text">@lang('Already Have an Account?') <a
                                                    href="{{ route('user.login') }}"
                                                    class="have-account__link text--base">@lang('Login Now')</a></p>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                        <span class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </span>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark btn--sm"
                            data-bs-dismiss="modal">@lang('Close')</button>
                        <a href="{{ route('user.login') }}" class="btn btn--base btn--sm">@lang('Login')</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        @include($activeTemplate . 'partials.registration_disabled')
    @endif
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('script')
    <script>
        "use strict";
        (function($) {

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                var data = {
                    email: value,
                    _token: token
                }

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $('#existModalCenter').modal('show');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
