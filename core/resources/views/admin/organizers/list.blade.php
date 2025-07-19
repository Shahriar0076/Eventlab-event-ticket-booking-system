@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Organizer')</th>
                                    <th>@lang('Organization Name')</th>
                                    <th>@lang('Email-Mobile')</th>
                                    <th>@lang('Country')</th>
                                    <th>@lang('Featured')</th>
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Balance')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($organizers as $organizer)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $organizer->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a
                                                    href="{{ route('admin.organizers.detail', $organizer->id) }}"><span>@</span>{{ $organizer->username }}</a>
                                            </span>
                                        </td>

                                        <td>
                                            <span class="fw-bold">{{ @$organizer->organization_name }}</span>
                                            <br>
                                            <a
                                                href="{{ route('admin.event.index', ['search' => $organizer->organization_name]) }}">{{ $organizer->events->count() }}
                                                @lang('events')</a>
                                        </td>


                                        <td>
                                            {{ $organizer->email }}<br>{{ $organizer->mobileNumber }}
                                        </td>
                                        <td>
                                            <span class="fw-bold"
                                                title="{{ @$organizer->country_name }}">{{ $organizer->country_code }}</span>
                                        </td>

                                        <td>
                                            @php echo $organizer->featuredBadge @endphp
                                        </td>



                                        <td>
                                            {{ showDateTime($organizer->created_at) }} <br>
                                            {{ diffForHumans($organizer->created_at) }}
                                        </td>


                                        <td>
                                            <span class="fw-bold">
                                                {{ showAmount($organizer->balance) }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="button--group">
                                                @if ($organizer->is_featured)
                                                    <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure not to feature this organizer?')"
                                                        data-action="{{ route('admin.organizers.featured', $organizer->id) }}">
                                                        <i class="la la-eye-slash"></i> @lang('Featured')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure to feature this organizer?')"
                                                        data-action="{{ route('admin.organizers.featured', $organizer->id) }}">
                                                        <i class="la la-eye"></i> @lang('Featured')
                                                    </button>
                                                @endif


                                                <a href="{{ route('admin.organizers.detail', $organizer->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                                @if (request()->routeIs('admin.organizers.kyc.pending'))
                                                    <a href="{{ route('admin.organizers.kyc.details', $organizer->id) }}"
                                                        target="_blank" class="btn btn-sm btn-outline--dark">
                                                        <i class="las la-user-check"></i>@lang('KYC Data')
                                                    </a>
                                                @endif
                                            </div>
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
                @if ($organizers->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($organizers) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection



@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush
