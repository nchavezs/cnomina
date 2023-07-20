$(document).ready(function () {
	verVacaciones();
});


function verVacaciones() {
	$.ajax({
		type: "POST",
		url: "assets/php/usuario/vacaciones/vacaciones.php",
		success: function (html) {
		$(".vacaciones-caja").html(html);
			
			var s = document.createElement("script");
			s.type = "text/javascript";
			s.src = "assets/js/select.js";
			$("head").append(s);

			tablas_vacaciones();
			$(".sources").change(function () {
				tablas_vacaciones();
			});
		}
	});
};

var ano;

function tablas_vacaciones() {
	ano = $("#ano :selected").val();
	$.ajax({
		type: "POST",
		url: "assets/php/usuario/vacaciones/mostrarVacaciones.php",
		data: {
			"ano": ano
		},
		success: function (datos) {
			var data = JSON.parse(datos);
			$(".caja-vacaciones").html(data.html);
			$("#sub_vaca").html(data.total);
		}
	});
};

function detalle_vacacion(id) {
	$.post("assets/php/usuario/vacaciones/detalleVacacion.php", {
		"id": id
	}, function (datos) {
		var data = JSON.parse(datos);
		Swal.fire({
			position: 'center',
			html: data.html,
			allowOutsideClick: true,
			showCloseButton: true,
			background: "#EEEEEE",
			showConfirmButton: false
		});

		var fecha2 = data.fecha2.split("/");
		var fecha3 = data.fecha3.split("/");
		$('#fecha').datepicker({
			startDate: new Date(fecha2[2], fecha2[1] - 1, fecha2[0]),
			language: 'es',
			minDate: new Date(fecha2[2], fecha2[1] - 1, fecha2[0]),
			maxDate: new Date(fecha3[2], fecha3[1] - 1, fecha3[0]),
			onRenderCell: function (date, cellType) {
				var ano = date.getFullYear();
				var mes = date.getMonth() + 1;
				var dia = date.getDay();
				var fecha = date.getDate();

				if (cellType == 'day' && comprobarFecha(data.fecha2, data.fecha3, date)) {
					return {
						html: '<div class="celda-fecha2"><p>' + fecha + '</p></div>'
					}
				}
			},
			onSelect: function onSelect(fd, date) {}
		});
	});
};

function comprobarFecha(fechaAnterior, fechaPosterior, fecha) {

	var fecha1 = fechaAnterior.split("/");
	var fecha2 = fechaPosterior.split("/");

	var fAnt = new Date(fecha1[2], fecha1[1] - 1, fecha1[0]);
	var fPos = new Date(fecha2[2], fecha2[1] - 1, fecha2[0]);
	var fAct = fecha;

	if (fAct >= fAnt && fAct <= fPos)
		return true;
	else
		return false;
};