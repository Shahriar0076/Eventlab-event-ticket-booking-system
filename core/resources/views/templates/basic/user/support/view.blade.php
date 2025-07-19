@extends($activeTemplate . 'layouts.' . $layout)

@section('content')
    @if (!auth()->user())
        <div class="py-70">
            <div class="container">
                <div class="card custom--card">
                    <div class="card-body">
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="support-form">
                <div class="card-header-bg">
                    <h6 class="my-0 support-form-title">
                        @php echo $myTicket->statusBadge; @endphp
                        [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                    </h6>
                    @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                        <button class="confirmationBtn ticket-close-btn" type="button" data-question="@lang('Are you sure to close this ticket?')"
                            data-action="{{ route('ticket.close', $myTicket->id) }}"><i class="la la-times"></i>
                        </button>
                    @endif
                </div>
                <div class="support-ticket-form">
                    <form method="post" class="disableSubmission" action="{{ route('ticket.reply', $myTicket->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-between">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea name="message" class="form-control form--control" rows="4" required>{{ old('message') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <a href="javascript:void(0)" class="btn btn--base btn--sm addFile"><i class="fa fa-plus"></i>
                                @lang('Add New')</a>
                        </div>
                        <div class="form-group mt-3">
                            <label class="form-label">@lang('Attachments') <small class="text--danger">@lang('Max 5 files can be uploaded').
                                    @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small> </label>
                            <input type="file" name="attachments[]" class="form-control form--control"
                                accept=".jpg, .jpeg, .png, .pdf, .doc, .docx" />
                            <div id="fileUploadsContainer"></div>
                            <p class="mt-2 mb-4 ticket-attachments-message text-muted">
                                @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'),
                                .@lang('pdf'), .@lang('doc'), .@lang('docx')
                            </p>
                        </div>
                        <button type="submit" class="btn btn--base w-100"> <i class="fa fa-reply"></i>
                            @lang('Reply')</button>
                    </form>
                </div>
            </div>

            @foreach ($messages as $message)
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
            @endforeach
        </div>
    </div>
    @if (!auth()->user())
        </div>
        </div>
        </div>
        </div>
    @endif

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
                        <button type="submit" class="input-group-text btn--danger remove-btn text-white"><i class="las la-times"></i></button>
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
