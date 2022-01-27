var timer = null;
var total = -1;

var numero_evento = null;

$(document).ready( function () {

      $('.dataTable').DataTable.ext.pager.numbers_length = 5;

      // numero();
      // numero_evento = setInterval(numero, 10000);

      $(document).on("click", "#salir", function () {
         Swal.close();
      });

      // $("#navbarDropdownMenuLink").on("click", function () {
      //    notificaciones();
      // });

      // $(".chat_cuerpo").perfectScrollbar();

      // $(".chat_enviar").on("click", function (e) {
      //    e.preventDefault();
      //    let mensaje = $("#chat-input").val().trim();
      //    if (mensaje != "") {
      //       send_mail(mensaje);
      //    }
      // });

      // $(".chat_fondo").on("click", function () {
      //    $(".chat_cerrar").click();
      // });

      // $(".chat").on("click", function () {
      //    cargar_mensajes();

      //    timer = setInterval(function () {
      //       cargar_mensajes()
      //    }, 8000);

      //    $(".chat").css("opacity", 0);
      //    $(".chat_caja").css("bottom", "20px");
      //    $(".chat_caja").css("opacity", 1);
      //    $(".chat_caja").css("pointer-events", "all");
      //    $(".chat_fondo").css("opacity", 1);
      //    $(".chat_fondo").css("pointer-events", "all");
      // });
      // $(".chat_cerrar").on("click", function () {
      //    clearTimeout(timer);
      //    $(".chat").css("opacity", 1);
      //    $(".chat_caja").css("bottom", "60px");
      //    $(".chat_caja").css("opacity", 0);
      //    $(".chat_caja").css("pointer-events", "none");
      //    $(".chat_fondo").css("opacity", 0);
      //    $(".chat_fondo").css("pointer-events", "none");
      // });
      // $('.chat_input textarea').on('keydown', function (e) {
      //    if (e.which === 13 && !e.shiftKey) {
      //       e.preventDefault();
      //       $(".chat_enviar").click();
      //    }
      //    var el = this;
      //    setTimeout(function () {
      //       el.style.cssText = 'height:auto; padding:0';
      //       el.style.cssText = '-moz-box-sizing:content-box';
      //       el.style.cssText = 'height:' + el.scrollHeight + 'px';
      //       $(".chat_cuerpo").css("height", "calc(90% - " + el.scrollHeight + 'px)');
      //    }, 0);
      // });


      $('#cerrar').click(function () {
         Swal.fire({
            position: 'center',
            type: 'question',
            title: '¿Desea cerrar sesión?',
            reverseButtons: true,
            showCancelButton: true,
            confirmButtonText: 'SI',
            cancelButtonText: 'NO',


         }).then((result) => {
            if (result.value) {
               $.post("assets/php/cerrarSesion.php", function (data) {
                  window.location.href = data;
               });

            } else if (result.dismiss === Swal.DismissReason.cancel) {

            }
         });
      });

      $('#cerrar-btn').click(function () {
         Swal.fire({
            position: 'center',
            type: 'question',
            title: '¿Desea cerrar sesión?',
            reverseButtons: true,
            showCancelButton: true,
            confirmButtonText: 'SI',
            cancelButtonText: 'NO',


         }).then((result) => {
            if (result.value) {
               $.post("assets/php/cerrarSesion.php", function (data) {
                  window.location.href = data;
               });

            } else if (result.dismiss === Swal.DismissReason.cancel) {

            }
         });
      });
   }
);

function no_pasar() {
   md.showNotification("top", "right", "No cuenta con los permisos suficientes.");
}

function cerrar() {
   Swal.close();
}


function send_mail(mensaje) {
   $.ajax({
      url: "assets/php/new_mensaje.php",
      method: "POST",
      data: {
         mensaje: mensaje
      },
      success: function (data) {
         if (data == 1) {
            $("#chat-input").val("");
            cargar_mensajes();
            mensaje_enviado();
         }
      }
   })
}

function cargar_mensajes() {
   $.post("assets/php/load_mensajes.php",
      function (datos) {
         let data = JSON.parse(datos);
         if (data.total != total) {
            $(".chat_cuerpo").html(data.html);
            $(".chat_cuerpo").scrollTop($(".chat_cuerpo")[0].scrollHeight);
            $(".chat_cuerpo .chat_msg").last().css("animation-name", "fadeIn");
            $(".chat_cuerpo .chat_msg").last().css("animation-duration", "2s");
            total = data.total;
         }
      });
}

function notificaciones() {
   $.ajax({
      url: "assets/php/notificaciones.php",
      success: function (html) {
         $(".noti-caja").html(html);
      }
   });
};

function numero() {
   $.ajax({
      url: "assets/php/numero.php",
      success: function (html) {
         $(".noti-numero").html(html);
      }
   });
};

function mensaje_enviado() {
   Swal.fire({
      title: "Correcto",
      text: "Enviado",
      type: "success"
   })
}

function mensaje_cargar() {
   let timerInterval
   Swal.fire({
      title: 'Cargando',
      html: 'Espere porfavor',
      allowOutsideClick: false,
      onBeforeOpen: () => {
         Swal.showLoading()
      },
      onClose: () => {
         clearInterval(timerInterval)
      }
   }).then((result) => {
      if (result.dismiss === Swal.DismissReason.timer) {}
   })
};

function select_estilo() {
   tail.select("select", {
      locale: "es",
      animate: true,
      classNames: ["campo"],
      width: "100%",
      search: true,
      descriptions: true,
      placeholder: "SELECCIONA UNA OPCIÓN",
      multiSelectAll:true,
      multiContainer: true,
      multiShowCount: false,
   });
};

function select_estilo_2() {
   tail.select("select", {
      locale: "es",
      animate: true,
      classNames: ["campo"],
      width: "100%",
      search: false,
      descriptions: true,
      placeholder: "SELECCIONA UNA OPCIÓN"
   });
};

function select_estilo_3() {
   tail.select("select", {
      locale: "es",
      animate: true,
      classNames: ["select_estilo"],
      search: false,
      width: "180px"
   });
};

function depa_change() {
   $("#departamento").on("change", function () {
      $.ajax({
         url: "assets/php/depa_change.php",
         type: "POST",
         data: {
            departamento: $("#departamento").val(),
         },
         success: function (data) {
            $("#puesto").html(data);
            tail.select("#puesto").reload();
            $("#puesto").change();
            if(document.getElementById( "plaza")){
               tail.select("#plaza").reload();
            }
         }
      });
   });

   $("#departamento").change();
}

function puesto_change() {
   $("#puesto").on("change", function () {
      $.ajax({
         url: "assets/php/puesto_change.php",
         type: "POST",
         data: {
            puesto: $("#puesto").val(),
         },
         success: function (data) {
            $("#plaza").html(data);
            tail.select("#plaza").reload();
         }
      });
   });
}

function isNumberKey(evt) {
   var charCode = (evt.which) ? evt.which : evt.keyCode
   return !(charCode > 31 && (charCode < 48 || charCode > 57));
};


function descargar(uri, name) {
    var link = document.createElement("a");
    link.download = name;
    link.href = uri;
    link.click();
}

function descargar_php(direccion) {
   window.location = "assets/php/download.php?filename=" + direccion;
};

function log_show(html) {
   Swal.fire({
       html: html,
       allowOutsideClick: false,
       allowEscapeKey: false
   });
}

$(document).on("click", '.cb-value', function () {
   var mainParent = $(this).parent('.toggle-btn');
   if ($(mainParent).find('input.cb-value').is(':checked')) {
       $(mainParent).addClass('active');
   } else {
       $(mainParent).removeClass('active');
   }
});