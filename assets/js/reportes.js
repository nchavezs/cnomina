// $(document).on("mouseenter",".tail-select", function(){
//    let id = $(this).prev().prop("id");
//    tail.select("#"+id).open();
// });

$(document).on("mouseleave", ".tail-select", function () {
   let id = $(this).prev().prop("id");
   tail.select("#" + id).close();
   $("#del_2").val(moment().startOf('year').format('MM/DD/YYYY'));
});

$(document).ready(function () {
   select_estilo();
   depa_change_multiple();
   puestos_change_multiple();
   $("#del_2").val(moment().startOf('year').format('MM/DD/YYYY'));

   $("#descripcion_plaza").change(function () {
      if ($("#historial_plaza").is(":checked")) {
         $("#historial_plaza").click();
         $("#descripcion_plaza").click();
      }
   });

   $("#historial_plaza").change(function () {
      if ($("#descripcion_plaza").is(":checked")) {
         $("#descripcion_plaza").click();
         $("#historial_plaza").click();
      }

      if ($(this).is(":checked")) {
         ADP.show($("#plazas_2").parent()[0], 'flip-down');
         $("#del_2").prop("disabled", false);
      } else {
         ADP.hide($("#plazas_2").parent()[0], 'flip-up');
         $("#del_2").prop("disabled", true);
         $("#del_2").val(moment().startOf('year').format('MM/DD/YYYY'));
      }
   });

   $("#reporte_usuario").click(function (e) {
      e.preventDefault();
      var usuarios = $("#usuario_3").val();
      var del = $("#del_3").val();
      var al = $("#al_3").val();

      let usuario_size = document.getElementById("usuario_3").selectedOptions.length;

      if (usuario_size < 1) {
         md.showNotification("top", "right", "Completa todos los campos.");
      } else {
         $(this).prop("disabled", true);
         // mensaje_cargar();;

         $.ajax({
            url: "assets/php/reporte_usuario.php",
            type: "POST",
            data: {
               "del": del,
               "al": al,
               "usuarios": usuarios
            },
            success: function (data) {
               // let verificar = data.includes("assets/archivos/");

               // if (verificar) {
                  window.open(data, '_blank');
               // } else {
               //    md.showNotification("top", "right", data);
               // }

               $("#reporte_usuario").prop("disabled", false);
               Swal.close();
            }
         });
      }
   });

   $("#reporte_plazas").click(function (e) {
      e.preventDefault();
      let puestos = $("#puestos_2").val();
      let departamentos = $("#departamentos_2").val();
      let plazas = $("#plazas_2").val();
      let del = $("#del_2").val();
      let al = $("#al_2").val();
      let puestos_size = document.getElementById("puestos_2").selectedOptions.length;
      let depa_size = document.getElementById("departamentos_2").selectedOptions.length;

      if (del == "" || al == "" || puestos_size < 1 || depa_size < 1) {
         md.showNotification("top", "right", "Completa todos los campos.");
      } else {
         if ($("#descripcion_plaza").is(':checked')) {
            reporte_descripcion(del, al, puestos, departamentos, this);
         } else if ($("#historial_plaza").is(':checked')) {
            reporte_historial(del, al, puestos, departamentos, plazas, this);
         }
      }

   });

   $("#reporte_general").click(function (e) {
      e.preventDefault();
      var puestos = $("#puestos_1").val();
      var departamentos = $("#departamentos_1").val();
      var del = $("#del_1").val();
      var al = $("#al_1").val();
      var sin_goce = 0;
      var con_goce = 0;
      var pases = 0;
      var movimientos = 0;
      var vacaciones = 0;
      var descuentos = 0;
      var medicos = 0;
      var altas = 0;
      var bajas = 0;

      let puestos_size = document.getElementById("puestos_1").selectedOptions.length;
      let depa_size = document.getElementById("departamentos_1").selectedOptions.length;

      if (del == "" || al == "" || puestos_size < 1 || depa_size < 1) {
         md.showNotification("top", "right", "Completa todos los campos.");
      } else {
         $(this).prop("disabled", true);
         mensaje_cargar();

         sin_goce = $("#sin_goce").is(':checked') ? 1 : 0;
         con_goce = $("#con_goce").is(':checked') ? 1 : 0;
         permisos = $("#permisos").is(':checked') ? 1 : 0;
         movimientos = $("#movimientos").is(':checked') ? 1 : 0;
         vacaciones = $("#vacaciones").is(':checked') ? 1 : 0;
         descuentos = $("#descuentos").is(':checked') ? 1 : 0;
         altas = $("#altas").is(':checked') ? 1 : 0;
         bajas = $("#bajas").is(':checked') ? 1 : 0;
         medicos = $("#medicos").is(':checked') ? 1 : 0;

         $.ajax({
            url: "assets/php/reporte_general.php",
            type: "POST",
            data: {
               "del": del,
               "al": al,
               "puestos": puestos,
               "departamentos": departamentos,
               "sin_goce": sin_goce,
               "con_goce": con_goce,
               "pases": pases,
               "movimientos": movimientos,
               "vacaciones": vacaciones,
               "descuentos": descuentos,
               "medicos": medicos,
               "altas": altas,
               "bajas": bajas
            },
            success: function (data) {
               let verificar = data.includes("assets/archivos/");

               if (verificar) {
                  window.open(data, '_blank');
               } else {
                  md.showNotification("top", "right", data);
               }

               $("#reporte_general").prop("disabled", false);
               Swal.close();
            }
         });
      }
   });

   $('.reportes input[type="text"].campo').datepicker({
      language: 'es',
      maxDate: new Date(),
      autoClose: 'true',
      todayButton: new Date()
   });
});

