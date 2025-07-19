@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="container  py-70">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-11 col-xl-9">
                <div class="card custom--card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('organizer.data.submit') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <label class="form-label">@lang('Organization Name')</label>
                                    <input type="text" class="form-control form--control" name="organization_name"
                                        value="{{ old('organization_name	') }}" required>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Username')</label>
                                        <input type="text" class="form-control form--control checkUser" name="username"
                                            value="{{ old('username') }}">
                                        <small class="text--danger usernameExist"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Country')</label>
                                        <select name="country" class="form-control form--control select2" required>
                                            @foreach ($countries as $key => $country)
                                                <option data-mobile_code="{{ $country->dial_code }}"
                                                    value="{{ $country->country }}" data-code="{{ $key }}">
                                                    {{ __($country->country) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Mobile')</label>
                                        <div class="input-group ">
                                            <span class="input-group-text mobile-code">

                                            </span>
                                            <input type="hidden" name="mobile_code">
                                            <input type="hidden" name="country_code">
                                            <input type="number" name="mobile" value="{{ old('mobile') }}"
                                                class="form-control form--control checkUser" required>
                                        </div>
                                        <small class="text--danger mobileExist"></small>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label">@lang('Address')</label>
                                    <input type="text" class="form-control form--control" name="address"
                                        value="{{ old('address') }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label">@lang('State')</label>
                                    <input type="text" class="form-control form--control" name="state"
                                        value="{{ old('state') }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label">@lang('Zip Code')</label>
                                    <input type="text" class="form-control form--control" name="zip"
                                        value="{{ old('zip') }}">
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label">@lang('City')</label>
                                    <input type="text" class="form-control form--control" name="city"
                                        value="{{ old('city') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="form-label">@lang('Profile Image')</label>
                                    <input type="file" class="form-control form--control" name="profile_image"
                                        accept="image/*">
                                    <small>{{ __('Supported Files: .png, .jpg, .jpeg Image will be resized into ' . getFileSize('organizerProfile') . ' px') }}</small>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="form-label">@lang('Cover Image')</label>
                                    <input type="file" class="form-control form--control" name="cover_image"
                                        accept="image/*">
                                    <small>{{ __('Supported Files: .png, .jpg, .jpeg Image will be resized into ' . getFileSize('organizerCover') . ' px') }}</small>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="form-label">@lang('Title')</label>
                                    <input type="text" class="form-control form--control" name="title"
                                        value="{{ old('title') }}">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="form-label">@lang('Short Description')</label>
                                    <input type="text" class="form-control form--control" name="short_description"
                                        value="{{ old('short_description') }}">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label class="form-label">@lang('Long Description')</label>
                                    <textarea class="form-control form--control" name="long_description" cols="30" rows="10">{{ old('long_description') }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn--base w-100">
                                    @lang('Submit')
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush


@push('script')
    <script>
        "use strict";
        (function($) {

            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif
            $('.select2').select2();

            $('select[name=country]').on('change', function() {
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
                var value = $('[name=mobile]').val();
                var name = 'mobile';
                checkUser(value, name);
            });

            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));


            $('.checkUser').on('focusout', function(e) {
                var value = $(this).val();
                var name = $(this).attr('name')
                checkUser(value, name);
            });

            function checkUser(value, name) {
                var url = '{{ route('organizer.checkUser') }}';
                var token = '{{ csrf_token() }}';

                if (name == 'mobile') {
                    var mobile = `${value}`;
                    var data = {
                        mobile: mobile,
                        mobile_code: $('.mobile-code').text().substr(1),
                        _token: token
                    }
                }
                if (name == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.field} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            }
        })(jQuery);
    </script>
@endpush
