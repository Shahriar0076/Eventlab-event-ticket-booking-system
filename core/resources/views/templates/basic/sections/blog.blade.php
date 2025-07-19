@php
    $blogContent = getContent('blog.content', true);
    $blogs = getContent('blog.element', false);
    $showAllBtn = $blogs->count() > 4;
@endphp

<section class="blog-section py-70">
    <div class="container">
        <div class="row gy-3 gy-md-4 justify-content-center">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="style-left">
                        <span class="section-heading__subtitle"> {{ __(@$blogContent->data_values->title) }} </span>
                        <h3 class="section-heading__title">{{ __(@$blogContent->data_values->heading) }} </h3>
                    </div>
                    <a href="{{ route('blogs') }}" class="section-button"> @lang('View all') <span class="section-icon"><i
                                class="la la-arrow-right"></i></span></a>
                </div>
            </div>
            <div class="@if ($blogs->count() > 1) col-xl-6 @else col-xl-12 @endif">
                @php
                    $firstBlog = @$blogs->first();
                @endphp
                <div class="blog-item blog-item-fit">
                    <div class="blog-item__thumb">
                        <a href="{{ route('blog.details', @$firstBlog->slug) }}" class="blog-item__thumb-link">
                            <img src="{{ frontendImage('blog', @$firstBlog->data_values->blog_image, '830x420') }}"
                                class="fit-image" alt="image">
                        </a>
                    </div>
                    <div class="blog-content">
                        <h5 class="blog-item__title"><a href="{{ route('blog.details', @$firstBlog->slug) }}"
                                class="blog-item__title-link border-effect">{{ __(strLimit(@$firstBlog->data_values->title, 80)) }}</a>
                        </h5>
                        <p class="blog-item__desc"> {{ __(strLimit(@$firstBlog->data_values->short_description, 160)) }}
                        </p>
                        <ul class="text-list flex-align gap-3">
                            <li class="text-list__item fs-14">
                                <span class="text-list__item-icon fs-12 me-1 text--base"><i
                                        class="fas fa-calendar-alt"></i></span>
                                {{ showDateTime($firstBlog->created_at, 'd M Y') }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @if ($blogs->count() > 1)
                <div class="col-xl-6">
                    <div class="row gy-4 justify-content-center">
                        @foreach ($blogs->skip(1)->take(3) as $blog)
                            <div class="col-xl-12 col-sm-6 ">
                                <div class="blog-item item-two blog-section-item">
                                    <div class="blog-item__thumb">
                                        <a href="{{ route('blog.details', @$blog->slug) }}"
                                            class="blog-item__thumb-link">
                                            <img src="{{ frontendImage('blog', @$blog->data_values->blog_image, '415x210', thumb: true) }}"
                                                class="fit-image" alt="image">
                                        </a>
                                    </div>
                                    <div class="blog-content">
                                        <h5 class="blog-item__title">
                                            <a href="{{ route('blog.details', @$blog->slug) }}"
                                                class="blog-item__title-link border-effect">
                                                {{ __(strLimit(@$blog->data_values->title, 70)) }} </a>
                                        </h5>

                                        <p class="blog-item__desc">
                                            {{ __(strLimit(@$blog->data_values->short_description, 80)) }} </p>
                                        <ul class="text-list flex-align gap-3">
                                            <li class="text-list__item fs-14"> <span
                                                    class="text-list__item-icon fs-12 me-1 text--base"><i
                                                        class="fas fa-calendar-alt"></i></span>
                                                {{ showDateTime($blog->created_at, 'd M Y') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
