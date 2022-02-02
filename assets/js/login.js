$(document).ready(function () {
  AOS.init({
    once: true
  });

  $("#login").submit(function (e) {
    e.preventDefault();
    $.ajax({
      url: "assets/php/sesion.php",
      type: "POST",
      data: $("form").serialize(),
      success: function (data) {
        if (data != 0 && data.includes("/")) {
          window.location.href = data;
        }else{
          mensaje("Error","Crendenciales no válidas","red");
        }
      }
    });
  });


  $(window).on('scroll', function () {
    var scrollToTop = $('.scroll-top-to'),
      scroll = $(window).scrollTop();
    if (scroll >= 200) {
      scrollToTop.fadeIn(200);
    } else {
      scrollToTop.fadeOut(100);
    }
  });

  $('.scroll-top-to').on('click', function () {
    $('body,html').animate({
      scrollTop: 0
    }, 500);
    return false;
  });

  $(".login").click(function () {
    $(".panel").addClass("mostrar");
    $(".fondo").css("display", "block");
  });

  $(".fondo").click(function () {
    cerrar();
  });
});

function cerrar() {
  $(".panel").removeClass("mostrar");
  $(".fondo").css("display", "none");
}

function mensaje(titulo, texto, color) {
  iziToast.show({
      title: titulo
      , message: texto
      , color: color
      , theme: "dark"
      , position: "bottomCenter"
      , transitionIn: 'revealIn'
  , });
}