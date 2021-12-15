$(document).ready(function () {
    $.ajax({
        url: "assets/php/mensajes.php",
        success: function (html) {
            $(".msn-caja-chat").html(html);

            $(".msn-contenido").perfectScrollbar();

            $(document).on("click", ".mostrar-chat", function () {
                var contenido = this.id.split("x");
                if ($("#" + contenido[0] + "x").text() === "Mostrar") {
                    $("#" + contenido[0]).slideToggle();
                    $("#" + contenido[0] + "z").scrollTop($("#" + contenido[0] + "z")[0].scrollHeight);
                    $("#" + contenido[0] + "x").text("Ocultar");
                } else {
                    $("#" + contenido[0]).slideToggle();
                    $("#" + contenido[0] + "z").scrollTop($("#" + contenido[0] + "z")[0].scrollHeight);
                    $("#" + contenido[0] + "x").text("Mostrar");
                }
            });

            $(document).on("submit", ".msn-responder", function (e) {
                e.preventDefault();
                var contenido = this.id.split("y");
                var datos = $(this).serialize()
                $.ajax({
                    type: "POST",
                    url: "assets/php/nuevoMensaje.php",
                    data: datos,
                    success: function (html) {
                        if (html != 0) {
                            $("#" + contenido[0] + "z").append(html);
                            $("#" + contenido[0] + "z").scrollTop($("#" + contenido[0] + "z")[0].scrollHeight);
                            mensaje_enviado();
                        }
                        $("#" + contenido[0] + "y")[0].reset();
                        $(".msn-contenido").perfectScrollbar();
                    }
                });

            });
        }
    });

    $(".dropdown-menu").perfectScrollbar();
});

function accion(id) {
    $("#barra").html('<div class="barra"><input class="busqueda-texto" type="text" placeholder="Busqueda . . ." onkeyup="buscar();"><div class="busqueda-icono"><i class="material-icons">search</i></div></div>');

    var contenido = id.split("-");
    $(".busqueda-texto").val(contenido[1]);

    $.ajax({
        type: "POST",
        url: "assets/php/buscar.php",
        data: {
            texto: contenido[0],
            condicion: 1
        },
        success: function (html) {
            if($(".msn-caja")){
                $(".msn-caja").html(html);
            }else{
                $(".msn-caja-chat").html(html);
            }
            
            $.post("assets/php/vistos.php", {
                chat: contenido[0]
            });

            $(".navbar-brand").html("Mensajes");
            $("#link1").removeClass("active");
            $("#link2").addClass("active");
            $("#msn-caja").removeClass("container-fluid");
            $("#" + contenido[0]).slideToggle();
            $("#" + contenido[0] + "z").scrollTop($("#" + contenido[0] + "z")[0].scrollHeight);
            $(".msn-contenido").perfectScrollbar();
        }
    });
}


function buscar() {
    var dato = $(".busqueda-texto").val();
    $.ajax({
        type: "POST",
        url: "assets/php/buscar.php",
        data: {
            texto: dato,
            condicion: 0
        },
        success: function (html) {
            if($(".msn-caja")){
                $(".msn-caja").html(html);
            }else{
                $(".msn-caja-chat").html(html);
            }
        }
    });
};