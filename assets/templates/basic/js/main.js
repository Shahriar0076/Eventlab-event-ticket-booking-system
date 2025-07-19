(function ($) {
  "use strict";

  // ==========================================
  //      Start Document Ready function
  // ==========================================
  $(document).ready(function () {
    // ============== Header Hide Click On Body Js Start ========

    $(".header-button").on("click", function () {
      $(".body-overlay").toggleClass("show");
    });
    $(".body-overlay").on("click", function () {
      $(".header-button").trigger("click");
      $(this).removeClass("show");
    });

    // =============== Header Hide Click On Body Js End =========
    $(".delete-btn").on("click", function () {
      $(this).closest(".event-speaker-item").remove();
    });

    // ========================== Header Hide Scroll Bar Js Start =====================
    $(".navbar-toggler.header-button").on("click", function () {
      $("body").toggleClass("scroll-hide-sm");
    });
    $(".body-overlay").on("click", function () {
      $("body").removeClass("scroll-hide-sm");
    });
    // ========================== Header Hide Scroll Bar Js End =====================

    // Sidebar Icon & Overlay js 
    $(".sidebar_menu_btn").on("click", function () {
      $(".sidebar-menu").addClass('show');
      $(".sidebar-overlay").addClass('show');
    });
    $(".sidebar-menu__close, .sidebar-overlay").on("click", function () {
      $(".sidebar-menu").removeClass('show');
      $(".sidebar-overlay").removeClass('show');
    });
    // Sidebar Icon & Overlay js 

    $(document).ready(function () {
      $(".popular-btn.wishlist").on("click", function (e) {
        e.preventDefault(); // Prevent default link behavior
        var btn = $(this);

        var url = btn.data("url"); // Get the URL from the clicked link

        $.ajax({
          url: url,
          type: "GET",
          success: function (response) {
            if (response.success) {
              notify("success", response.message);
              // Toggle wishlist-show class
              btn.toggleClass("wishlist-show");

              // Update like count
              var likeCount = response.likeCount;
              $('[data-toggle="tooltip"]').tooltip();
              if (likeCount == 0) {
                btn.tooltip("dispose");
              } else {
                btn.attr(
                  "data-bs-original-title",
                  likeCount + (likeCount > 0 ? " liked" : "")
                );
              }

              // Update heart icon class based on response
              if (response.liked) {
                btn.find("i").removeClass("far").addClass("fas");
              } else {
                btn.find("i").removeClass("fas").addClass("far");
              }
            } else {
              notify("error", response.message);
            }
          },
          error: function (xhr, status, error) {
            // Handle error if any
            console.error(xhr.responseText);
          },
        });
      });
    });

    // tooltip js
    const tooltipTriggerList = document.querySelectorAll(
      '[data-bs-toggle="tooltip"]'
    );
    const tooltipList = [...tooltipTriggerList].map(
      (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
    );
    // tooltip js end

    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js Start =====================
    $(".dropdown-item").on("click", function () {
      $(this).closest(".dropdown-menu").addClass("d-block");
    });
    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js End =====================

    // ========================== Add Attribute For Bg Image Js Start =====================
    $(".bg-img").css("background", function () {
      var bg = "url(" + $(this).data("background-image") + ")";
      return bg;
    });
    // ========================== Add Attribute For Bg Image Js End =====================

    // ========================== add active class to ul>li top Active current page Js Start =====================

    // ========================== add active class to ul>li top Active current page Js End =====================

    // ================== Password Show Hide Js Start ==========
    $(".toggle-password").on("click", function () {
      $(this).toggleClass(" fa-eye-slash");
      var input = $($(this).attr("id"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
    // =============== Password Show Hide Js End =================

    // ============23. magnific popup Start ======================================

    // ================== Sidebar Menu Js Start ===============
    // Sidebar Dropdown Menu Start
    $(".has-dropdown > a").on('click', function () {
      $(".sidebar-submenu").slideUp(200);
      if ($(this).parent().hasClass("active")) {
        $(".has-dropdown").removeClass("active");
        $(this).parent().removeClass("active");
      } else {
        $(".has-dropdown").removeClass("active");
        $(this).next(".sidebar-submenu").slideDown(200);
        $(this).parent().addClass("active");
      }
    });

    // Sidebar Dropdown Menu End
    // Sidebar Icon & Overlay js
    $(".event-filter__button").on("click", function () {
      $(".grid-sidebar").addClass("show-sidebar");
      $(".sidebar-overlay").addClass("show");
    });
    $(".sidebar-menu__close, .sidebar-overlay").on("click", function () {
      $(".grid-sidebar").removeClass("show-sidebar");
      $(".sidebar-overlay").removeClass("show");
    });
    // Sidebar Icon & Overlay js
    // ===================== Sidebar Menu Js End =================

    //=========  map hide js =========

    $(".details__map-title").on("click", function () {
      $(".map").toggleClass("map-show");
    });

    //=========  map hide js =========

    //==================== countdown js start here ===================

    let duration = "2023-11-31 18:55:21";
    if (duration) {
      const targetDate = new Date(duration).getTime();
      const interval = setInterval(function () {
        const currentDate = new Date().getTime();
        const remainingTime = targetDate - currentDate;
        if (remainingTime <= 0) {
          clearInterval(interval);
        } else {
          const days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
          const hours = Math.floor(
            (remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
          );
          const minutes = Math.floor(
            (remainingTime % (1000 * 60 * 60)) / (1000 * 60)
          );
          const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
          $(".box__days").html(`${days}`);
          $(".remaining-time__hrs").html(`${hours}`);
          $(".remaining-time__min").html(`${minutes}`);
          //  $('.remaining-time__sec').html(`${seconds}`);
        }
      }, 1000);
    }
    //==================== countdown js end here ====================

    //=================== search box wrapper js start ===================

    $(".search-box-wrapper").on("click", function (event) {
      event.stopPropagation(); // Prevent the click event from propagating to the body
      $(".search-box").toggleClass("show-box");
    });
    $(".search-box").on("click", function (event) {
      event.stopPropagation(); // Prevent the click event from propagating to the body
      $(".search-box").addClass("show-box");
    });
    $("body").on("click", function () {
      $(".search-box").removeClass("show-box");
    });
    //=================== search box wrapper js end ===================

    // ==================== Custom Sidebar Dropdown Menu Js Start ==================
    $(".has-submenu").on("click", function (event) {
      event.preventDefault(); // Prevent the default anchor link behavior

      // Check if this submenu is currently visible
      var isOpen = $(this).find(".sidebar-submenu").is(":visible");

      // Hide all submenus initially
      $(".sidebar-submenu").slideUp();

      // Remove the "active" class from all li elements
      $(".sidebar-menu__item").removeClass("active");

      // If this submenu was not open, toggle its visibility and add the "active" class to the clicked li
      if (!isOpen) {
        $(this).find(".sidebar-submenu").slideToggle(500);
        $(this).addClass("active");
      }
    });
    // ==================== Custom Sidebar Dropdown Menu Js End ==================
  });

  // ========================= Preloader Js Start =====================
  $(window).on("load", function () {
    $(".preloader").fadeOut();
  });
  // ========================= Preloader Js End=====================

  // ========================= Header Sticky Js Start ==============
  $(window).on("scroll", function () {
    if ($(window).scrollTop() >= 300) {
      $(".header").addClass("fixed-header");
    } else {
      $(".header").removeClass("fixed-header");
    }
  });
  // ========================= Header Sticky Js End===================

  //============================ Scroll To Top Icon Js Start =========
  var btn = $(".scroll-top");

  $(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
      btn.addClass("show");
    } else {
      btn.removeClass("show");
    }
  });

  btn.on("click", function (e) {
    e.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, "300");
  });
  //========================= Scroll To Top Icon Js End ======================

  //========================= copy shared link to clipboard ======================

  //========================= copy shared link to clipboard ======================

  // tooltip
  $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });

  /*==================== custom dropdown select js ====================*/
  $(".custom--dropdown > .custom--dropdown__selected").on("click", function () {
    $(this).parent().toggleClass("open");
  });
  $(".custom--dropdown > .dropdown-list > .dropdown-list__item").on(
    "click",
    function () {
      $(
        ".custom--dropdown > .dropdown-list > .dropdown-list__item"
      ).removeClass("selected");
      $(this)
        .addClass("selected")
        .parent()
        .parent()
        .removeClass("open")
        .children(".custom--dropdown__selected")
        .html($(this).html());

      // Get the selected value
      var selectedValue = $(this).data('url'); // Assuming data-code attribute contains the value
      // Call onchange function
      changeLanguage(selectedValue);

    }
  );
  $(document).on("keyup", function (evt) {
    if ((evt.keyCode || evt.which) === 27) {
      $(".custom--dropdown").removeClass("open");
    }
  });
  $(document).on("click", function (evt) {
    if (
      $(evt.target).closest(".custom--dropdown > .custom--dropdown__selected")
        .length === 0
    ) {
      $(".custom--dropdown").removeClass("open");
    }
  });

  function changeLanguage(selectedValue) {
    // Do something with the selected value
    window.location.href = selectedValue;
  }
  
  function adjustDisplay() {
    var $detailButtons = $('.detail-buttons');
    var $ticketDetailActions = $('.ticket-detail-actions');

    // Check if there are exactly 3 buttons in detail-buttons div
    if ($detailButtons.children('a, button').length === 3 && $(window).width() <= 500) {
      $ticketDetailActions.css('display', 'block');
      $detailButtons.addClass('mt-3');
    } else {
      $ticketDetailActions.css('display', 'flex');
      $detailButtons.removeClass('mt-3');
    }
  }

  // Run the function on document ready
  adjustDisplay();

  // Run the function whenever the window is resized
  $(window).resize(function () {
    // Check if the current window width is less than or equal to 380px
    if ($(window).width() <= 500) {
      adjustDisplay();
    } else {
      // Reset the display property to 'flex' for larger screens
      $('.ticket-detail-actions').css('display', 'flex');
      $('.detail-buttons').removeClass('mt-3');
    }
  });

})(jQuery);
