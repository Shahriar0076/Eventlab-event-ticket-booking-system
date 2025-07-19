@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog py-120">
        <div class="container">

            <div class="row gy-4 justify-content-center blog-list">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item">
                            <div class="blog-item__thumb">
                                <a class="blog-item__thumb-link" href="{{ route('blog.details', @$blog->slug) }}">
                                    <img src="{{ frontendImage('blog', @$blog->data_values->blog_image, '415x210', thumb: true) }}"
                                        alt="blog_image">
                                </a>
                            </div>
                            <div class="blog-item__content">
                                <ul class="text-list inline">
                                    <li class="text-list__item">
                                        <span class="text-list__item-icon"><i class="fas fa-calendar-alt"></i></span>
                                        {{ showDateTime($blog->created_at, 'd M Y') }}
                                    </li>
                                </ul>
                                <h3 class="blog-item__title"><a class="blog-item__title-link"
                                        href="{{ route('blog.details', @$blog->slug) }}">{{ __(@$blog->data_values->title) }}</a>
                                </h3>
                                <a class="btn--simple" href="{{ route('blog.details', @$blog->slug) }}">@lang('Read More')
                                    <span class="btn--simple__icon"><i class="la la-arrow-right"></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            @if ($blogs->hasPages())
                <div class="mt-5">
                    {{ paginateLinks($blogs) }}
                </div>
            @endif
        </div>
    </section>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection


@push('script')
    <script>
        "use strict";
        (function($) {
            $(".cta-section").addClass("pb-70").removeClass('py-70');
        })(jQuery);
    </script>
@endpush
