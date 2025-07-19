@extends($activeTemplate . 'organizer.layouts.master')
@section('content')
    <div class="add-event-wrapper">
        @include($activeTemplate . 'organizer.partials.event_progress_bar')
        <div class="event">
            <div class="event__top">
                <h5 class="event__title"> @lang('Gallery') </h5>
                @if (!request()->routeIs('organizer.event.publish'))
                    <button id="saveAndContinue" type="button" class="btn btn--base">@lang('Save & Continue') <span class="icon"><i class="fas fa-angle-right"></i></span></button>
                @endif
            </div>
            <div class="event__form">
                <form id="eventForm">
                    <div class="form-group  event-group">
                        <div class="event-group-inner">
                            <label class="form--label"> @lang('Upload Cover Photo')</label>
                            <small class="mt-3 text-muted">@lang('Supported Files'): <b>.png, .jpg, .jpeg</b>
                                @lang('Image will be resized into') <b>{{ getFileSize('eventCover') }}</b> px
                            </small>
                        </div>
                        <div class="event__content gallery-cover">
                            <img src="{{ getImage(getFilePath('eventCover') . '/' . $event->cover_image) }}" alt="image" class="fit-image uploadedImage @if (!$event->cover_image) fit-contain-image @endif">
                            <div class="upload-input-inner">
                                <input type="file" class="upload-input" id="fileInput" name="cover_image" accept="image/*">
                                <label class="upload-label" for="fileInput"><i class="la la-cloud-upload"></i></label>
                            </div>

                        </div>
                    </div>
                    <div class="form-group  event-group">
                        <div class="event-group-inner">
                            <label class="form--label"> @lang('Upload Event Image') / @lang('Gallery Images') <span class="text--danger">*</span> </label>
                            <small class="mt-3 text-muted">@lang('Supported Files'): <b>.png, .jpg, .jpeg</b>
                                @lang('Image will be resized into') <b>{{ getFileSize('eventGallery') }}</b> px
                            </small>
                        </div>
                        <div class="event__content">
                            <div class="event-img">
                                <div class="gallery-uploader">
                                    <div class="input-images-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/image-uploader.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.upload-input').on('change', function() {
                var imgTag = $(this).closest('.event__content').find('.uploadedImage');
                imgTag.removeClass('fit-contain-image');
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        imgTag.attr('src', e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });

            $('.input-images-2').imageUploader({
                @if (isset($event->galleryImages))
                    preloaded: [
                        @foreach ($event->galleryImages as $image)
                            {
                                id: '{{ $image->id }}',
                                src: '{{ getImage(getFilePath('eventGallery') . '/' . $image->image) }}'
                            },
                        @endforeach
                    ],
                @endif

                imagesInputName: 'gallery_images',
                preloadedInputName: 'oldGalleryImages',
                maxFiles: {{ gs('max_gallery_images') ?? 25 }}
            });

            //Ajax
            function submitData() {
                $('.coverImage').find('input[name="photos[]"]').attr('name', 'cover_image[]')

                var btn = $('#saveAndContinue');
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#eventForm')[0]);

                var url = '{{ route('organizer.event.store.gallery', @$event->id ?? '') }}';
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
                                notify('success', `@lang('Gallery info updated successfully')`);
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

            $('#eventForm').on("submit",function(e) {
                e.preventDefault();
                submitData();
            });

            $('#saveAndContinue').on('click', function() {
                submitData();
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .event-group-inner {
            flex: 1;
        }
    </style>
@endpush
