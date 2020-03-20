$(document).ready(function(){

    $('.showhide').click(function(e) {
      $('#main_menu').stop().slideToggle('slow');
    });

    $('a[href^="#skipCont"]').click(function(e) {
        e.preventDefault();
        $('html,body').animate({ scrollTop: $(this.hash).offset().top}, 500);
    });

    // keyboard tab focus
    document.addEventListener('keydown', function(e) {
        if (e.keyCode === 9) {
        $('body').addClass('show-focus-outlines');
        }
    });
    document.addEventListener('mousedown', function(e) {
        $('body').removeClass('show-focus-outlines');
    });

    $('#accessible, #nav').find('li').each(function(index, element) {
        $(this).children('a').focus(function(e) {

            $(this).parent('li').addClass('hover');
        });
    });

    $('#header-nav>li>a, #primary-menu>li>a').focusin(function(e) {
        $('#header-nav, #primary-menu').find('li').each(function(index, element) {
            $(this).removeClass('hover');
        });
        $(this).addClass('hover');

    });

    $("#header-nav>li:last-child ul li:last-child, #primary-menu>li:last-child ul li:last-child").focusout(function(e) {
        $("#header-nav>li:last-child, #primary-menu>li:last-child").removeClass("hover")
    });

    $('#header-nav>li>a, #primary-menu>li>a').click(function(e) {
        $(this).addClass('hover');
        $(this).next('ul').addClass('visible');

    });

    $(document).on('click', function (e) {
        if ($(e.target).closest("#header-nav>li").length === 0) {
            $("#header-nav li").removeClass('hover');
        }

    });

    $('.more-vid-section .owl-carousel').owlCarousel({
        margin: 10,
        nav: true,
        dots: false,
        //loop: $('.owl-carousel .item').size() > 1 ? true:false,
        responsive: {
            0: { items: 1 },
            600: { items: 2 },
            1000: { items: 4 }
        }
    });

    $('.announcement-carousel').owlCarousel({
        items: 1,
        margin:0,
        nav:true,
        loop: true,
        dots:false,
        autoplay:true,
        autoplayTimeout:4000,
        autoplayHoverPause:true
    });

});

