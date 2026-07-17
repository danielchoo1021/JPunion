$(function () {

    // ---------------------------------------------- //
    // Navbar Shrinking Behavior
    // ---------------------------------------------- //
    $(window).scroll(function () {
        if ($(window).scrollTop() > 20) {
            $('nav.navbar').addClass('shrink');
        } else {
            $('nav.navbar').removeClass('shrink');
        }
    });


    // ---------------------------------------------- //
    // OWl Carousel
    // ---------------------------------------------- //
    $('.owl-carousel').owlCarousel({
        loop: false,
        nav: false,
        dots: true,
        navText: [
            "<i class='icon-arrow-left'></i>",
            "<i class='icon-arrow-right'></i>"
        ],
        margin: 15,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            757: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });


    // ---------------------------------------------- //
    // Label color changing on focus
    // ---------------------------------------------- //
    $('input, textarea').focus(function () {
        $(this).parent('label').addClass('active');
    });
    $('input, textarea').blur(function () {
        $(this).parent('label').removeClass('active');
    });

    // ---------------------------------------------- //
    // Date picker initialization
    // ---------------------------------------------- //
    $('.datepicker-here').datepicker({
        todayButton: new Date()
    });

    // ---------------------------------------------- //
    // Time picker initialization
    // ---------------------------------------------- //
    $('.timepicker').timepicki();

    // ---------------------------------------------- //
    // Time picker initialization
    // ---------------------------------------------- //
    $('body').scrollspy({
        target: '.navbar',
        offset: 80
    });

    // ---------------------------------------------- //
    // Preventing URL update on navigation link click
    // ---------------------------------------------- //
    $('.navbar-nav a, #scroll-down').bind('click', function (e) {
        var anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $(anchor.attr('href')).offset().top
        }, 1000);
        e.preventDefault();
    });

    // ---------------------------------------------- //
    // Scroll to top button
    // ---------------------------------------------- //
    $('#scrollTop').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1000);
    });

    $(window).scroll(function () {
        if ($(window).scrollTop() >= 1500) {
            $('#scrollTop').fadeIn();
        } else {
            $('#scrollTop').fadeOut();
        }
    });

    // ---------------------------------------------- //
    // Reservation Modal Opening & Closing
    // ---------------------------------------------- //
    $('#open-reservation').click(function (e) {
        e.preventDefault();
        $('.reservation-overlay').fadeIn();
        $('body').css({'overflow': 'hidden'});

        setTimeout(function () {
            $('#reservation-modal').addClass('is-visible');
        }, 100);
    });

    $('#close').click(function () {
        $('.reservation-overlay').fadeOut();
        setTimeout(function () {
            $('body').css('overflow', 'auto');
        }, 400);
        $('#reservation-modal').removeClass('is-visible');
    });


    // ---------------------------------------------- //
    // Lightbox initialization
    // ---------------------------------------------- //
    lightbox.option({
        'resizeDuration': 400,
        'fadeDuration': 400,
        'alwaysShowNavOnTouchDevices': true
    });

    // ---------------------------------------------- //
    // Booking form validation
    // ---------------------------------------------- //
    $('#booking-form, #booking-form-alternative').validate({
        messages: {
            name: 'please enter your name',
            email: 'please enter your email address',
            number: 'please enter your phone number',
            people: 'please enter how many people',
            date: 'please enter booking date',
            time: 'please enter booking time',
            request: 'please enter your special request'
        }
    });

    // ---------------------------------------------- //
    // Modal booking form validation
    // ---------------------------------------------- //
    $('#booking-form-alternative').validate({
        messages: {
            clientname: 'please enter your name',
            clientemail: 'please enter your email address',
            clientnumber: 'please enter your phone number',
            clientpeople: 'please enter how many people',
            clientdate: 'please enter booking date',
            clienttime: 'please enter booking time',
            clientrequest: 'please enter your special request'
        }
    });


    // ---------------------------------------------- //
    // Contact form validation
    // ---------------------------------------------- //
    $('#contact-form').validate({
        messages: {
            username: 'please enter your name',
            useremail: 'please enter your email address',
            message: 'please enter your message'
        }
    });

    // ---------------------------------------------- //
    // map
    // ---------------------------------------------- //
    map();

    // ---------------------------------------------- //
    // Hero Carousel initialization
    // ---------------------------------------------- //
    var Page = (function () {
        var $navArrows = $('#nav-arrows'),
            $nav       = $('#nav-dots > span'),
            slitslider = $('#slider').slitslider({
                onBeforeChange : function (slide, pos) {
                    $nav.removeClass('nav-dot-current');
                    $nav.eq(pos).addClass('nav-dot-current');
                }
            }),
            init = function () {
                initEvents();
            },
            initEvents = function () {
                // add navigation events
                $navArrows.children(':last').on('click', function () {
                    slitslider.next();
                    return false;
                });
                $navArrows.children(':first').on('click', function () {
                    slitslider.previous();
                    return false;
                });
                $nav.each(function (i) {
                    $(this).on('click', function (event) {
                        var $dot = $(this);
                        if (!slitslider.isActive()) {
                            $nav.removeClass('nav-dot-current');
                            $dot.addClass('nav-dot-current');
                        }
                        slitslider.jump(i + 1);
                        return false;
                    });
                });
            };
        return { init : init };
    })();
    Page.init();

    // ------------------------------------------------------ //
    // For demo purposes, can be deleted
    // ------------------------------------------------------ //

    var stylesheet = $('link#theme-stylesheet');
    $( "<link id='new-stylesheet' rel='stylesheet'>" ).insertAfter(stylesheet);
    var alternateColour = $('link#new-stylesheet');

    if ($.cookie("theme_csspath")) {
        alternateColour.attr("href", $.cookie("theme_csspath"));
    }

    $("#colour").change(function () {

        if ($(this).val() !== '') {

            var theme_csspath = 'css/style.' + $(this).val() + '.css';

            alternateColour.attr("href", theme_csspath);

            $.cookie("theme_csspath", theme_csspath, { expires: 365, path: document.URL.substr(0, document.URL.lastIndexOf('/')) });

        }

        return false;
    });

});



// ------------------------------------------------------ //
// styled Google Map
// ------------------------------------------------------ //
function map() {

    if ($('#map').length > 0) {


        function initMap() {

            var location = new google.maps.LatLng(40.420307,-3.703263);

            var mapCanvas = document.getElementById('map');
            var mapOptions = {
                center: location,
                zoom: 11,
                panControl: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            var map = new google.maps.Map(mapCanvas, mapOptions);

            var markerImage = 'img/marker.png';

            var marker = new google.maps.Marker({
                position: location,
                map: map,
                icon: markerImage
            });

            var contentString = '<div class="info-window">' +
                '<h3>Info Window Content</h3>' +
                '<div class="info-content">' +
                '<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>' +
                '</div>' +
                '</div>';

            var infowindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 400
            });

            marker.addListener('click', function () {
                infowindow.open(map, marker);
            });

            var styles = [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}];

            map.set('styles', styles);
        }

        google.maps.event.addDomListener(window, 'load', initMap);
    }

}
