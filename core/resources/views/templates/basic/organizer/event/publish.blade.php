@extends($activeTemplate . 'organizer.layouts.master')
@section('content')
    @php
        $isApproved = $event->status == Status::EVENT_APPROVED;
        $isDraft = $event->status == Status::EVENT_DRAFT;
        $isRejected = $event->status == Status::EVENT_REJECTED;
        $isVerificationProcessActive = gs('event_verification');
    @endphp

    <div class="add-event-wrapper">
        @include($activeTemplate . 'organizer.partials.event_progress_bar')
        <div class="event">
            <div class="event__top">
                <h5 class="event__title"> @lang('Publish') </h5>
                <div class="d-flex align-items-center flex-wrap gap-3">
                    @if (!$isDraft)
                        @if ($isRejected)
                            <button class="btn btn--base" id="eventPublished" type="button">@lang('Submit again for verification')</button>
                        @endif
                    @else
                        @if ($isVerificationProcessActive)
                            <button class="btn btn--warning" id="eventPublished" type="button">@lang('Submit for verification')</button>
                        @else
                            <button class="btn btn--base" id="eventPublished" type="button">@lang('Publish your event now')</button>
                        @endif
                    @endif
                </div>
            </div>
            <form id="eventForm">
                <div class="row justify-content-center  mt-5 ">
                    <div class="col-lg-9">
                        <div class="event-publish text-center">

                            @if ($isVerificationProcessActive)
                                @if ($isApproved)
                                    <div class="event-publish-icon text--success">
                                        <i class="far fa-check-circle"></i>
                                    </div>
                                @elseif($isRejected)
                                    <div class="event-publish-icon text--danger">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                @else
                                    <div class="event-publish-icon text--warning">
                                        <i class="fas fa-circle-notch transform-rotate-90"></i>
                                    </div>
                                @endif
                            @else
                                <div class="event-publish-icon text--success">
                                    <i class="far fa-check-circle"></i>
                                </div>
                            @endif

                            @if (!$isDraft)
                                @if ($isRejected)
                                    <h4 class="mb-3 draft-notify">@lang('Event is rejected')</h4>
                                @else
                                    @if ($isApproved)
                                        <h4 class="mb-3 draft-notify">@lang('Congratulations! Event is published')</h4>
                                    @else
                                        <h4 class="mb-3 draft-notify">@lang('Waiting for approval')</h4>
                                    @endif
                                @endif
                            @else
                                @if ($isVerificationProcessActive)
                                    <h4 class="mb-3 published-notify"> @lang('Submit your Event for Verification')</h4>
                                    <p class="mb-3 info">@lang('Submit your event for verification, the event will be verified by the administrator')</p>
                                @else
                                    <h4 class="mb-3 published-notify"> @lang('Almost Ready for Publishing')</h4>
                                    <p class="mb-3 info">@lang('Your anticipation is appreciated as we put the finishing touches on our event. Are you ready to publish?')</p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('#eventPublished').on('click', function() {
                var btn = $('#eventPublished');
                @if ($isVerificationProcessActive)
                    var btnStatus = '@lang('Submitting')';
                    var publishedNotify = '@lang('Event is submitted for verification')';
                @else
                    var btnStatus = '@lang('Publishing')';
                    var publishedNotify = '@lang('Congratulations Event is published')';
                @endif

                var btnText = btn.text();
                var spinnerHtml = '<div class="spinner-border"></div>';
                var url = '{{ route('organizer.event.store.publish', $event->id) }}';
                var token = '{{ csrf_token() }}';
                btn.html(spinnerHtml + ' ' + btnStatus + '...');
                btn.attr('disabled', true);

                var data = {
                    is_published: 1,
                    _token: token
                };

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            if (response.status == 0) {
                                notify('success', `@lang('Event save and drafted successfully')`);
                                $('.draft-notify').text(`@lang('Event is Drafted')`);
                            } else {
                                $('.published-notify').text(publishedNotify);
                            }
                            setTimeout(() => {
                                btn.hide();
                                $('.info').hide();
                                window.location.href = response.redirect_url;
                            }, 1000);
                        } else {
                            notify('error', response.message);
                        }
                    }
                });

            });

        })(jQuery);
    </script>
@endpush
