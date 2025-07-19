@extends($activeTemplate . 'organizer.layouts.master')
@section('content')
    <div class="add-event-wrapper">
        @include($activeTemplate . 'organizer.partials.event_progress_bar')
        <div class="event">
            <div class="event__top">
                <h5 class="event__title"> @lang('Time Slots') </h5>
                @if (!request()->routeIs('organizer.event.publish'))
                    <button id="saveAndContinue" type="button" class="btn btn--base">
                        @lang('Save & Continue') <span class="icon"><i class="fas fa-angle-right"></i></span>
                    </button>
                @endif
            </div>
            @php
                $startDate = old('start_date', $event->start_date);
                $endDate = old('end_date', $event->end_date);
                $startDateStr = new DateTime($startDate);
                $endDateStr = new DateTime($endDate);
                $randomNumber = rand(10000, 99999);
            @endphp

            <form id="eventForm">

                @while ($startDateStr <= $endDateStr)
                    @php
                        $formattedDate = showDateTime($startDateStr, 'd M Y');
                        $shortDate = showDateTime($startDateStr, 'Y-m-d');
                        $timeSlots = $event->timeSlots->where('date',$shortDate)->first();
                    @endphp
                    <div class="event-form-wrapper mb-4" data-id="{{ @$timeSlots->id ?? $randomNumber }}">
                        <div class="event-form-wrapper__top">
                            <h5 class="time" data-date="{{ $shortDate }}"> {{ $formattedDate }} </h5>
                        </div>

                        @if (isset($timeSlots))
                            @foreach (@$timeSlots->slots ?? [] as $timeSlot)
                                <div class="event__form">
                                    <div class="time-slot-contents" data-id="{{ $timeSlot->id }}">
                                        <div class="d-flex justify-content-end mb-2">
                                            <button type="button" class="btn btn-outline--danger btn--sm time-delete-btn"> <i class="far fa-trash-alt"></i> </button>
                                        </div>
                                        <input type="hidden" name="time_slots[{{ $timeSlots->id }}][date]" value="{{ $timeSlots->date }}">
                                        <div class="event__form">
                                            <div class="form-group  event-group">
                                                <label type="time" class="form--label"> @lang('Start Time') </label>
                                                <div class="event__content">
                                                    <input type="time" class="form--control" placeholder="12:00pm" id="time" name="time_slots[{{ $timeSlots->id }}][slots][{{ $timeSlot->id }}][start_time]" value="{{ $timeSlot->start_time }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group  event-group">
                                                <label type="time-end" class="form--label"> @lang('End Time') </label>
                                                <div class="event__content">
                                                    <input type="time" class="form--control" placeholder="6:00pm" name="time_slots[{{ $timeSlots->id }}][slots][{{ $timeSlot->id }}][end_time]" value="{{ $timeSlot->end_time }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group  event-group">
                                                <label type="title" class="form--label"> @lang('Title') </label>
                                                <div class="event__content">
                                                    <input type="text" class="form--control" name="time_slots[{{ $timeSlots->id }}][slots][{{ $timeSlot->id }}][title]" value="{{ $timeSlot->title }}" required>
                                                </div>
                                            </div>

                                            <div class="form-group  event-group">
                                                <label type="desc" class="form--label"> @lang('Description') </label>
                                                <div class="event__content">
                                                    <textarea id="desc" class="form--control" cols="30" rows="10" name="time_slots[{{ $timeSlots->id }}][slots][{{ $timeSlot->id }}][description]" required>{{ $timeSlot->description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        @php
                        $startDateStr->modify('+1 day');
                        $randomNumber = $randomNumber + 1 ;
                        @endphp
                        <div class="add-btn">
                            <button type="button" class="btn @if (isset($timeSlots)) btn-outline--base @else btn--base w-100 @endif  add-time-slots-btn">
                                @lang('Add Slot') <span class="icon"><i class="fas fa-plus"></i></span>
                            </button>
                        </div>
                    </div>
                @endwhile
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.time-delete-btn').on('click', function() {
                var parentRow = $(this).closest('.event-form-wrapper');
                $(this).closest('.time-slot-contents').remove();

                if (parentRow.find('.time-slot-contents').length > 0) {
                    parentRow.find('.add-time-slots-btn').removeClass('btn--base w-100').addClass('btn-outline--base');
                } else {
                    parentRow.find('.add-time-slots-btn').removeClass('btn-outline--base').addClass('btn--base w-100');
                }
            });

            $('.add-time-slots-btn').on('click', function() {
                const parentRow = $(this).closest('.event-form-wrapper');
                const date = parentRow.find('h5[data-date]').data('date');
                const id = parentRow.data('id');
                let length = parentRow.find('.time-slot-contents').length;
                let index = length ? length - 1 : 0;

                let lastItem = parentRow.find('.time-slot-contents').last();

                if (lastItem.length) {
                    index = (lastItem.data('id') * 1) + 1;
                }


                $(this).removeClass('btn--base w-100').addClass('btn-outline--base');

                let formHTML = `
                            <div class="time-slot-contents" data-id="${index}">
                                <div class="d-flex justify-content-end mb-2">
                                    <button type="button" class="btn btn-outline--danger btn--sm time-delete-btn"> <i class="far fa-trash-alt"></i> </button>
                                </div>

                                <input type="hidden" name="time_slots[${id}][date]" value="${date}">

                                <div class="event__form">
                                    <div class="form-group  event-group">
                                        <label type="time" class="form--label"> @lang('Start Time') </label>
                                        <div class="event__content">
                                            <input type="time" class="form--control" placeholder="12:00pm" id="time" name="time_slots[${id}][slots][${index}][start_time]" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group  event-group">
                                        <label type="time-end" class="form--label"> @lang('End Time') </label>
                                        <div class="event__content">
                                            <input type="time" class="form--control" placeholder="6:00pm" name="time_slots[${id}][slots][${index}][end_time]" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-group  event-group">
                                        <label type="title" class="form--label"> @lang('Title') </label>
                                        <div class="event__content">
                                            <input type="text" class="form--control" name="time_slots[${id}][slots][${index}][title]" value="" required>
                                        </div>
                                    </div>

                                    <div class="form-group  event-group">
                                        <label type="desc" class="form--label"> @lang('Description') </label>
                                        <div class="event__content">
                                            <textarea id="desc" class="form--control" cols="30" rows="10" name="time_slots[${id}][slots][${index}][description]" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    `;
                parentRow.find('.add-btn').before(formHTML);

            });

            $(document).on('click', '.time-delete-btn', function() {
                var parentRow = $(this).closest('.event-form-wrapper');
                $(this).closest('.time-slot-contents').remove();
                if (parentRow.find('.time-slot-contents').length > 0) {
                    parentRow.find('.add-time-slots-btn').removeClass('btn--base w-100').addClass('btn-outline--base');
                } else {
                    parentRow.find('.add-time-slots-btn').removeClass('btn-outline--base').addClass('btn--base w-100');
                }
            });

            //Ajax
            function submitData() {

                var btn = $('#saveAndContinue');
                btn.attr('disabled', true);

                //store
                var formData = new FormData($('#eventForm')[0]);
                var url = '{{ route('organizer.event.store.time.slots', $event->id) }}';
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
                                notify('success', `@lang('Time slots updated successfully')`);
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
