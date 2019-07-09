(function ($) {
  /**
  * Get CSS Breakpoints
  * Usage: window.breakpoint gives you the actual breakpoint
  * E.g.: if(window.breakpoint == 'mobile') { Your Code }
  */
  Drupal.behaviors.getBreakpointsFromCSS = {
    attach: function (context, settings) {
      var breakpoint;
      var breakpoint_refreshValue;
      breakpoint_refreshValue = function () {
        window.breakpoint = window.getComputedStyle(document.querySelector('html'), ':before').getPropertyValue('content').replace(/\"/g, '');
      };

      $(window).resize(function () {
        breakpoint_refreshValue();
      }).resize();
    }
  };

  // Superfish Accordion ActiveTrail Fix
  // Drupal.behaviors.accordionActiveTrail = {
  //    attach: function (context, settings) {
  //      $('#superfish-main', context).on('click', function (){
  //        $('.sf-menu.sf-accordion .active-trail', context)
  //          .last()
  //          .parents('ul.sf-hidden')
  //          .css('display', 'block')
  //          .parent('li.active-trail')
  //          .addClass('sf-expanded');
  //      });
  //    }
  //  };


  /*
  * Animation of numbver count
  * - Animated Numbers
  */
  Drupal.behaviors.animatedNumbers = {
    attach: function (context, settings) {

      /* $('.count').each(function () {
          $(this).prop('Counter',0).animate({
              Counter: $(this).text()
          }, {
              duration: 4000,
              easing: 'swing',
              step: function (now) {
                  $(this).text(Math.ceil(now));
              }
          });
      });*/
    }
  };

  /*
  * Language Selector (Function 1)
  * - Show in click
  */
  Drupal.behaviors.showSwitcher = {
    attach: function (context, settings) {
      $('.current-language-switch', context).click(function () {
        $('#block-languageswitchercontent').addClass('expanded');
        $('.current-language-switch').addClass('closed');
      });
    }
  };

  Drupal.behaviors.showSwitcherInt = {
    attach: function (context, settings) {
      $('.current-language-switch', context).click(function () {
        $('#block-languageswitcher').addClass('expanded');
        $('.current-language-switch').addClass('closed');
      });
    }
  };

  /*
  * Language Selector (Function 2)
  * - Hide Language Selector onclick on document
  */
  Drupal.behaviors.hideSwitcher = {
    attach: function (context, settings) {
      $(window).click(function (event) {
        if (!($('.current-language-switch span', context).is(event.target))) {
          $('#block-languageswitchercontent').removeClass('expanded');
          if ($('.current-language-switch').hasClass('closed')) {
            $('.current-language-switch').removeClass('closed');
          }
        }
      });

    }
  };

  Drupal.behaviors.hideSwitcherInt = {
    attach: function (context, settings) {
      $(window).click(function (event) {
        if (!($('.current-language-switch span', context).is(event.target))) {
          $('#block-languageswitcher').removeClass('expanded');
          if ($('.current-language-switch').hasClass('closed')) {
            $('.current-language-switch').removeClass('closed');
          }
        }
      });

    }
  };


  /*
  * Language Selector (Function 2)
  * - Toggle Language Selector onclick on document
  */
  Drupal.behaviors.toggleSwitcher = {
    attach: function (context, settings) {
      $(window).click(function (event) {
        if ($('#block-languageswitchercontent ul li a', context).is(event.target)) {
          $('.current-language-switch').removeClass('closed');
        }
      });

    }
  };

  Drupal.behaviors.toggleSwitcherInt = {
    attach: function (context, settings) {
      $(window).click(function (event) {
        if ($('#block-languageswitcher ul li a', context).is(event.target)) {
          $('.current-language-switch').removeClass('closed');
        }
      });

    }
  };

  /**
  * - Displays search form
  */

  Drupal.behaviors.showSearch = {
    attach: function (context, settings) {
      $('.search-block-form form input', context).click(function () {
        $('.search-block-form form').addClass('search-expand');
      });
    }
  };

  /**
  * - Hides search form
  */
  Drupal.behaviors.hideSearch = {
    attach: function (context, settings) {
      $(window).click(function (event) {
        if (!($('.search-block-form form input', context).is(event.target))) {
          $('.search-block-form form').removeClass('search-expand');
        }
      });
    }
  };

  /**
  * - Displays current year using PHP
  */
  Drupal.behaviors.currentYear = {
    attach: function (context, settings) {
      var year = new Date().getFullYear();
      $('.copy-year').html('Ⓒ ' + year);
    }
  };


  /**
  * - Creates snow
  */
  Drupal.behaviors.snow = {
    attach: function (context, settings) {

        var collectOnMe = document.querySelectorAll('.collectonme'),
                buttons = document.getElementsByTagName("input");

        for(var i = 0; i < collectOnMe.length; i++) {
            collectOnMe[i].style.display = "none";
        }

      // default options
        var testContainer = document.querySelector('.site-footer-top'), testContainerIsSnowing = true;

        snowFall.snow(testContainer);

        testContainer.addEventListener('click', function (e) {
            testContainerIsSnowing = !testContainerIsSnowing;

            if (!testContainerIsSnowing) {
                snowFall.snow(testContainer, "clear");
            }
            else {
                snowFall.snow(testContainer);
            }
        });
    }
  };


  /**
  * - On Scroll, remove the opened menu item
  */
  Drupal.behaviors.scrollCloseElements = {
    attach: function (context, settings) {

      checkOrientationMenu = function () {
        $('#block-languageswitcher').removeClass('expanded');
        $('.current-language-switch').removeClass('closed');
        $('#superfish-main-accordion').removeClass('sf-expanded');
        $('#superfish-main-accordion').hide();
        $('#superfish-main-toggle').removeClass('sf-expanded');
      };

      $(window).scroll(function () {
        checkOrientationMenu();
      });
    }
  };


  /*
  * Sticky Header
  * - On scroll to certain length header beomes sticky
  */
  Drupal.behaviors.stickyHeader = {
    attach: function (context, settings) {
      supaScroll = function () {
        if ($(window).scrollTop() >= 50) {
          $('.header').addClass('sticky');
        }
        else {
          $('.header').removeClass('sticky');
        }
      };
      $(window).scroll(function () {
        supaScroll();
      });
    }
  };



  /*
  * Slow scroll to Anchor
  * - https://css-tricks.com/snippets/jquery/smooth-scrolling/
  */
  Drupal.behaviors.anchorScroll = {
    attach: function (context, settings) {
      var $headerHeight = $('#header').height();

      if ($('ul.on-the-move').length > 0) {
        $(function () {
          $('a[href*="#"]:not([href="#"])', context).click(function (e) {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
              var target = $(this.hash);
              target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
              if (target.length) {
                $('html, body').animate({
                  scrollTop: target.offset().top - $headerHeight}, 1000);
                  // Use this rather than return false
                e.preventDefault();
              }
            }
          });
        });
      }
      // on page load, check if the URL has a hashm then scroll to it
      $(document).ready(function () {
        var elem = $(window.location.hash);
        if (elem.length) {
          var target = elem;
          $('html, body').animate({
            scrollTop: target.offset().top - $headerHeight}, 1000);
        }
      });
    }
  };

  // Owl Carousel;
  Drupal.behaviors.frontOwl = {
    attach: function (context, settings) {
      $('.owl-carousel', context).owlCarousel({
        items: 1,
        loop: true,
        animateOut: 'fadeOut',
        autoplay: true,
        autoplayTimeout: 5000
      });
    }
  };


  // Front Page Banner resizer;
  Drupal.behaviors.bannerFrontResize = {
    attach: function (context, settings) {

      resizeFrontBanner = function () {
        var bannerOuterHeight = $('.path-frontpage .banner-region .view-display-id-embed_1 .view-content').outerHeight();
        var bannerHeight = bannerOuterHeight - 62;
        $('.path-frontpage .banner-region').css('min-height', bannerHeight);
          // console.log(bannerHeight);
      }

      $(document).ready(function () {
        if ($('.path-frontpage .banner-region', context)) {
          setTimeout(function () {
            resizeFrontBanner();
          },1000);
        }
      });

      $(window).resize(function () {
        if ($('.path-frontpage .banner-region', context)) {
          resizeFrontBanner();
        }
      });
    }
  };


})(jQuery);
