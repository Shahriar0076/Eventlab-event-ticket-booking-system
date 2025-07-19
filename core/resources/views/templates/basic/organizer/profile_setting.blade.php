@extends($activeTemplate . 'organizer.layouts.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Profile')</h5>
                </div>
                <div class="card-body">
                    <form class="register" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <label class="form-label">@lang('Organization Name')</label>
                                <input type="text" class="form-control form--control" name="organization_name"
                                    value="{{ @$organizer->organization_name }}" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('First Name')</label>
                                <input type="text" class="form-control form--control" name="firstname"
                                    value="{{ $organizer->firstname }}" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Last Name')</label>
                                <input type="text" class="form-control form--control" name="lastname"
                                    value="{{ $organizer->lastname }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('E-mail Address')</label>
                                <input class="form-control form--control" value="{{ $organizer->email }}" readonly>
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Mobile Number')</label>
                                <input class="form-control form--control" value="{{ $organizer->mobile }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('Address')</label>
                                <input type="text" class="form-control form--control" name="address"
                                    value="{{ @$organizer->address }}">
                            </div>
                            <div class="form-group col-sm-6">
                                <label class="form-label">@lang('State')</label>
                                <input type="text" class="form-control form--control" name="state"
                                    value="{{ @$organizer->state }}">
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label class="form-label">@lang('Zip Code')</label>
                                <input type="text" class="form-control form--control" name="zip"
                                    value="{{ @$organizer->zip }}">
                            </div>

                            <div class="form-group col-sm-4">
                                <label class="form-label">@lang('City')</label>
                                <input type="text" class="form-control form--control" name="city"
                                    value="{{ @$organizer->city }}">
                            </div>

                            <div class="form-group col-sm-4">
                                <label class="form-label">@lang('Country')</label>
                                <input class="form-control form--control" value="{{ @$organizer->country_name }}" disabled>
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
                                    value="{{ @$organizer->title }}">
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label">@lang('Short Description')</label>
                                <input type="text" class="form-control form--control" name="short_description"
                                    value="{{ @$organizer->short_description }}">
                            </div>
                            <div class="form-group col-sm-12">
                                <label class="form-label">@lang('Long Description')</label>
                                <textarea class="form-control form--control" name="long_description" cols="30" rows="10">{{ @$organizer->long_description }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
