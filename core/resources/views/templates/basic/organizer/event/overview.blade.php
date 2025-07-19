@extends($activeTemplate.'organizer.layouts.master')
@section('content')
    <div class="add-event-wrapper">
        @include($activeTemplate.'organizer.partials.event_progress_bar')
        <div class="event">
            <div class="event__top">
                <h5 class="event__title"> {{ __($pageTitle) }} </h5>
                @if (!request()->routeIs('organizer.event.publish'))
                    <button id="saveAndContinue" type="button" class="btn btn--base">
                        @lang('Save & Continue') <span class="icon"><i class="fas fa-angle-right"></i></span>
                    </button>
                @endif
            </div>
            <div class="event__form">
                <form id="eventForm">
                    <div class="form-group  event-group">
                        <label class="form--label"> @lang('Select Category')</label>
                        <div class="event__content">
                            <select class="form-select form--control custom-select" data-searchable="true" name="category" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', @$event->category_id) == $category->id)>
                                        {{ __($category->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group  event-group">
                        <label class="form--label"> @lang('Select Location')</label>
                        <div class="event__content">
                            <select class="form-select form--control custom-select" data-searchable="true" name="location" required>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}" @selected(old('location_id', @$event->location_id) == $location->id)>
                                        {{ __($location->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group  event-group">
                        <label class="form--label"> @lang('Event Type')</label>
                        <div class="event__content">
                            <select class="form-select form--control custom-select" name="type" required>
                                <option value="{{ Status::EVENT_ONLINE }}" @selected(old('type', @$event->type) == Status::EVENT_ONLINE)>@lang('Online')
                                </option>
                                <option value="{{ Status::EVENT_OFFLINE }}" @selected(old('type', @$event->type) == Status::EVENT_OFFLINE)>@lang('Offline')
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group  event-group">
                        <label type="line" class="form--label typeLabel">
                            @if (@$event->type == Status::EVENT_OFFLINE)
                                @lang('Map Embed Link')
                            @else
                                @lang('Zoom Link / Online Meeting Link')
                            @endif
                        </label>
                        <div class="event__content">
                            <input type="url" name="link" class="form--control" id="link" value="{{ old('link', @$event->link) }}">
                        </div>
                    </div>
                    <div class="form-group  event-group">
                        <label type="message" class="form--label"> @lang('Location Address')<span class="text--danger">*</span></label>
                        <div class="event__content">
                            <textarea class="form--control" name="location_address" required>{{ old('location_address', @$event->location_address) }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('select[name="type"]').on('change', function() {
                    var selectedType = $(this).val();
                    if (selectedType == "{{ Status::EVENT_OFFLINE }}") {
                        $('.typeLabel').text('@lang('Map Embed Link')');
                    } else if (selectedType == "{{ Status::EVENT_ONLINE }}") {
                        $('.typeLabel').text('@lang('Zoom Link / Online Meeting Link')');
                    }
                });
            });

            //Ajax
            function submitData(){
                var btn = $('#saveAndContinue');
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#eventForm')[0]);
                var url      = '{{ route('organizer.event.store.overview', @$event->id ?? '') }}';
                var token    = '{{ csrf_token() }}';

                formData.append('_token', token);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        var btnAfterSubmit = `<div class="spinner-border"></div> @lang('Saving')...`;
                        btn.html(btnAfterSubmit);
                    },
                    complete: function (e) {
                        var btnName = `@lang('Save & Continue') <i class="las la-angle-right"></i>`;
                        btn.html(btnName);
                        btn.removeAttr('disabled');
                    },
                    success: function(response) {
                        if (response.success) {
                            @if (!$event)
                                window.location.href = response.redirect_url
                            @else
                                notify('success', `@lang('Event overview updated successfully')`);
                            @endif
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
