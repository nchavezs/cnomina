$(document).ready(function () {
	verGastos();
});


function verGastos() {
	$.ajax({
		type: "POST",
		url: "assets/php/usuario/gastos/gastos.php",
		success: function (html) {
			$(".gastos-caja").html(html);
			var s = document.createElement("script");
			s.type = "text/javascript";
			s.src = "assets/js/select.js";
			$("head").append(s);
			tablas_gastos();
			$(".sources").change(function () {
				tablas_gastos();
			});
		}
	});
};

var ano;
var mes;
var mes_texto;

function tablas_gastos() {
	ano = $("#ano :selected").val();
	mes = $("#mes :selected").val();
	mes_texto = $("#mes :selected").text();
	$.ajax({
		type: "POST",
		url: "assets/php/usuario/gastos/mostrarGastos.php",
		data: {
			"ano": ano,
			"mes": mes
		},
		success: function (html) {
			$(".caja-gastos").html(html);
			$("#detalle-gastos").html("del mes de " + mes_texto + " del " + ano);
		}
	});
};


function detalle_gastos(id) {
	$.post("assets/php/usuario/gastos/detalleGastos.php", {
		id: id
	}, function (datos) {
		var data = JSON.parse(datos);
		Swal.fire({
			position: 'center',
			html: data.html,
			customClass: 'swal3-width',
			allowOutsideClick: true,
			showCloseButton: true,
			showConfirmButton: false
		});

		var date1 = data.fecha.split("/");
        var date2 = new Date(date1[2], parseInt(date1[1]) - 1, date1[0]);
        $('#fecha').datepicker({
            startDate: date2,
            language: 'es',
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
