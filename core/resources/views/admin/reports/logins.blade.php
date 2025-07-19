@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">

                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    @if (request()->routeIs('admin.report.user.*'))
                                        <th>@lang('User')</th>
                                    @else
                                        <th>@lang('Organizer')</th>
                                    @endif
                                    <th>@lang('Login at')</th>
                                    <th>@lang('IP')</th>
                                    <th>@lang('Location')</th>
                                    <th>@lang('Browser | OS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loginLogs as $log)
                                    <tr>

                                        <td>
                                            @if ($log->user)
                                                <span class="fw-bold">{{ @$log->user->fullname }}</span>
                                                <br>
                                                <span class="small"> <a
                                                        href="{{ route('admin.users.detail', $log->user_id) }}"><span>@</span>{{ @$log->user->username }}</a>
                                                </span>
                                            @else
                                                <span class="fw-bold">{{ @$log->organizer->fullname }}</span>
                                                <br>
                                                <span class="small"> <a
                                                        href="{{ route('admin.organizers.detail', $log->organizer_id) }}"><span>@</span>{{ @$log->organizer->username }}</a>
                                                </span>
                                            @endif
                                        </td>


                                        <td>
                                            {{ showDateTime($log->created_at) }} <br> {{ diffForHumans($log->created_at) }}
                                        </td>



                                        <td>
                                            <span class="fw-bold">
                                                @if ($log->user)
                                                    <a
                                                        href="{{ route('admin.report.user.login.ipHistory', [$log->ip]) }}">{{ $log->ip }}</a>
                                                @else
                                                    <a
                                                        href="{{ route('admin.report.organizer.login.ipHistory', [$log->ip]) }}">{{ $log->ip }}</a>
                                                @endif
                                            </span>
                                        </td>

                                        <td>{{ __($log->city) }} <br> {{ __($log->country) }}</td>
                                        <td>
                                            {{ __($log->browser) }} <br> {{ __($log->os) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($loginLogs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($loginLogs) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    @if (request()->routeIs('admin.report.user.login.history') || request()->routeIs('admin.report.organizer.login.history'))
        <x-search-form placeholder="Enter Username" dateSearch='yes' />
    @endif
@endpush
@if (request()->routeIs('admin.report.user.login.ipHistory') ||
        request()->routeIs('admin.report.organizer.login.ipHistory'))
    @push('breadcrumb-plugins')
        <a href="https://www.ip2location.com/{{ $ip }}" target="_blank"
            class="btn btn-outline--primary">@lang('Lookup IP') {{ $ip }}</a>
    @endpush
@endif
