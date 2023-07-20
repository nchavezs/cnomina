$(document).ready(function () {
	verDescuentos();
});


function verDescuentos() {
	$.ajax({
		type: "POST",
		url: "assets/php/usuario/descuentos/descuentos.php",
		success: function (html) {
			$(".descuentos-caja").html(html);
			var s = document.createElement("script");
			s.type = "text/javascript";
			s.src = "assets/js/select.js";
			$("head").append(s);
			tablas_descuentos();
			$(".sources").change(function () {
				tablas_descuentos();
			});
		}
	});
};

var ano;
var mes;
var mes_texto;

function tablas_descuentos() {
	ano = $("#ano :selected").val();
	mes = $("#mes :selected").val();
	mes_texto = $("#mes :selected").text();
	$.ajax({
		type: "POST",
		url: "assets/php/usuario/descuentos/mostrarDescuentos.php",
		data: {
			"ano": ano,
			"mes": mes
		},
		success: function (html) {
			$(".caja-descuentos").html(html);
			$("#detalle-descuento").html("del mes de " + mes_texto + " del " + ano);
		}
	});
};


function detalle_descuento(id) {
	$.post("assets/php/usuario/descuentos/detalleDescuento.php", {
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

		var fechas = data.fechas.split(",");
		var fecha1 = fechas[0].split("/");
		var fechasDate = [];
		for (var i = 0; i < fechas.length; i++) {
			var fecha2 = fechas[i].split("/");
			fechasDate.push(new Date(fecha2[2], fecha2[1] - 1, fecha2[0]));
		}
		var max = new Date(Math.max.apply(null, fechasDate));
		var min = new Date(Math.min.apply(null, fechasDate));

		$('#fecha').datepicker({
			startDate: min,
			minDate: min,
			maxDate: max,
			language: 'es',
			onRenderCell: function (date, cellType) {
				var ano = date.getFullYear();
				var mes = date.getMonth() + 1;
				var dia = date.getDay();
				var fecha = date.getDate();
				if (cellType == 'day' && comparar_fechas(fechas, date)) {
					return {
						html: '<div class="celda-fecha2"><p>' + fecha + '</p></div>'
					}
				}
			},
			onSelect: function onSelect(fd, date) {}
		});
	});
};


function comparar_fechas(fechas, fecha) {
	var y = 0;
	for (var i = 0; i < fechas.length; i++) {
		var fecha1 = fechas[i].split("/");
		var fecha2 = new Date(fecha1[2], fecha1[1] - 1, fecha1[0]);
		if (fecha2.getTime() === fecha.getTime()) {
			y = 1;
			break;
		}
	}
	if (y == 1)
		return true;
	else
		return false;
};
