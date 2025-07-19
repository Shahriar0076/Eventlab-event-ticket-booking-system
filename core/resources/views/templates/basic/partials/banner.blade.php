@php
    $banner = getContent('banner.content', true);
    $categories = App\Models\Category::active()->orderBy('sort_order')->get();
    $locations = App\Models\Location::active()->orderBy('sort_order')->get();
@endphp

<section class="banner-section bg-img"
    data-background-image="{{ frontendImage('banner', @$banner->data_values->background_image, '1920x570') }}">
    <div class="banner-section__shape">
        <img src="{{ getImage($activeTemplateTrue . 'images/shapes/s-1.png') }}" alt="image">
    </div>
    <div class="banner-section__shape-two">
        <img src="{{ getImage($activeTemplateTrue . 'images/shapes/s-2.png') }}" alt="image">
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="banner-content">
                    <h5 class="banner-content__subtitle"> {{ __(@$banner->data_values->title) }} </h5>
                    <h1 class="banner-content__title"> {{ __(@$banner->data_values->heading) }}</h1>
                    <div class="banner-content__button">
                        <form action="{{ route('event.index') }}">
                            <div class="banner-form">
                                <div class="location">
                                    <span class="banner-form__icon"> <i class="las la-map-marker-alt"></i></span>
                                    <select name="location" class="custom-select" data-searchable="true">
                                        <option value=""> @lang('Select Location') </option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->slug }}"> {{ __($location->name) }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="all-category">
                                    <span class="banner-form__icon"> <i class="las la-layer-group"></i> </span>
                                    <select class="custom-select" data-searchable="true" name="category">
                                        <option value=""> @lang('Select Category') </option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->slug }}"> {{ __($category->name) }} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="search-btn">
                                    <button type="submit" class="btn btn--base">
                                        <span class="icon">
                                            <i class="las la-search"></i>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="banner-right">
                    <div class="banner-thumb">
                        <img src="{{ frontendImage('banner', @$banner->data_values->banner_image, '600x440') }}"
                            alt="image">
                    </div>
                    <div class="banner-thumb__shape"></div>
                    <div class="banner-thumb__shape-two">
                        <img src="{{ getImage($activeTemplateTrue . 'images/shapes/s-3.png') }}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
