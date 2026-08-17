'use strict';

$(document).ready(function () {

  $('table td[colspan]').addClass('text-center');

  //preloader js code
  $(".preloader").delay(300).animate({
    "opacity": "0"
  }, 300, function () {
    $(".preloader").css("display", "none");
  });
});

// Show or hide the sticky footer button
$(window).on("scroll", function () {
  if ($(this).scrollTop() > 200) {
    $(".scroll-to-top").fadeIn(200);
  } else {
    $(".scroll-to-top").fadeOut(200);
  }
});

// Animate the scroll to top
$(".scroll-to-top").on("click", function (event) {
  event.preventDefault();
  $("html, body").animate({ scrollTop: 0 }, 300);
});



// ============== Header Hide Click On Body Js Start ========
$('.navbar-toggler.header-button').on('click', function () {
  if ($('.body-overlay').hasClass('show')) {
    $('.body-overlay').removeClass('show');
  } else {
    $('.body-overlay').addClass('show');
  }
});
$('.body-overlay').on('click', function () {
  $('.header-button').trigger('click');
});
// =============== Header Hide Click On Body Js End =========



// mobile menu js
$(".navbar-collapse>ul>li>a, .navbar-collapse ul.sub-menu>li>a").on("click", function () {
  const element = $(this).parent("li");
  if (element.hasClass("open")) {
    element.removeClass("open");
    element.find("li").removeClass("open");
  }
  else {
    element.addClass("open");
    element.siblings("li").removeClass("open");
    element.siblings("li").find("li").removeClass("open");
  }
});

// wow js init
new WOW().init();

// main wrapper calculator
var bodySelector = document.querySelector('body');
var header = document.querySelector('.header');
var footer = document.querySelector('.footer');
(function () {
  if (bodySelector.contains(header) && bodySelector.contains(footer)) {
    var headerHeight = document.querySelector('.header').clientHeight;
    var footerHeight = document.querySelector('.footer').clientHeight;

    // if header isn't fixed to top
    var totalHeight = parseInt(headerHeight, 10) + parseInt(footerHeight, 10) + 'px';

    // if header is fixed to top
    var minHeight = '100vh';
    document.querySelector('.main-wrapper').style.minHeight = `calc(${minHeight} - ${totalHeight})`;
  }
})();

$(function () {
  $('[data-toggle="tooltip"]').tooltip({
    boundary: 'window'
  })
});

$('#open-registration, #open-registration-modal').on('click', function () {
  $('.registration-form').removeClass('d-none');
  $('.login-form').addClass('d-none');
  $('#loginModalLabel').addClass('d-none');
  $('#registrationModalLabel').removeClass('d-none');
});

$('#open-login').on('click', function () {
  $('.registration-form').addClass('d-none');
  $('.login-form').removeClass('d-none');
  $('#loginModalLabel').removeClass('d-none');
  $('#registrationModalLabel').addClass('d-none');
});




//table data label
Array.from(document.querySelectorAll('table')).forEach(table => {
  let heading = table.querySelectorAll('thead tr th');
  Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
    Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
      colum.setAttribute('data-label', heading[i].innerText)
    });
  });
});