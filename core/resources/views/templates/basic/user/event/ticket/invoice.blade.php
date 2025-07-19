<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>
    <link href="{{ siteFavicon() }}" rel="shortcut icon" type="image/png">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/invoice.css') }}">
</head>

<body>
    <header>
        <div class="row">
            <div class="col-12">
                <div class="list--row">
                    <div class="logo float-left">
                        <img alt="@lang('image')" class="logo-img"
                            src="{{ getImage(getFilePath('logo_icon') . '/logo.png') }}">
                    </div>
                    <h6 class="float-right m-0" style="margin: 0;"> {{ date('d/m/Y') }}</h6>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="row">
            <div class="col-12">
                <div class="address list--row">
                    <div class="float-left">
                        <h5 class="primary-text d-block fw-md">@lang('Ticket To')</h5>
                        <ul class="list" style="--gap: 0.3rem">
                            <li>
                                <div class="list list--row gap-5rem">
                                    <span class="strong">@lang('Name') :</span>
                                    <span>{{ $ticket->user->fullname }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="list list--row gap-5rem">
                                    <span class="strong">@lang('Email') :</span>
                                    <span>{{ $ticket->user->email }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="float-right">
                        <ul class="text-end">
                            <li>
                                <h5 class="primary-text d-block fw-md"> @lang('Ticket Information') </h5>
                            </li>
                            <li>
                                <span class="d-inline-block strong">@lang('Ticket Status') :</span>
                                <span class="d-inline-block">@php echo $ticket->statusBadge @endphp</span>
                            </li>

                            <li>
                                <span class="d-inline-block strong">@lang('Payment Status') :</span>
                                <span class="d-inline-block">@php echo $ticket->paymentData @endphp</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="body">
                    <div>
                        <h5 class="title">@lang('Tickets Details')</h5>
                        <ul class="list mb-3" style="--gap: 0.3rem">
                            <li>
                                <div class="list list--row gap-5rem">
                                    <span class="strong">@lang('Event') :</span>
                                    <span>{{ __($ticket->event->title) }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="list list--row gap-5rem">
                                    <span class="strong">@lang('Organized By') :</span>
                                    <span>{{ __($ticket->event->organizer->organization_name) }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <table class="table-bordered custom-table table">
                        <tbody>
                            @foreach ($ticket->details as $key => $detail)
                                <tr class="custom-table__subhead">
                                    <td colspan="2" style="text-align: center;">@lang('Ticket')
                                        {{ $key + 1 }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-start">@lang('First Name')</td>
                                    <td>{{ $detail['first_name'] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start">@lang('Last Name')</td>
                                    <td>{{ $detail['last_name'] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start">@lang('Email')</td>
                                    <td>{{ $detail['email'] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start">@lang('Price')</td>
                                    <td>{{ showAmount($ticket->price) }}</td>
                                </tr>
                            @endforeach

                            <tr class="custom-table__subhead">
                                <td class="text-end" colspan="1">@lang('Quantity')</td>
                                <td>{{ $ticket->quantity }}</td>
                            </tr>
                            <tr class="custom-table__subhead">
                                <td class="text-end" colspan="1">@lang('Total Price')</td>
                                <td>{{ showAmount($ticket->total_price) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
