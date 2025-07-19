@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $contact = getContent('contact_us.content', true);
    @endphp
    <div class="contact-section py-70">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section-heading">
                        <h2 class="section-heading__title"> {{ __(@$contact->data_values->heading) }}</h2>
                        <p class="section-heading__desc"> {{ __(@$contact->data_values->description) }} </p>
                    </div>
                </div>
            </div>
            <div class="row gy-4 gy-md-5 justify-content-center">
                <div class="col-lg-4 col-sm-6">
                    <div class="contact-item">
                        <span class="contact-item__icon">
                            <img src="{{ asset($activeTemplateTrue . 'images/icons/contact-2.svg') }}" alt="shape">
                        </span>
                        <h6 class="contact-item__title"> @lang('Visit Us') </h6>
                        <p class="contact-item__desc"> {{ __(@$contact->data_values->address) }}</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="contact-item">
                        <span class="contact-item__icon">
                            <img src="{{ asset($activeTemplateTrue . 'images/icons/contact-3.svg') }}" alt="shape">
                        </span>
                        <h6 class="contact-item__title"> @lang('Call Us') </h6>
                        <p class="contact-item__desc"><a
                                href="tel:{{ str_replace(' ', '', @$contact->data_values->contact_number) }}">{{ @$contact->data_values->contact_number }}</a>
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="contact-item">
                        <span class="contact-item__icon">
                            <img src="{{ asset($activeTemplateTrue . 'images/icons/contact-1.svg') }}" alt="shape">
                        </span>
                        <h6 class="contact-item__title"> @lang('Write to Us') </h6>
                        <p class="contact-item__desc">
                            <a href="mailto:{{ @$contact->data_values->email }}">{{ @$contact->data_values->email }}</a>
                        </p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="contact-form-wrapper">
                        <h6 class="contact-form-wrapper__title mb-4"> @lang('Send a Message') </h6>
                        <form class="verify-gcaptcha" method="post" autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" class="form--control"
                                            value="{{ old('name', @$user->fullname) }}" name="name"
                                            placeholder="@lang('Name') *" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="email" class="form--control"
                                            value="{{ old('email', @$user->email) }}" name="email"
                                            placeholder="@lang('Email') *" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input type="text" class="form--control" placeholder="@lang('Subject') *"
                                            value="{{ old('subject') }}" name="subject" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <textarea class="form--control" placeholder="@lang('Message') *" name="message" required>{{ old('message') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <x-captcha :hideLabel="true" />

                            <button class="btn btn--base btn--lg contact-btn"> @lang('Send Message')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
