(function ($) {
  "use strict";

  // ==========================================
  //      Start Document Ready function
  // ==========================================
  $(document).ready(function () {

    // ============== Header Hide Click On Body Js Start ========

    $('.header-button').on('click', function () {
      $('.body-overlay').toggleClass('show')
    });
    $('.body-overlay').on('click', function () {
      $('.header-button').trigger('click')
      $(this).removeClass('show');
    });

    // =============== Header Hide Click On Body Js End =========    

    // ========================== Header Hide Scroll Bar Js Start =====================
    $('.navbar-toggler.header-button').on('click', function () {
      $('body').toggleClass('scroll-hide-sm')
    });
    $('.body-overlay').on('click', function () {
      $('body').removeClass('scroll-hide-sm')
    });
    // ========================== Header Hide Scroll Bar Js End =====================

    /* wishlist js start here */
    $('.popular-btn.wishlist').on('click', function () {
      $(this).toggleClass('wishlist-show')
    })

    // tooltip js 
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    // tooltip js end 


    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js Start =====================
    $('.dropdown-item').on('click', function () {
      $(this).closest('.dropdown-menu').addClass('d-block')
    });
    // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js End =====================



    // ========================== Add Attribute For Bg Image Js Start =====================
    $(".bg-img").css('background', function () {
      var bg = ('url(' + $(this).data("background-image") + ')');
      return bg;
    });
    // ========================== Add Attribute For Bg Image Js End =====================

    // ========================== add active class to ul>li top Active current page Js Start =====================
    function dynamicActiveMenuClass(selector) {
      let FileName = window.location.pathname.split("/").reverse()[0];

      selector.find("li").each(function () {
        let anchor = $(this).find("a");
        if ($(anchor).attr("href") == FileName) {
          $(this).addClass("active");
        }
      });
      // if any li has active element add class
      selector.children("li").each(function () {
        if ($(this).find(".active").length) {
          $(this).addClass("active");
        }
      });
      // if no file name return
      if ("" == FileName) {
        selector.find("li").eq(0).addClass("active");
      }
    }
    if ($('ul').length) {
      dynamicActiveMenuClass($('ul'));
    }
    // ========================== add active class to ul>li top Active current page Js End =====================

    // ==================== Dashboard User Profile Dropdown Start ==================
    $('.user-info__button').on('click', function (event) {
      event.stopPropagation(); // Prevent the click event from propagating to the body
      $('.user-info-dropdown').toggleClass('show');
    });

    $('.user-info-dropdown__link').on('click', function (event) {
      event.stopPropagation(); // Prevent the click event from propagating to the body
      $('.user-info-dropdown').addClass('show')
    });

    $('body').on('click', function () {
      $('.user-info-dropdown').removeClass('show');
    })
    // ==================== Dashboard User Profile Dropdown End ==================

    // Sidebar Icon & Overlay js 
    $(".dashboard-body__bar-icon").on("click", function () {
      $(".sidebar-menu").addClass('show-sidebar');
      $(".sidebar-overlay").addClass('show');
    });
    $(".sidebar-menu__close, .sidebar-overlay").on("click", function () {
      $(".sidebar-menu").removeClass('show-sidebar');
      $(".sidebar-overlay").removeClass('show');
    });
    // Sidebar Icon & Overlay js 

    // ================== Password Show Hide Js Start ==========
    $(".toggle-password").on('click', function () {
      $(this).toggleClass(" fa-eye-slash");
      var input = $($(this).attr("id"));
      if (input.attr("type") == "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
    // =============== Password Show Hide Js End =================

    // ================== Sidebar Menu Js Start ===============
    // Sidebar Dropdown Menu Start
    $(".has-dropdown > a").on('click', function () {
      $(".sidebar-submenu").slideUp(200);
      if (
        $(this)
          .parent()
          .hasClass("active")
      ) {
        $(".has-dropdown").removeClass("active");
        $(this)
          .parent()
          .removeClass("active");
      } else {
        $(".has-dropdown").removeClass("active");
        $(this)
          .next(".sidebar-submenu")
          .slideDown(200);
        $(this)
          .parent()
          .addClass("active");
      }
    });

    // Sidebar Dropdown Menu End
    // Sidebar Icon & Overlay js 
    $(".event-filter__button").on("click", function () {
      $(".grid-sidebar").addClass('show-sidebar');
      $(".sidebar-overlay").addClass('show');
    });
    $(".sidebar-menu__close, .sidebar-overlay").on("click", function () {
      $(".grid-sidebar").removeClass('show-sidebar');
      $(".sidebar-overlay").removeClass('show');
    });
    // Sidebar Icon & Overlay js 
    // ===================== Sidebar Menu Js End =================

    //=========  map hide js =========

    $(".details__map-title").on("click", function () {
      $(".map").toggleClass('map-show');
    });

    //=========  map hide js =========

    // const activePageLink = $('.page-list .nav-item.active');
    // Select the last active nav-item
    const activePageLinkIndex = $('.page-list .nav-item.active').index();
    $('.page-list .nav-item').each(function (index, element) {
      if (index < activePageLinkIndex) $(element).addClass('active');
    })


    //====== quantity cart css start here ==============
    $(document).ready(function () {
      const minus = $('.qtyminus');
      const plus = $('.qtyplus');
      const input = $('.qty input');

      minus.on('click', function () {
        e.preventDefault();
        var value = input.val();
        if (value > 1) {
          value--;
        }
        input.val(value);
      });

      plus.on('click', function () {
        e.preventDefault();
        var value = input.val();
        value++;
        input.val(value);
      });


    });
    //====== quantity cart css end here ===============

    //==================== countdown js start here ===================

    let duration = "2023-11-31 18:55:21"
    if (duration) {
      const targetDate = new Date(duration).getTime();
      const interval = setInterval(function () {
        const currentDate = new Date().getTime();
        const remainingTime = targetDate - currentDate;
        if (remainingTime <= 0) {
          clearInterval(interval);
        } else {
          const days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
          const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
          const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
          $('.box__days').html(`${days}`);
          $('.remaining-time__hrs').html(`${hours}`);
          $('.remaining-time__min').html(`${minutes}`);
          //  $('.remaining-time__sec').html(`${seconds}`);
        }
      }, 1000);

    }
    //==================== countdown js end here ====================

    //=================== search box wrapper js start ===================

    $('.search-box-wrapper').on('click', function (event) {
      event.stopPropagation(); // Prevent the click event from propagating to the body
      $('.search-box').toggleClass('show-box');
    });
    $('.search-box').on('click', function (event) {
      event.stopPropagation(); // Prevent the click event from propagating to the body
      $('.search-box').addClass('show-box')
    });
    $('body').on('click', function () {
      $('.search-box').removeClass('show-box');
    })
    //=================== search box wrapper js end ===================


    // ==================== Custom Sidebar Dropdown Menu Js Start ==================
    $('.has-submenu').on('click', function (event) {
      event.preventDefault(); // Prevent the default anchor link behavior

      // Check if this submenu is currently visible
      var isOpen = $(this).find('.sidebar-submenu').is(':visible');

      // Hide all submenus initially
      $('.sidebar-submenu').slideUp();

      // Remove the "active" class from all li elements
      $('.sidebar-menu__item').removeClass('active');

      // If this submenu was not open, toggle its visibility and add the "active" class to the clicked li
      if (!isOpen) {
        $(this).find('.sidebar-submenu').slideToggle(500);
        $(this).addClass('active');
      }
    });
    // ==================== Custom Sidebar Dropdown Menu Js End ==================
  });

  // ========================= Preloader Js Start =====================
  $(window).on("load", function () {
    $('.preloader').fadeOut();
  })
  // ========================= Preloader Js End=====================

  // ========================= Header Sticky Js Start ==============
  $(window).on('scroll', function () {
    if ($(window).scrollTop() >= 300) {
      $('.header').addClass('fixed-header');
    }
    else {
      $('.header').removeClass('fixed-header');
    }
  });
  // ========================= Header Sticky Js End===================


  // ========================= Odometer Counter Up Js End ==========
  $(".counterup-item").each(function () {
    $(this).isInViewport(function (status) {
      if (status === "entered") {
        for (var i = 0; i < document.querySelectorAll(".odometer").length; i++) {
          var el = document.querySelectorAll('.odometer')[i];
          el.innerHTML = el.getAttribute("data-odometer-final");
        }
      }
    });
  });
  // ========================= Odometer Up Counter Js End =====================

  //============================ Scroll To Top Icon Js Start =========
  var btn = $('.scroll-top');

  $(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
      btn.addClass('show');
    } else {
      btn.removeClass('show');
    }
  });

  btn.on('click', function (e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: 0 }, '300');
  });
  //========================= Scroll To Top Icon Js End ======================

  $(".header-search-icon").on('click', function () {
    $(".search-box-wrapper").toggleClass('show-box');
  })

  $(document).on('click', function (e) {
    if (!$(e.target).closest('.search-box-wrapper').length &&
      !$(e.target).closest('.header-search-icon').length) {
      $('.search-box-wrapper').removeClass("show-box");
    }
  });

})(jQuery);
