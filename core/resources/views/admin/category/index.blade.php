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
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody class="sort">
                                @forelse($categories as $category)
                                    <tr class="sortable-table-row" data-id="{{ $category->id }}">
                                        <td>
                                            <div class="user">
                                                <div class="thumb"><img
                                                        src="{{ getImage(getFilePath('category') . '/' . $category->image, getFileSize('category')) }}"
                                                        alt="{{ __($category->name) }}" class="plugin_bg"></div>
                                                <span class="name">{{ __($category->name) }}</span>
                                            </div>
                                        </td>

                                        <td> {{ $category->events_count }} </td>
                                        <td> @php echo $category->statusBadge; @endphp </td>

                                        <td>
                                            <div class="button--group">
                                                <button type="button" data-id="{{ $category->id }}"
                                                    data-name="{{ $category->name }}" data-image="{{ $category->image }}"
                                                    data-route="{{ route('admin.category.store', $category->id) }}"
                                                    data-bs-toggle="modal" data-bs-target="#categoryModal"
                                                    class="btn btn-outline--primary editBtn btn-sm"><i
                                                        class="las la-pen"></i>@lang('Edit')</button>

                                                @if ($category->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-question="@lang('Are you sure to enable this category?')"
                                                        data-action="{{ route('admin.category.status', $category->id) }}"><i
                                                            class="la la-eye"></i>@lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-question="@lang('Are you sure to disable this category?')"
                                                        data-action="{{ route('admin.category.status', $category->id) }}"><i
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
                @if ($categories->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($categories) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="categoryModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Add New category')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"><span><i
                                class="las la-times"></i></span></button>
                </div>
                <form method="post" action="{{ route('admin.category.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Image')</label>
                            <x-image-uploader image="" class="w-100" type="category" :required=true />
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
    <button type="button" data-route="{{ route('admin.category.store') }}" data-bs-target="#categoryModal"
        data-bs-toggle="modal" class="btn btn-sm btn-outline--primary addBtn"><i
            class="las la-plus"></i>@lang('Add New')</button>
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

            var modal = $('#categoryModal');
            const imagePath = @json(route('home') . '/' . getFilePath('category'));

            $('.editBtn').on('click', function() {
                modal.find('form')[0].reset();
                modal.find('form').attr('action', $(this).data('route'));
                modal.find('input[name=name]').val($(this).data('name'));
                modal.find('.modal-title').text(`@lang('Update Category')`);

                var image = $(this).data('image');

                if (image) {
                    var imageUrl = `${imagePath}/${image}`;
                } else {
                    var imageUrl = '{{ getImage(null, getFileSize('category')) }}';
                }

                $(".image-upload-preview").css({
                    "background-image": "url('" + imageUrl + "')"
                });


                $("#image-upload-input1").removeAttr("required");
            });

            $('.addBtn').on('click', function() {
                modal.find('form')[0].reset();
                modal.find('form').attr('action', $(this).data('route'));
                modal.find('.modal-title').text(`@lang('Add Category')`);

                var imageUrl = '{{ getImage(null, getFileSize('category')) }}';
                $(".image-upload-preview").css({
                    "background-image": "url('" + imageUrl + "')"
                });
                $("#image-upload-input1").prop("required", true);
            });

        })(jQuery);

        function updateSortOrder(sorting) {
            var action = "{{ route('admin.category.sort') }}";
            var csrf = "{{ csrf_token() }}";
            sortOrderAction(sorting, action, csrf);
        }
    </script>
@endpush

@push('script')
    <script></script>
@endpush
