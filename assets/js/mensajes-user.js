$(document).ready(
    function() {
        $.ajax({
            url: "assets/php/mensajes-user.php",
            success: function(html) {
                $(".msn-caja-chat").html(html);
            }
        });

        $(".dropdown-menu").perfectScrollbar();
    }
);

function accion(id) {
    $("#barra").html('<div class="barra"><input class="busqueda-texto" type="text" placeholder="Busqueda . . ." onkeyup="buscar();"><div class="busqueda-icono"><i class="material-icons">search</i></div></div>');

    var contenido = id.split("-");
    $(".busqueda-texto").val(contenido[1]);

    $.ajax({
        type: "POST",
        url: "assets/php/buscar-user.php",
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
        }
    });
}

function buscar() {
    var dato = $(".busqueda-texto").val();
    $.ajax({
        type: "POST",
        url: "assets/php/buscar-user.php",
        data: {
            texto: dato,
            condicion: 0
        },
        success: function(html) {
            if($(".msn-caja")){
                $(".msn-caja").html(html);
            }else{
                $(".msn-caja-chat").html(html);
            }
        }
    });
};