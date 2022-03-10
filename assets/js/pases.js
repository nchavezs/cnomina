$(document).ready(function () {
	verPases();
});


function verPases() {
	$.ajax({
		type: "POST",
		url: "assets/php/usuario/pases/pases.php",
		success: function (html) {
			$(".pases-caja").html(html);
			var s = document.createElement("script");
			s.type = "text/javascript";
			s.src = "assets/js/select.js";
			$("head").append(s);
			tablas_pases();
			$(".sources").change(function () {
				tablas_pases();
			});
		}
	});
};

var ano;
var mes;
var categoria;
var mes_texto;
var categoria_texto;

function tablas_pases() {
	ano = $("#ano :selected").val();
	mes = $("#mes :selected").val();
	mes_texto = $("#mes :selected").text();
	categoria = $("#categoria :selected").val();
	categoria_texto = $("#categoria :selected").text();
	$.ajax({
		type: "POST",
		url: "assets/php/usuario/pases/mostrarPases.php",
		data: {
			"ano": ano,
			"mes": mes,
			"categoria": categoria
		},
		success: function (html) {
			$(".caja-pases").html(html);
			$("#detalle-pase").html("del mes de " + mes_texto + " del " + ano);
			$("#titulo-pase").html("PASES DE " + categoria_texto.toUpperCase());
		}
	});
};


function detalle_pase(id) {
	$.post("assets/php/usuario/pases/detallePase.php", {
		id: id
	}, function (datos) {
		var data = JSON.parse(datos);
		Swal.fire({
			position: 'center',
			html: data.html,
			customClass: 'swal3-width',
			allowOutsideClick: true,
			background: "#EEEEEE",
			showCloseButton: true,
			showConfirmButton: false
		});

		var date1 = data.fecha.split("/");
		var date2 = new Date(parseInt(date1[2]), parseInt(date1[1]) - 1, parseInt(date1[0]));
		var hora1 = data.hora.split(" ");
		var hora2 = hora1[0].split(":");
		var hora = parseInt(hora2[0]);
		var minutos = parseInt(hora2[1]);
		$('#fecha').datepicker({
			startDate: date2,
			timepicker: true,
			language: 'es',
			minHours: hora,
			minMinutes: minutos,
			maxHours: hora,
			maxMinutes: minutos,
			onRenderCell: function (date, cellType) {
				if (cellType == 'day') {
					return {
						disabled: true,
					}
				}
			}
		});
		$('#fecha').data('datepicker').selectDate(date2);
	});
};

function archivo(url) {
	if (url === "") {
		Swal.fire(
			'Sin archivo',
			'No se ha encontrado ningún archivo',
			'warning'
		)
	} else
		window.open(url, '_blank');
};
