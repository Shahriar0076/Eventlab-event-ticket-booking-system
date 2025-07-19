@extends($activeTemplate . 'organizer.layouts.' . $layout)

@section('content')
    <div class="support-form">
        <div class="card-header-bg">
            <h5 class="mt-0 support-form-title">
                @php echo $myTicket->statusBadge; @endphp
                [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
            </h5>
            @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->organizer)
                <button class="ticket-close-btn confirmationBtn" type="button" data-question="@lang('Are you sure to close this ticket?')"
                    data-action="{{ route('organizer.ticket.close', $myTicket->id) }}"><i class="la la-times"></i>
                </button>
            @endif
        </div>
        <div class="support-ticket-form">
            <form method="post" class="disableSubmission" action="{{ route('organizer.ticket.reply', $myTicket->id) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="row justify-content-between">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea name="message" class="form-control form--control" rows="4" required>{{ old('message') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="text-end d-flex justify-content-end">
                    <a href="javascript:void(0)" class="btn btn--base btn--sm addFile"><i class="fa fa-plus"></i>
                        @lang('Add New')</a>
                </div>
                <div class="form-group">
                    <label class="form-label">@lang('Attachments') <small class="text--danger">@lang('Max 5 files can be uploaded').
                            @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small></label>
                    <input type="file" name="attachments[]" class="form-control form--control"
                        accept=".jpg, .jpeg, .png, .pdf, .doc, .docx" />
                    <div id="fileUploadsContainer"></div>
                    <p class="my-2 ticket-attachments-message text-muted">
                        @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'),
                        .@lang('doc'), .@lang('docx')
                    </p>
                </div>
                <button type="submit" class="btn btn--base w-100"> <i class="fa fa-reply"></i> @lang('Reply')</button>
            </form>
        </div>
    </div>


    <div class="support-ticket-wrapper">
        @forelse ($messages as $message)
            @if ($message->admin_id == 0)
                <div class="support-ticket">
                    <div class="flex-align gap-3 mb-2">
                        <h6 class="support-ticket-name">{{ $message->ticket->name }}</h6>
                        <p class="support-ticket-date"> @lang('Posted on')
                            {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                    </div>

                    <p class="support-ticket-message">{{ $message->message }}</p>

                    @if ($message->attachments->count() > 0)
                        <div class="support-ticket-file mt-2">
                            @foreach ($message->attachments as $k => $image)
                                <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3">
                                    <span class="icon"><i class="la la-file-download"></i></span>
                                    @lang('Attachment')
                                    {{ ++$k }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="support-ticket reply">
                    <div class="flex-align gap-3 mb-2">
                        <h6 class="support-ticket-name">{{ $message->admin->name }} <span
                                class="staff">@lang('Staff')</span></h6>
                        <p class="support-ticket-date"> @lang('Posted on')
                            {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                    </div>

                    <p class="support-ticket-message">{{ $message->message }}</p>

                    @if ($message->attachments->count() > 0)
                        <div class="support-ticket-file mt-2">
                            @foreach ($message->attachments as $k => $image)
                                <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="me-3">
                                    <span class="icon"><i class="la la-file-download"></i></span>
                                    @lang('Attachment')
                                    {{ ++$k }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        @empty
            <div class="empty-message text-center">
                <img src="{{ asset('assets/images/empty_list.png') }}" alt="empty">
                <h5 class="text-muted">@lang('No replies found here!')</h5>
            </div>
        @endforelse
    </div>

    <x-confirmation-modal />
@endsection
@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 4) {
                    notify('error', 'You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="input-group my-3">
                        <input type="file" name="attachments[]" class="form-control form--control" required accept=".jpg, .jpeg, .png, .pdf, .doc, .docx"/>
                        <button type="submit" class="input-group-text btn-danger remove-btn"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.input-group').remove();
            });
        })(jQuery);
    </script>
@endpush
