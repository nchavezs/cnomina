function wizard_perfil() {
    $(".wizard_panel").hide();
    $(".wizard_panel_perfil").show();
    $(".wizard_step").removeClass("activo");
    $(".wizard_step_perfil").addClass("activo");
    $(".wizard_step_inicio").addClass("terminado");
}

function wizard_inicio() {
    $(".wizard_panel").hide();
    $(".wizard_panel_inicio").show();
    $(".wizard_step").removeClass("activo");
    $(".wizard_step_inicio").addClass("activo");
}

function wizard_logo() {
    $(".wizard_panel").hide();
    $(".wizard_panel_logo").show();
    $(".wizard_step").removeClass("activo");
    $(".wizard_step_logo").addClass("activo");
    $(".wizard_step_perfil").addClass("terminado");
}

function wizard_importar() {
    $(".wizard_panel").hide();
    $(".wizard_panel_importar").show();
    $(".wizard_step").removeClass("activo");
    $(".wizard_step_importar").addClass("activo");
    $(".wizard_step_logo").addClass("terminado");
}

$(document).on("submit", "#form_wizard_perfil", function(e){
    e.preventDefault();
    $.ajax({
        url: "assets/php/wizard_perfil.php",
        type: "POST",
        data: {
            nombre: $("#nombre").val(),
            password: $("#password").val(),
            confirmar: $("#confirmar").val()
        },
        success: function(data){
            if(data == 1){
                md.showNotification("top", "right", "Datos actualizados correctamente.");
                wizard_logo();
            }else if(data == 2){
                md.showNotification("top", "right", "Las contraseñas no coinciden.");
            }else if(data == 0){
                md.showNotification("top", "right", "Error al actualizar.");
            }
        }
    });
})