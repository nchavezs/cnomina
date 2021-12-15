$(document).ready(function () {
    verMovimientos();
});

function verMovimientos() {
    $.ajax({
        type: "POST",
        url: "assets/php/usuario/movimientos/movimientos.php",
        success: function (html) {
            $(".movimientos-caja").html(html);
            var s = document.createElement("script");
            s.type = "text/javascript";
            s.src = "assets/js/select.js";
            $("head").append(s);

            tablas_movimientos();
            $(".sources").change(function () {
                tablas_movimientos();
            });
        }
    });
};

function detalle_movimiento(id) {
    $.post("assets/php/usuario/movimientos/detalleMovimiento.php", {
        "id": id
    }, function (html) {
        Swal.fire({
            position: 'center',
            html: html,
            allowOutsideClick: true,
            showCloseButton: true,
            showConfirmButton: false,
        });
    });
};

function tablas_movimientos() {
    var ano = $("#ano :selected").val();
    $.ajax({
        type: "POST",
        url: "assets/php/usuario/movimientos/mostrarMovimientos.php",
        data: {
            "ano": ano
        },
        success: function (html) {
            $(".caja-movimientos").html(html);
        }
    });
};

function archivo(url) {
    if (url === "") {
        Swal.fire({
            title: 'Sin archivo',
            text: 'No se ha encontrado ningún archivo',
            type: 'warning',

            
        })
    } else
        window.open(url, '_blank');
};