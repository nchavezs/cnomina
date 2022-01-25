$(document).ready(function () {
   select_estilo();
   depa_change_multiple();

   $("#reporte").click(function (e) {
      e.preventDefault();
      var puestos = $("#puestos").val();
      var departamentos = $("#departamentos").val();
      var del = $("#del").val();
      var al = $("#al").val();
      // var beneficiarios = 0;
      var sin_goce = 0;
      var con_goce = 0;
      var licencias = 0;
      var movimientos = 0;
      var vacaciones = 0;
      var descuentos = 0;
      var medicos = 0;
      var altas = 0;
      var bajas = 0;

      let puestos_size = document.getElementById("puestos").selectedOptions.length;
      let depa_size = document.getElementById("departamentos").selectedOptions.length;

      if (del == "" || al == "" || puestos_size < 1 || depa_size < 0) {
         md.showNotification("top", "right", "Completa todos los campos.");
      } else {
         $(this).prop("disabled", true);
         mensaje_cargar();

         // if ($("#check1").is(':checked'))
         //    beneficiarios = 1;

         if ($("#check2").is(':checked'))
            sin_goce = 1;

         if ($("#check3").is(':checked'))
            con_goce = 1;

         if ($("#check4").is(':checked'))
            licencias = 1;

         if ($("#check5").is(':checked'))
            movimientos = 1;

         if ($("#check6").is(':checked'))
            vacaciones = 1;

         if ($("#check7").is(':checked'))
            descuentos = 1;

         if ($("#check8").is(':checked'))
            medicos = 1;

         if ($("#check9").is(':checked'))
            altas = 1;

         if ($("#check10").is(':checked'))
            bajas = 1;


         $.ajax({
            url: "assets/php/reporte_general.php",
            type: "POST",
            data: {
               "del": del,
               "al": al,
               "puestos": puestos,
               "departamentos": departamentos,
               // "beneficiarios": beneficiarios,
               "sin_goce": sin_goce,
               "con_goce": con_goce,
               "licencias": licencias,
               "movimientos": movimientos,
               "vacaciones": vacaciones,
               "descuentos": descuentos,
               "medicos": medicos,
               "altas": altas,
               "bajas": bajas
            },
            success: function (data) {
               let verificar = data.includes("assets/archivos/"); 
               
               if(verificar){
                  window.open(data, '_blank');
               }else{
                  md.showNotification("top", "right", data);
               }
            
               $("#reporte").prop("disabled", false);
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
}

function depa_change_multiple() {
   $("#departamentos").on("change", function () {
      let items = document.getElementById("departamentos").selectedOptions.length;
      if (items > 0) {
         $.ajax({
            url: "assets/php/depa_change_multiple.php",
            type: "POST",
            data: {
               departamentos: $("#departamentos").val(),
            },
            success: function (data) {
               $("#puestos").html(data);
               tail.select("#puestos").reload();
               if (document.getElementById("plazas")) {
                  puesto_change_multiple();
               }
            }
         });
      }
   });
   $("#departamentos").change();
}

function puesto_change_multiple() {
   $("#puestos").on("change", function () {
      $.ajax({
         url: "assets/php/puesto_change.php",
         type: "POST",
         data: {
            puesto: $("#puestos").val(),
         },
         success: function (data) {
            $("#plazas").html(data);
            tail.select("#plazas").reload();
         }
      });
   });

   $("#puestos").change();
}