function reporte_historial(del, al, puestos, departamentos, plazas, boton) {
   let plaza_size = document.getElementById("plazas_2").selectedOptions.length;

   if (plaza_size < 1) {
      md.showNotification("top", "right", "Completa todos los campos.");
   } else {
      $(boton).prop("disabled", true);
      mensaje_cargar();
      $.ajax({
         url: "assets/php/reporte_historial_plazas.php",
         type: "POST",
         data: {
            "del": del,
            "al": al,
            "plazas": plazas
         },
         success: function (data) {
            let verificar = data.includes("assets/archivos/");

            if (verificar) {
               window.open(data, '_blank');
            } else {
               md.showNotification("top", "right", data);
            }

            $(boton).prop("disabled", false);
            Swal.close();
         }
      });
   }
}

function reporte_descripcion(del, al, puestos, departamentos, boton) {

   $(boton).prop("disabled", true);
   mensaje_cargar();
   $.ajax({
      url: "assets/php/reporte_descripcion_plazas.php",
      type: "POST",
      data: {
         "del": del,
         "al": al,
         "puestos": puestos
      },
      success: function (data) {
         let verificar = data.includes("assets/archivos/");

         if (verificar) {
            window.open(data, '_blank');
         } else {
            md.showNotification("top", "right", data);
         }

         $(boton).prop("disabled", false);
         Swal.close();
      }
   });

}


function pagina(page) {
   $(".reportes .pagina").addClass("adp-hide");
   ADP.show($(".reportes .pagina_" + page)[0], 'fade');
   $(".reportes .pagina_" + page).removeClass("adp-hide");
}

function depa_change_multiple() {
   $(".departamentos").change(function () {
      let select_puesto = $(this).closest(".pagina").find(".puestos").prop("id");
      let select_plaza = $(this).closest(".pagina").find(".plazas").prop("id");
      let elemento = $(this);
      $.ajax({
         url: "assets/php/depa_change_multiple.php",
         type: "POST",
         data: {
            departamentos: elemento.val()
         },
         success: function (data) {

            $("#" + select_puesto).html(data);
            tail.select("#" + select_puesto).reload();
            $("#" + select_puesto).change();

            if (document.getElementById(select_plaza)) {
               $("#" + select_plaza).html(data);
               tail.select("#" + select_plaza).reload();
            }
         }
      });

   });
}

function puestos_change_multiple() {
   $(".puestos").change(function () {
      let elemento = $(this);
      let select_plaza = elemento.closest(".pagina").find(".plazas").prop("id");
      $.ajax({
         url: "assets/php/puesto_change_multiple.php",
         type: "POST",
         data: {
            puestos: elemento.val(),
         },
         success: function (data) {
            if (document.getElementById(select_plaza)) {
               $("#" + select_plaza).html(data);
               tail.select("#" + select_plaza).reload();
            }
         }
      });
   });
}