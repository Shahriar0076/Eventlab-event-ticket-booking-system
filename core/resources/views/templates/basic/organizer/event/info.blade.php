@extends($activeTemplate . 'organizer.layouts.master')
@section('content')
    <div class="add-event-wrapper">
        @include($activeTemplate . 'organizer.partials.event_progress_bar')
        <div class="event">
            <div class="event__top">
                <h5 class="event__title"> {{ __($pageTitle) }} </h5>
                @if (!request()->routeIs('organizer.event.publish'))
                    <button id="saveAndContinue" type="button" class="btn btn--base">@lang('Save & Continue') <span class="icon"><i
                                class="fas fa-angle-right"></i></span>
                    </button>
                @endif
            </div>
            <div class="event__form">
                <form id="eventForm">
                    <div class="form-group  event-group">
                        <label class="form--label"> @lang('Event Title')</label>
                        <div class="event__content">
                            <input type="text" class="form--control" name="title"
                                value="{{ old('title', @$event->title) }}" required>
                        </div>
                    </div>
                    <div class="form-group  event-group">
                        <label class="form--label"> @lang('Short Description')</label>
                        <div class="event__content">
                            <input type="text" class="form--control" name="short_description"
                                value="{{ old('short_description', @$event->short_description) }}">
                        </div>
                    </div>
                    <div class="form-group  event-group">
                        <label class="form--label"> @lang('Description')</label>
                        <div class="event__content">
                            <textarea class="form--control nicEdit" name="description">{{ old('description', @$event->description) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group  event-group">
                        <label class="form--label"> @lang('Start Date')</label>
                        <div class="event__content">
                            <input type="date" class="form--control" name="start_date"
                                value="{{ old('start_date', @$event->start_date) }}" required>
                        </div>
                    </div>
                    <div class="form-group  event-group">
                        <label class="form--label"> @lang('End Date')</label>
                        <div class="event__content">
                            <input type="date" class="form--control" name="end_date"
                                value="{{ old('end_date', @$event->end_date) }}" required>
                        </div>
                    </div>
                    <div class="form-group  event-group">
                        <label class="form--label"> @lang('Total Seats')</label>
                        <div class="event__content">
                            <input type="number" class="form--control" name="seats"
                                value="{{ old('seats', @$event->seats) }}" required>
                        </div>
                    </div>
                    <div class="form-group  event-group">
                        <label class="form--label"> @lang('Ticket Price')</label>
                        <div class="event__content">
                            <div class="input-group">
                                <input type="number" step="any" class="form-control form--control" name="price"
                                    id="price" value="{{ old('price', @$event->price) }}" required>
                                <div class="input-group-text">{{ gs('cur_text') }}</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .nicEdit-main {
            outline: none !important;
        }

        .nicEdit-custom-main {
            border-radius: 0 0 5px 5px !important;
            background-color: #fff;
            border-left-color: #ebebeb !important;
            border-right-color: #ebebeb !important;
            border-bottom-color: #ebebeb !important;
        }

        .nicEdit-panelContain {
            border-color: #ebebeb !important;
            border-radius: 5px 5px 0 0 !important;
            background-color: #ffffff !important
        }

        .nicEdit-buttonContain div {
            background-color: #fff !important;
            border: 0 !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 10px !important;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/nicEdit.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";

        bkLib.onDomLoaded(function() {
            $(".nicEdit").each(function(index) {
                $(this).attr("id", "nicEditor" + index);

                new nicEditor({
                    fullPanel: false
                }).panelInstance('nicEditor' + index, {
                    hasPanel: true
                });
                $('.nicEdit-main').parent('div').addClass('nicEdit-custom-main')
            });
        });
    </script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            //Ajax
            function submitData() {
                var btn = $('#saveAndContinue');
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#eventForm')[0]);

                var nicInstance = nicEditors.findEditor('nicEditor0');
                var nicContent = nicInstance.getContent();

                var url = '{{ route('organizer.event.store.info', $event->id) }}';
                var token = '{{ csrf_token() }}';

                formData.append('_token', token);
                formData.append('description', nicContent);

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
                                notify('success', `@lang('Info info updated successfully')`);

                                setTimeout(function() {
                                    window.location.href =
                                        '{{ route('organizer.event.time.slots', $event->id) }}';
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

            $('#eventForm').on("submit", function(e) {
                e.preventDefault();
                submitData();
            });

            $('#saveAndContinue').on('click', function() {
                submitData();
            });

        })(jQuery);
    </script>
@endpush
