@extends($activeTemplate . 'organizer.layouts.master')
@section('content')
    <div class="card custom--card">
        <div class="card-header">
            <h5 class="card-title">@lang('KYC Form')</h5>
        </div>
        <div class="card-body">
            @if ($organizer->kyc_data)
                <ul class="list-group list-group-flush border rounded">
                    @foreach ($organizer->kyc_data as $val)
                        @continue(!$val->value)
                        <li class="list-group-item flex-column p-3">
                            <small class="text-muted d-block">{{ __($val->name) }}</small>

                            <span class="fw-bold">
                                @if ($val->type == 'checkbox')
                                    {{ implode(',', $val->value) }}
                                @elseif($val->type == 'file')
                                    <a href="{{ route('organizer.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}"
                                        class="me-3"><i class="fa-regular fa-file"></i> @lang('Attachment') </a>
                                @else
                                    {{ __($val->value) }}
                                @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            @else
                <h5 class="text-center">@lang('KYC data not found')</h5>
            @endif
        </div>
    </div>
@endsection
