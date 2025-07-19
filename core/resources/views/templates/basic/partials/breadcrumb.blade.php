@php
    $breadcrumb = getContent('breadcrumb.content', true);
@endphp

<section class="breadcrumb bg-img"
    data-background-image="{{ frontendImage('breadcrumb', @$breadcrumb->data_values->image, '1920x120') }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="breadcrumb__wrapper">
                    <h2 class="breadcrumb__title"> {{ __($pageTitle) }}</h2>
                </div>
            </div>
        </div>
    </div>
</section>
