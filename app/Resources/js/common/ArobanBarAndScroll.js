/**
 * SideBar et FixedBar
 */
$('.toggle-sidebar').click(function () {
    $('body').toggleClass('sidebar-visible');
    $('.toggle-sidebar').toggleClass('sidebar-visible');
});

$(document).click(function (e) {
    var target = e.target;
    if (!$(target).is('#sidebar') && !$(target).is('.toggle-sidebar') && !$(target).parents().is('#sidebar')) {
        $('body').removeClass('sidebar-visible');
        $('.toggle-sidebar').removeClass('sidebar-visible');
    }
});

/**
 * Effet graphique sur les nobmres
 */
$('.panel .number').each(function () {
    $(this).prop('Counter', 0).animate({
        Counter: $(this).text()
    }, {
        duration: 800,
        easing: 'swing',
        step: function (now) {
            $(this).text(Math.ceil(now));
        }
    });
});

/**
 * Gestion du scroll
 */
$(window).scroll(function () {
    var y = $(window).scrollTop();
    var fixedbarSwitch = $('#topbar').innerHeight() + $('.page-header').innerHeight() + $('.nav-pills').innerHeight();
    if (y > (fixedbarSwitch)) {
        $('#vertical-nav').addClass('fixed');
    }
    else {
        $('#vertical-nav').removeClass('fixed');
    }
});

/**
 * Effet sur la navigation verticale
 */
$('#vertical-nav').find('a').on('click', function (event) {
    var hash = this.hash;
    event.preventDefault();

    $('html, body').animate({
        scrollTop: $(hash).offset().top
    }, 400, function () {
        window.location.hash = hash;
    });
});

/**
 * Scroller vers un div particulier.
 * Utilisé au départ sur les annonces terrains.
 */
$('button[id^="button-scroll"]').click(function () {
    var scrollDiv = $(this).attr('data-scroll-to');
    $('#' + scrollDiv).addClass('highlight');
    $('html, body').animate({
        scrollTop: $('#' + scrollDiv).offset().top - 80
    }, 20);
});
