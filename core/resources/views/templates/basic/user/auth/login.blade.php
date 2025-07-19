@extends($activeTemplate . 'layouts.app')
@section('panel')
    @php
        $loginRegistration = getContent('login_registration.content', true);
    @endphp

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
                            <h6 class="account-form__title"> @lang('User Login') </h6>
                            <div class="change-current-user-login">
                                <a href="{{ route('organizer.login') }}" class="btn w-100"> <span
                                        class="change-current-user-login-icon">
                                        <i class="las la-sign-in-alt text--base"></i>
                                    </span> @lang('Continue as Organizer') </a>
                            </div>
                        </div>

                        @include($activeTemplate . 'partials.social_login')

                        <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 form-group">
                                    <label for="email" class="form--label">@lang('Email or Username')</label>
                                    <input id="email" type="text" class="form--control" placeholder=""
                                        name="username" value="{{ old('username') }}" required>
                                </div>
                                <div class="col-sm-12 form-group">
                                    <label for="your-password" class="form--label">@lang('Password')</label>
                                    <div class="position-relative">
                                        <input id="your-password" type="password" class="form-control form--control"
                                            value="" name="password">
                                    </div>
                                </div>
                                <x-captcha />
                                <div class="col-sm-12 form-group">
                                    <div class="d-flex flex-wrap justify-content-between">
                                        <div class="form--check">
                                            <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">@lang('Remember me') </label>
                                        </div>
                                        <a href="{{ route('user.password.request') }}"
                                            class="forgot-password text--base fs-12"> @lang('Forgot Password?') </a>
                                    </div>
                                </div>

                                <div class="col-sm-12 form-group mt-2">
                                    <button type="submit" class="btn btn--base w-100" id="recaptcha"> @lang('Login')
                                    </button>
                                </div>

                                @if (gs('registration'))
                                    <div class="col-sm-12">
                                        <div class="have-account text-center">
                                            <p class="have-account__text">@lang("Don't Have An Account?") <a
                                                    href="{{ route('user.register') }}"
                                                    class="have-account__link text--base">@lang('Create Account')</a></p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
