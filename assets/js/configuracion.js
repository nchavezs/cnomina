function wizard_perfil() {
    $(".wizard_panel").hide();
    $(".wizard_panel_perfil").parent().show();
    $(".wizard_step").removeClass("activo");
    $(".wizard_step_perfil").addClass("activo");
    $(".wizard_step_inicio").addClass("terminado");
}

function wizard_inicio() {
    $(".wizard_panel").hide();
    $(".wizard_panel_inicio").parent().show();
    $(".wizard_step").removeClass("activo");
    $(".wizard_step_inicio").addClass("activo");
}

function wizard_logo() {
    $(".wizard_panel").hide();
    $(".wizard_panel_logo").parent().show();
    $(".wizard_step").removeClass("activo");
    $(".wizard_step_logo").addClass("activo");
    $(".wizard_step_perfil").addClass("terminado");
}

function wizard_importar() {
    $(".wizard_panel").hide();
    $(".wizard_panel_importar").parent().show();
    $(".wizard_step").removeClass("activo");
    $(".wizard_step_importar").addClass("activo");
    $(".wizard_step_logo").addClass("terminado");
}

function wizard_omitir() {
    window.location.href = "./registrar?pass=1";
}

function wizard_finalizar() {
    Swal.fire({
        title: "Finalizar configuración",
        text: "¿Seguro que quieres finalizar la configuración?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
        reverseButtons: "true"
    }).then((result) => {
        if (result.value) {
            window.location.href = "./registrar";
        }
    })
}

$(document).on("submit", "#form_wizard_perfil", function (e) {
    e.preventDefault();
    $.ajax({
        url: "assets/php/wizard_perfil.php",
        type: "POST",
        data: {
            nombre: $("#nombre").val(),
            password: $("#password").val(),
            confirmar: $("#confirmar").val()
        },
        success: function (data) {
            if (data == 1) {
                md.showNotification("top", "right", "Datos actualizados correctamente.");
            } else if (data == 2) {
                md.showNotification("top", "right", "Las contraseñas no coinciden.");
            } else {
                md.showNotification("top", "right", "Error al actualizar.");
            }
        }
    });
})



Dropzone.options.dropzonePlantilla = {
    paramName: "file",
    maxFileSize: 3,
    maxFiles: 1,
    acceptedFiles: '.xlsx',
    addRemoveLinks: true,
    dictRemoveFile: "X",
    dictCancelUpload: "Cancelar carga",
    dictInvalidFileType: "Formato incorrecto",

    init: function init() {
        myDropzone = this;

        this.on("success", function (file, data) {
            if (file.accepted && data != 0) {
                log_show(data);    
                Dropzone.forElement("#dropzone-plantilla").removeAllFiles(true);
                $(".wizard_step_importar").addClass("terminado");
            }else{
                md.showNotification("top", "right", "Contenido de archivo no válido.");
            }
        });

        this.on("addedfile", function(file) {
            let ext = file.name.split('.').pop();
            switch(ext){
                case 'pdf': $(file.previewElement).find(".dz-image img").attr("src", "assets/img/icons/pdf.png");
                break;
                case 'xlsx': $(file.previewElement).find(".dz-image img").attr("src", "assets/img/icons/xlsx.png");
                break;
                case 'png': $(file.previewElement).find(".dz-image img").attr("src", "assets/img/icons/img.png");
                break;
                case 'jpg': $(file.previewElement).find(".dz-image img").attr("src", "assets/img/icons/img.png");
                break;
                default: $(file.previewElement).find(".dz-image img").attr("src", "assets/img/icons/file.png");
                break;
            }
        });
    }
};

Dropzone.options.dropzoneLogo = {
    paramName: "file",
    maxFileSize: 5,
    maxFiles: 1,
    acceptedFiles: 'image/jpeg,image/png',
    addRemoveLinks: true,
    dictRemoveFile: "X",
    dictCancelUpload: "Cancelar carga",
    dictInvalidFileType: "Formato incorrecto",
    
    init: function init() {
        myDropzone = this;

        this.on("success", function (file, data) {
            switch(data){
                case "1": md.showNotification("top", "right", "Imagen cargada correctamente."); 
                break;
                default: md.showNotification("top", "right", "Error al cargar imagen.");
                break;
            }
            if (file.accepted) {
                $(".wizard_step_logo").addClass("terminado");
            }
        });

        this.on("addedfile", function(file) {
            let ext = file.name.split('.').pop();
            switch(ext){
                case 'pdf': $(file.previewElement).find(".dz-image img").attr("src", "assets/img/icons/pdf.png");
                break;
                case 'xlsx': $(file.previewElement).find(".dz-image img").attr("src", "assets/img/icons/xlsx.png");
                break;
                case 'png': $(file.previewElement).find(".dz-image img").attr("src", "assets/img/icons/img.png");
                break;
                case 'jpg': $(file.previewElement).find(".dz-image img").attr("src", "assets/img/icons/img.png");
                break;
                default: $(file.previewElement).find(".dz-image img").attr("src", "assets/img/icons/file.png");
                break;
            }
        });
    }
};

function log_show(html) {
    Swal.fire({
        html: html,
        allowOutsideClick: false,
        allowEscapeKey: false,
        padding: 0
    });
}

