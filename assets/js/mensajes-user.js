var contacto_seleccionado = "";
var chat_evento = null;
var contactos_evento = null;
var total_mensajes = -1;
var total_nuevos = -1;

$(document).ready(function(){
    $(".mensajeria_contactos").perfectScrollbar();
    $(".mensajeria_chat").perfectScrollbar();
    contactos("");
    contactos_evento = setInterval("contactos('');", 3000);

    if($(window).width() < 767){
        $(document).on("click", ".mensajeria_contacto", function(){
            ADP.hide($(".mensajeria_panel")[0], 'slide-left');
        });
    
        $(document).on("click", ".mensajeria_usuario .regresar", function(){
            ADP.show($(".mensajeria_panel")[0], 'slide-left');
        });
    }

    $('.mensajeria textarea').on('keydown', function (e) {
        if (e.which === 13 && !e.shiftKey) {
           e.preventDefault();
           enviar_mensaje();
        }
     });

     $('.mensajeria input').on('input', function (e) {
        total_nuevos = -1;
        contactos(this.value);
        clearTimeout(contactos_evento);
        if(this.value == ""){
            contactos_evento = setInterval("contactos('');", 3000);
        }
     });
    
});

function limpiar_lista(){
    $(".mensajeria_contacto").removeClass("activo");
    let contacto = document.getElementById(contacto_seleccionado);
    $(contacto).addClass("activo");
    $(contacto).find(".mensajeria_noti").remove();
}

function mostrar_chat(id, nombre){
    contacto_seleccionado = id;
    $(".mensajeria_usuario span").html(nombre);

    total_nuevos = -1;
    contactos("");
    limpiar_lista();
    $("input").val("");

    clearTimeout(contactos_evento);
    contactos_evento = setInterval("contactos('');", 3000);

    $(".mensajeria_vacio").removeClass("adp-hide");
    ADP.show($(".mensajeria_caja")[0], 'fade');

    total_mensajes = -1;
    chat(id);
    clearTimeout(chat_evento);
    chat_evento = setInterval("chat(contacto_seleccionado);", 3000);
}

function chat(id){
    $.ajax({
        url: "assets/php/chat_mensaje.php",
        type: "POST",
        data:{
            id: id
        },
        success: function(datos){
            let data = JSON.parse(datos);
            if (data.total != total_mensajes) {
                $(".mensajeria_chat").html(data.html);
                $(".mensajeria_chat").scrollTop($(".mensajeria_chat")[0].scrollHeight);
                total_mensajes = data.total;
            }    
        }
    });
}

function contactos(texto){
    $.ajax({
        url: "assets/php/buscar_contacto_user.php",
        type: "POST",
        data:{
            texto: texto
        },
        success: function(datos){
            let data = JSON.parse(datos);
            if (data.total != total_nuevos) {
                $(".mensajeria_contactos").html(data.html);
                total_nuevos = data.total;
                limpiar_lista();
            } 
        }
    });
}

function enviar_mensaje(){
    $.ajax({
        url: "assets/php/enviar_mensaje.php",
        type: "POST",
        data:{
            mensaje: $(".mensajeria textarea").val(),
            id: contacto_seleccionado
        },
        success: function(data){
            if(data != 0){
                $(".mensajeria textarea").val("");
                $(".mensajeria_chat").append(data);
                $(".mensajeria_chat").scrollTop($(".mensajeria_chat")[0].scrollHeight);
            }
        }
    });
}