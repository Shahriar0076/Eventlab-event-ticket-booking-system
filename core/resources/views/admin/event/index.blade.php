@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--lg  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Organizer')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Featured')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>

                                        <td>
                                            {{ __(strLimit($event->title, 30)) }}
                                            <br>
                                            <small class="text-muted"><i
                                                    class="la la-map-marker"></i>{{ strLimit(__($event->location->name), 10) }}</small>
                                        </td>

                                        <td>
                                            <a
                                                href="{{ route('admin.organizers.detail', $event->organizer->id) }}">{{ @$event->organizer->username }}</a>
                                        </td>

                                        <td>{{ __($event->category->name) }}</td>
                                        <td> {{ showDateTime($event->start_date, 'F j, Y') }}</td>
                                        <td>@php echo $event->featuredBadge @endphp</td>
                                        <td>@php echo $event->statusBadge() @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                @if ($event->is_featured)
                                                    <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sureto unfeature this event?')"
                                                        data-action="{{ route('admin.event.featured', $event->id) }}">
                                                        <i class="la la-eye-slash"></i> @lang('Unfeature')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure to feature this event?')"
                                                        data-action="{{ route('admin.event.featured', $event->id) }}">
                                                        <i class="la la-eye"></i> @lang('Feature')
                                                    </button>
                                                @endif
                                                <a href="{{ route('admin.event.details', $event->id) }}"
                                                    class="btn btn-sm btn-outline--primary">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
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
                @if (@$events->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($events) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>

    </div>

    <div class="modal fade" id="locationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Add New location')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"><span><i
                                class="las la-times"></i></span></button>
                </div>
                <form method="post" action="{{ route('admin.location.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name') </label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45"><i class="fa fa-send"></i>
                            @lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Name | Organizer" />
@endpush
