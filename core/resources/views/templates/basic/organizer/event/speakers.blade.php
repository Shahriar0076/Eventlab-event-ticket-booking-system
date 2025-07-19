@extends($activeTemplate . 'organizer.layouts.master')
@section('content')
    <div class="add-event-wrapper">
        @include($activeTemplate . 'organizer.partials.event_progress_bar')
        <div class="event">
            <div class="event__top">
                <h5 class="event__title"> @lang('Speakers') </h5>
                @if (!request()->routeIs('organizer.event.publish'))
                    <button id="saveAndContinue" type="button" class="btn btn--base">@lang('Save & Continue') <span class="icon"><i class="fas fa-angle-right"></i></span>
                    </button>
                @endif
            </div>
            <form id="eventForm">
                <div class="event-speaker">
                    @if ($event->speakers)
                        @foreach (@$event->speakers as $speaker)
                            <div class="event-speaker__wrapper">
                                <div class="event-group-inner">
                                    <h6 class="event-speaker__wrapper-title"> @lang('Speaker') {{ $loop->iteration }} </h6>
                                    <div class="event-speaker-item__thumb">
                                        <img src="{{ getImage(getFilePath('eventSpeaker') . '/' . $speaker->image, getFileSize('eventSpeaker'), $avatar = true) }}" alt="image" class="fit-image uploadedImage">
                                        <div class="upload-input-inner">
                                            <input type="file" class="upload-input" id="fileInput" name="speakers[{{ $speaker->id }}][image]" accept="image/*">
                                            @if ($speaker->image)
                                                <input type="hidden" name="speakers[{{ $speaker->id }}][image_old]" value="{{ $speaker->image }}">
                                            @endif
                                            <label class="upload-label" for="fileInput"><i class="la la-cloud-upload"></i></label>
                                        </div>
                                    </div>
                                    <small class="text-muted text-center d-block">@lang('Supported Files'): <strong>.png, .jpg,
                                            .jpeg</strong>
                                        @lang('Image will be resized into') <strong>{{ getFileSize('eventSpeaker') }}</strong> px
                                    </small>
                                </div>
                                <div class="event-speaker-item">
                                    <div class="event-form">
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label class="form-label-two">@lang('Name')</label>
                                                <input type="text" class="form--control form-three" name="speakers[{{ $speaker->id }}][name]" value="{{ $speaker->name }}">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label-two">@lang('Designation')</label>
                                                <input type="text" class="form--control form-three" name="speakers[{{ $speaker->id }}][designation]" value="{{ $speaker->designation }}">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label-two">@lang('Facebook Url')</label>
                                                <input type="url" class="form--control form-three" name="speakers[{{ $speaker->id }}][facebook_url]" value="{{ @$speaker->social['facebook_url'] }}">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label-two">@lang('Youtube Url')</label>
                                                <input type="url" class="form--control form-three" name="speakers[{{ $speaker->id }}][youtube_url]" value="{{ @$speaker->social['youtube_url'] }}">
                                            </div>
                                            <div class="form-group ">
                                                <label class="form-label-two">@lang('Instagram Url')</label>
                                                <input type="url" class="form--control" name="speakers[{{ $speaker->id }}][instagram_url]" value="{{ @$speaker->social['instagram_url'] }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="speaker-delete-btn">
                                    <button type="button" class="delete-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="@lang('Delete')" data-id="{{ $speaker->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <p class="text-center mb-3 empty-message">@lang('Add some speakers to your event')</p>
                    <div class="event-speaker-btn">
                        <button type="button" class="btn btn--base"> <span class="icon"><i class="las la-plus"></i></span>
                        </button>
                    </div>
                </div>
                <form>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .upload-input-inner {
            position: absolute;
            bottom: 10px;
            right: 0px;
            outline: 2px solid hsl(var(--white));
            border-radius: 50%;
            overflow: hidden;
            font-size: 0;
        }

        .upload-input {
            position: absolute;
            height: 100%;
            width: 100%;
            opacity: 0;
            appearance: none;
            cursor: pointer;
        }

        .upload-label {
            width: 30px;
            height: 30px;
            background-color: hsl(var(--base));
            color: hsl(var(--white));
            display: grid;
            place-content: center;
            font-size: 18px;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.event-speaker__wrapper').length > 0 ? $('.empty-message').hide() : $('.empty-message').show();

            function addImageInputListener() {
                // Listen for change event on file input
                $('.upload-input').on('change', function() {
                    var imgTag = $(this).closest('.event-speaker-item__thumb').find('.uploadedImage');
                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            imgTag.attr('src', e.target.result);
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
            addImageInputListener();

            var num = $('.event-speaker__wrapper').length > 0 ? $('.event-speaker__wrapper').length + 1 : 1;
            var index = Math.floor(Math.random() * (999999 - 100000 + 1)) + 100000;

            // Array to store deleted speakers
            var deletedSpeakers = [];

            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                // Check if the data-id attribute exists
                if ($(this).data('id')) {
                    var deleteImageUrl = $(this).data('id');
                    // Append the deleted image URL to the array
                    deletedSpeakers.push(deleteImageUrl);
                }

                // Optionally, you can remove the closest row after the button is clicked
                $(this).closest('.event-speaker__wrapper').remove()
                $('.event-speaker__wrapper').length > 0 ? $('.empty-message').hide() : $('.empty-message')
                    .show();
            });




            $(document).on('click', ".event-speaker-btn button", function(e) {
                e.preventDefault();
                //add seakers
                $('.event-speaker-btn').before(`
                <div class="event-speaker__wrapper">
                                <div class="event-group-inner">
                                    <h6 class="event-speaker__wrapper-title"> @lang('Speaker') ${num} </h6>
                                    <div class="event-speaker-item__thumb">
                                        <img src="{{ getImage('', getFileSize('eventSpeaker'), $avatar = true) }}"
                                            alt="image" class="fit-image uploadedImage">
                                        <div class="upload-input-inner">
                                            <input type="file" class="upload-input" id="fileInput"
                                                name="speakers[${index}][image]" accept="image/*">
                                            <label class="upload-label" for="fileInput"><i
                                                    class="la la-cloud-upload"></i></label>
                                        </div>
                                    </div>
                                    <small class="text-muted text-center d-block">@lang('Supported Files'): <strong>.png, .jpg,
                                            .jpeg</strong>
                                        @lang('Image will be resized into') <strong>{{ getFileSize('eventSpeaker') }}</strong> px
                                    </small>
                                </div>
                                <div class="event-speaker-item">
                                    <div class="event-form">
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label class="form-label-two">@lang('Name')</label>
                                                <input type="text" class="form--control form-three"
                                                    name="speakers[${index}][name]">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label-two">@lang('Designation')</label>
                                                <input type="text" class="form--control form-three"
                                                    name="speakers[${index}][designation]">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label-two">@lang('Facebook Url')</label>
                                                <input type="url" class="form--control form-three"
                                                    name="speakers[${index}][facebook_url]">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label class="form-label-two">@lang('Youtube Url')</label>
                                                <input type="url" class="form--control form-three"
                                                    name="speakers[${index}][youtube_url]">
                                            </div>
                                            <div class="form-group ">
                                                <label class="form-label-two">@lang('Instagram Url')</label>
                                                <input type="url" class="form--control"
                                                    name="speakers[${index}][instagram_url]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="speaker-delete-btn">
                                    <button type="button" class="delete-btn" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="@lang('Delete')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                `);

                num = num + 1
                index = index + 1

                $('.delete-btn').on('click', function(e) {
                    e.preventDefault();
                    $(this).closest('.event-speaker__wrapper').remove()
                    $('.event-speaker__wrapper').length > 0 ? $('.empty-message').hide() : $(
                        '.empty-message').show();
                });
                addImageInputListener()
                $('.empty-message').hide();

            });

            //Ajax
            function submitData() {
                var btn = $('#saveAndContinue');
                btn.attr('disabled', true);

                // Store form data
                var formData = new FormData($('#eventForm')[0]);

                // Append deleted images to formData
                $.each(deletedSpeakers, function(index, value) {
                    formData.append('deleted_speakers[]', value);
                });

                var url = '{{ route('organizer.event.store.speakers', @$event->id ?? '') }}';
                var token = '{{ csrf_token() }}';
                formData.append('_token', token);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        var btnAfterSubmit = `<div class="spinner-border"></div> @lang('Saving')...`;
                        btn.html(btnAfterSubmit);
                    },
                    complete: function(e) {
                        var btnName = `@lang('Save & Continue') <i class="las la-angle-right"></i>`;
                        btn.html(btnName);
                        btn.removeAttr('disabled');
                    },
                    success: function(response) {
                        if (response.success) {
                            if (!response.is_update) {
                                window.location.href = response.redirect_url
                            } else {
                                notify('success', `@lang('Speakers updated successfully')`);
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        } else {
                            notify('error', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        notify('error', error);
                    }
                });
            }

            $('#eventForm').on('submit', function(e) {
                e.preventDefault();
                submitData();
            });

            $('#saveAndContinue').on('click', function() {
                submitData();
            });

        })(jQuery);
    </script>
@endpush
