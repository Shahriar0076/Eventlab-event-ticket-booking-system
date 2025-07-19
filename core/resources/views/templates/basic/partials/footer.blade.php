@php
    $contact = getContent('contact_us.content', true);
    $subscribe = getContent('subscribe.content', true);
    $socialIcons = getContent('social_icon.element');
    $categories = App\Models\Category::active()->orderBy('sort_order')->latest()->limit(4)->get();
    $policyPages = getContent('policy_pages.element', false, null, true);
@endphp

<footer class="footer-area">
    <div class="py-70">
        <div class="container">
            <div class="row gy-5">
                <div class="col-xl-3 col-md-6">
                    <div class="footer-item">
                        <div class="footer-item__logo">
                            <a href="{{ route('home') }}"> <img src="{{ siteLogo('dark') }}" alt="image"></a>
                        </div>
                        <p class="footer-item__desc"> @php echo @$contact->data_values->website_footer @endphp </p>

                        <div class="social-list-wrapper">
                            <h6 class="social-list-wrapper__title"> @lang('Follow Us') </h6>
                        </div>

                        <ul class="social-list">
                            @foreach ($socialIcons as $socialIcon)
                                <li class="social-list__item">
                                    <a href="{{ @$socialIcon->data_values->url }}" class="social-list__link flex-center"
                                        target="_blank">
                                        @php echo @$socialIcon->data_values->social_icon @endphp
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-1 d-none d-xl-block"></div>
                @php
                    $pages = App\Models\Page::where('tempname', $activeTemplate)
                        ->where('is_default', Status::NO)
                        ->get();
                @endphp
                <div class="col-xl-2 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title"> @lang('Quick Link')</h5>
                        <ul class="footer-menu">
                            <li class="footer-menu__item">
                                <a class="footer-menu__link" href="{{ route('home') }}">
                                    @lang('Home')
                                </a>
                            </li>

                            @foreach ($pages as $k => $data)
                                <li class="footer-menu__item"><a class="footer-menu__link"
                                        href="{{ route('pages', [$data->slug]) }}">
                                        {{ __($data->name) }} </a>
                                </li>
                            @endforeach
                            <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('blogs') }}">
                                    @lang('Blogs') </a>
                            </li>
                            <li class="footer-menu__item"><a class="footer-menu__link" href="{{ route('contact') }}">
                                    @lang('Contact') </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-sm-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('Categories')</h5>
                        <ul class="footer-menu">
                            @foreach ($categories as $category)
                                <li class="footer-menu__item"><a
                                        href="{{ route('event.index', ['category' => $category->slug]) }}"
                                        class="footer-menu__link"> {{ __($category->name) }} </a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 ">
                    <div class="footer-item">
                        <h5 class="footer-item__title"> {{ __(@$subscribe->data_values->title) }} </h5>
                        <form class="subscribe-form" id="subscribeForm">
                            @csrf
                            <div class="input-group subscribe">
                                <input type="email" class="form--control form-control" id="subscriber" name="email"
                                    autocomplete="off" placeholder="Enter your email">
                                <button type="submit" class="btn btn--base"> @lang('Subscribe') </button>
                            </div>
                        </form>
                        <p class="footer-item__desc mt-4"> {{ __(@$subscribe->data_values->description) }} </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-footer">
        <div class="container">
            <div class="row gy-3">
                <div class="col-md-12">
                    <div
                        class="d-flex flex-wrap justify-content-center justify-content-md-between flex-column flex-md-row align-items-center gap-2">
                        <div class="bottom-footer-text">
                            ©{{ date('Y') }} <a href="{{ route('home') }}">{{ __(gs('site_name')) }}.</a>
                            @lang('All Right Reserved').
                        </div>
                        <div class="d-flex gap-3 flex-wrap">
                            @foreach ($policyPages as $policy)
                                <a class="text--base"
                                    href="{{ route('policy.pages', [slug(@$policy->data_values->title), $policy->id]) }}">
                                    {{ __(@$policy->data_values->title) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

@push('script')
    <script>
        "use strict";
        (function($) {
            var form = $("#subscribeForm");
            form.on('submit', function(e) {
                e.preventDefault();
                var data = form.serialize();
                $.ajax({
                    url: `{{ route('subscribe') }}`,
                    method: 'post',
                    data: data,
                    success: function(response) {
                        if (response.success) {
                            form.find('input[name=email]').val('');
                            form.find('button[type=submit]').attr('disabled', false);
                            notify('success', response.message);
                        } else {
                            notify('error', response.message);
                        }
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
