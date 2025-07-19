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
                                    <th scope="col">@lang('Event Title')</th>
                                    <th scope="col">@lang('User Name')</th>
                                    <th scope="col">@lang('Price')</th>
                                    <th scope="col">@lang('Quantity')</th>
                                    <th scope="col">@lang('Total')</th>
                                    <th scope="col">@lang('Payment Status')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ __($order->event->title) }}</td>
                                        <td><a
                                                href="{{ route('admin.users.detail', $order->user->id) }}">{{ __($order->user->username) }}</a>
                                        </td>
                                        <td>{{ gs('cur_sym') }}{{ $order->price }}</td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>{{ gs('cur_sym') }}{{ $order->total_price }}</td>
                                        <td>@php echo $order->paymentData @endphp</td>
                                        <td> @php echo $order->statusBadge @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.order.details', $order->id) }}"
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
                @if (@$orders->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($orders) }}
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
    <x-search-form placeholder="name, user" dateSearch='yes' />
@endpush
