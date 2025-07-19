@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two sortable-table">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Events')</th>
                                    <th>@lang('Featured')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="sort">
                                @forelse($locations as $location)
                                    <tr class="sortable-table-row" data-id="{{ $location->id }}">
                                        <td>
                                            <div class="user">
                                                <div class="thumb"><img
                                                        src="{{ getImage(getFilePath('location') . '/' . $location->image, getFileSize('location')) }}"
                                                        alt="{{ __($location->name) }}" class="plugin_bg"></div>
                                                <span class="name">{{ __($location->name) }}</span>
                                            </div>
                                        </td>

                                        <td>{{ $location->events_count }}</td>
                                        <td>@php echo $location->featuredBadge @endphp</td>
                                        <td> @php echo $location->statusBadge; @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <button type="button" data-id="{{ $location->id }}"
                                                    data-name="{{ $location->name }}" data-image="{{ $location->image }}"
                                                    data-route="{{ route('admin.location.store', $location->id) }}"
                                                    data-bs-toggle="modal" data-bs-target="#locationModal"
                                                    class="btn btn-outline--primary editBtn btn-sm">
                                                    <i class="las la-pen"></i>@lang('Edit')
                                                </button>

                                                @if ($location->is_featured)
                                                    <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure not to feature this location?')"
                                                        data-action="{{ route('admin.location.featured', $location->id) }}">
                                                        <i class="la la-eye-slash"></i> @lang('Unfeature')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn"
                                                        data-question="@lang('Are you sure to feature this location?')"
                                                        data-action="{{ route('admin.location.featured', $location->id) }}">
                                                        <i class="la la-eye"></i> @lang('Featured')
                                                    </button>
                                                @endif

                                                @if ($location->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-question="@lang('Are you sure to enable this location?')"
                                                        data-action="{{ route('admin.location.status', $location->id) }}"><i
                                                            class="la la-eye"></i>@lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-question="@lang('Are you sure to disable this location?')"
                                                        data-action="{{ route('admin.location.status', $location->id) }}"><i
                                                            class="la la-eye-slash"></i>@lang('Disable')
                                                    </button>
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
                        </table>
                    </div>
                </div>
                @if ($locations->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($locations) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="locationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Add Location')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"><span><i
                                class="las la-times"></i></span></button>
                </div>
                <form method="post" action="{{ route('admin.location.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Image')</label>
                            <x-image-uploader image="" class="w-100" type="location" :required=true />
                        </div>

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
    <button type="button" data-route="{{ route('admin.location.store') }}" data-bs-target="#locationModal"
        data-bs-toggle="modal" class="btn btn-sm btn-outline--primary addBtn"><i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/table-sortable.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/admin/js/table-sortable.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var modal = $('#locationModal');

            const imagePath = @json(route('home') . '/' . getFilePath('location'));

            $('.editBtn').on('click', function() {
                modal.find('form')[0].reset();
                modal.find('form').attr('action', $(this).data('route'));
                modal.find('input[name=name]').val($(this).data('name'));
                modal.find('.modal-title').text(`@lang('Add Location')`);

                var image = $(this).data('image');

                if (image) {
                    var imageUrl = `${imagePath}/${image}`;
                } else {
                    var imageUrl = '{{ getImage(null, getFileSize('location')) }}';
                }

                $(".image-upload-preview").css({
                    "background-image": "url('" + imageUrl + "')"
                });


                $("#image-upload-input1").removeAttr("required");
            });

            $('.addBtn').on('click', function() {
                modal.find('form')[0].reset();
                modal.find('form').attr('action', $(this).data('route'));
                modal.find('.modal-title').text(`@lang('Add Location')`);

                var imageUrl = '{{ getImage(null, getFileSize('location')) }}';
                $(".image-upload-preview").css({
                    "background-image": "url('" + imageUrl + "')"
                });
                $("#image-upload-input1").prop("required", true);
            });

        })(jQuery);

        function updateSortOrder(sorting) {
            var action = "{{ route('admin.location.sort') }}";
            var csrf = "{{ csrf_token() }}";
            sortOrderAction(sorting, action, csrf);
        }
    </script>
@endpush
