$(document).ready(function () {
   select_estilo();

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

      if (del == "" || al == "" || puestos_size < 1 || depa_size < 0) {
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

function pagina(pagina) {
   $(".reportes .pagina").addClass("adp-hide");
   ADP.show($(".reportes .pagina_" + pagina)[0], 'fade');
   $(".reportes .pagina_"+pagina).removeClass("adp-hide");
   depa_change_multiple(pagina);
}

function depa_change_multiple(pagina) {
   pagina--;
   $("#departamentos_" + pagina).on("change", function () {
      let items = document.getElementById("departamentos_" + pagina).selectedOptions.length;
      if (items > 0) {
         $.ajax({
            url: "assets/php/depa_change_multiple.php",
            type: "POST",
            data: {
               departamentos: $("#departamentos_" + pagina).val(),
            },
            success: function (data) {
               $("#puestos_" + pagina).html(data);
               tail.select("#puestos_" + pagina).reload();
               if (document.getElementById("plazas_" + pagina)) {
                  puesto_change_multiple(pagina);
               }
            }
         });
      }
   });
   $("#departamentos_" + pagina).change();
}

function puesto_change_multiple(pagina) {
   pagina--;
   $("#puestos_"+pagina).on("change", function () {
      $.ajax({
         url: "assets/php/puesto_change.php",
         type: "POST",
         data: {
            puesto: $("#puestos_"+pagina).val(),
         },
         success: function (data) {
            $("#plazas_"+pagina).html(data);
            tail.select("#plazas_"+pagina).reload();
         }
      });
   });

   $("#puestos_"+pagina).change();
}