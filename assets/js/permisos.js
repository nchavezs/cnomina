permisos();

function permisos() {
  $.post("assets/php/usuario/permisos/permisos.php", function (a) {
    $(".permisos-caja").html(a);
    var s = document.createElement("script");
    s.type = "text/javascript";
    s.src = "assets/js/select.js";
    $("head").append(s);

    tablas(id);
    $(".sources").change(function () {
      tablas();
    });
  });
}

function tablas() {
  var ano = $("#ano :selected").val();
  var categoria = $("#permiso :selected").val();
  $.ajax({
    type: "POST",
    url: "assets/php/usuario/permisos/mostrarPermisos.php",
    data: {
      ano: ano,
      categoria: categoria,
    },
    success: function (a) {
      var data = JSON.parse(a);
      $("#caja-permiso").html(data.html);
      $("#total").html("Total de permisos: " + data.total);
    },
  });
}

function detalle(id) {
  $.post(
    "assets/php/usuario/permisos/detallePermiso.php",
    {
      id: id,
    },
    function (datos) {
      var data = JSON.parse(datos);
      Swal.fire({
        position: "center",
        html: data.html,
        allowOutsideClick: true,
        showCloseButton: true,
        background: "#EEEEEE",
        showConfirmButton: false,
      });

      let date1 = data.del.split("/");
      date1 = new Date(date1[2], parseInt(date1[1]) - 1, date1[0]);

      let date2 = data.al.split("/");
      date2 = new Date(date2[2], parseInt(date2[1]) - 1, date2[0]);

      $("#fecha").datepicker({
        language: "es",
        minDate: date1,
        maxDate: date2,
        startDate: date1,
        onRenderCell: function (date, cellType) {
          if (cellType == "day" && comprobarFecha(data.del, data.al, date)) {
            return {
              html:
                '<div class="celda-fecha"><p>' + date.getDate() + "</p></div>",
            };
          }
        },
        onSelect: function onSelect(fd, date) {},
      });
    }
  );
}

function comprobarFecha(fechaAnterior, fechaPosterior, fecha) {
  var fecha1 = fechaAnterior.split("/");
  var fecha2 = fechaPosterior.split("/");

  var fAnt = new Date(fecha1[2], fecha1[1] - 1, fecha1[0]);
  var fPos = new Date(fecha2[2], fecha2[1] - 1, fecha2[0]);
  var fAct = fecha;

  if (fAct >= fAnt && fAct <= fPos) return true;
  else return false;
}