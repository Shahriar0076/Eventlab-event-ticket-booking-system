@extends($activeTemplate . 'layouts.app')
@section('panel')
    @if (auth()->check())
        @include($activeTemplate . 'partials.user_header')
    @else
        @include($activeTemplate . 'partials.organizer_header')
    @endif
    @include($activeTemplate . 'partials.breadcrumb')

    <section class="dashboard-section section-bg-two py-70">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <span class="sidebar_menu_btn d-lg-none d-inline-flex"><i class="fas fa-bars"></i></span>
                    <div class="dashboard-wrapper">
                        <div class="dashboard">
                            <div class="dashboard__inner flex-wrap">
                                <!-- Sidebar Menu -->
                                @include($activeTemplate . 'partials.sidenav')
                                <!-- Dashboard Right -->
                                <div class="dashboard__right">
                                    <!-- Dashboard Body-->
                                    <div class="dashboard-body">
                                        <!-- Dashboard Card -->
                                        @yield('content')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    @include($activeTemplate . 'partials.footer')
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            var inputElements = $('[type=text],[type=password],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {

                if (element.hasAttribute('required')) {
                    $(element).closest('.form-group').find('label').addClass('required');
                }

            });


            $('.showFilterBtn').on('click', function() {
                $('.responsive-filter-card').slideToggle();
            });


            Array.from(document.querySelectorAll('table')).forEach(table => {
                let heading = table.querySelectorAll('thead tr th');
                Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
                    Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                        colum.setAttribute('data-label', heading[i].innerText)
                    });
                });
            });

            function formatState(state) {
                if (!state.id) return state.text;
                let gatewayData = $(state.element).data();
                return $(
                    `<div class="d-flex gap-2">${gatewayData.imageSrc ? `<div class="select2-image-wrapper"><img class="select2-image" src="${gatewayData.imageSrc}"></div>` : '' }<div class="select2-content"> <p class="select2-title">${gatewayData.title}</p><p class="select2-subtitle">${gatewayData.subtitle}</p></div></div>`
                );
            }

            $('.select2').each(function(index, element) {
                $(element).select2({
                    templateResult: formatState,
                    minimumResultsForSearch: "-1"
                });
            });

            $('.select2-searchable').each(function(index, element) {
                $(element).select2({
                    templateResult: formatState,
                    minimumResultsForSearch: "1"
                });
            });


            $('.select2-basic').each(function(index, element) {
                $(element).select2({
                    dropdownParent: $(element).closest('.select2-parent')
                });
            });

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });

        })(jQuery);
    </script>
@endpush
