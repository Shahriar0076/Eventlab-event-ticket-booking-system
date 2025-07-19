<div class="modal fade custom--modal share-modal" id="shareModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close cursor-pointer" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <h4>@lang('Share Event')</h4>
                    <p>@lang('Join us and share the excitement at our upcoming event')</p>
                </div>
                <div class="share-modal-content">
                    <ul class="event-share-list">
                        <li class="event-share__item">
                            <a href="https://www.youtube.com/" class="event-share__link flex-center facebook" target="_blank">
                                <i class="fab fa-facebook"></i>
                            </a>
                        </li>
                    
                        <li class="event-share__item">
                            <a href="https://twitter.com/?lang=en" class="event-share__link flex-center twitter" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </li>
                        <li class="event-share__item">
                            <a href="https://twitter.com/?lang=en" class="event-share__link flex-center pinterest" target="_blank">
                                <i class="fab fa-pinterest"></i>
                            </a>
                        </li>
                        <li class="event-share__item">
                            <a href="https://www.instagram.com/" class="event-share__link flex-center linkedin" target="_blank">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control form--control event-share-link-value" value="" readonly>
                        <button type="button" class="btn btn--base share-link-btn"><i class="far fa-copy"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@push('script')
    <script>
        "use strict";
        (function($) {

            $(".share-link-btn").on('click', function() {
                var inputField = $(".event-share-link-value");
                inputField.select();
                inputField[0].setSelectionRange(0, 99999);

                document.execCommand("copy");
                inputField[0].setSelectionRange(0, 0);

                $("#shareModal").modal("hide");
                notify("success", "Link copied to clipboard");
            });

            $(".shareEventBtn").on('click', function() {
                let title    = $(this).data('title');
                let imageUrl = $(this).data('image-url');
                let url      = $(this).data('url');

                $(".event-share__link.facebook").attr("href",`https://www.facebook.com/sharer/sharer.php?u=${url}`);
                $(".event-share__link.twitter").attr("href",`https://twitter.com/intent/tweet?text=${title}&amp;url=${url}`);
                $(".event-share__link.pinterest").attr("href",`https://pinterest.com/pin/create/bookmarklet/?media=${imageUrl}&url=${url}`);
                $(".event-share__link.linkedin").attr("href",`http://www.linkedin.com/shareArticle?mini=true&amp;url=${url}`);

                $(".event-share-link-value").val(url);

                $("#shareModal").modal("show");
            });
        })(jQuery);
    </script>
@endpush
