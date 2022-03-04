var check1 = 0;

$("#check1").change(function () {
    if ($(this).is(':checked')) {
        check1 = 1;
    } else {
        check1 = 0;
    }
});

Dropzone.prototype.defaultOptions.dictRemoveFile = "X";
Dropzone.prototype.defaultOptions.dictCancelUpload = "X";
Dropzone.prototype.defaultOptions.dictInvalidFileType = "Formato de archivo incorrecto";

Dropzone.options.myAwesomeDropzone = {
    paramName: "file",
    maxFileSize: 3,
    parallelUploads: 1,
    acceptedFiles: '.pdf',
    addRemoveLinks: true,
    autoProcessQueue: false,
    init: function init() {
        myDropzone = this;
        contenido = "";
        a = 0;
        b = 0;
        c = 0;

        this.on("error", function (file) {
            if (!file.accepted) this.removeFile(file);
        });

        this.on("removedfile", function () {
            if (myDropzone.getQueuedFiles().length === 0) {
                $("#enviar").html('');
            }
        });

        this.on("sending", function (file, xhr, formData) {
            formData.append("registrar_usuario", check1);
        });

        this.on("addedfile", function (file) {           
                let ext = file.name.split('.').pop();
                switch(ext){
                    case 'pdf': $(file.previewElement).find(".dz-image img").attr("src", "assets/img/icons/pdf.png");
                    break;
                    default: $(file.previewElement).find(".dz-image img").attr("src", "assets/img/icons/file.png");
                    break;
                }
            $("#enviar").html('<div class="boton_generar_reporte"><div id="enviar-btn" class="btn btn-primary btn-sm regresar"><i class="material-icons">upload</i> Subir archivos</div></div>');
            $("#enviar-btn").on("click", function () {
                myDropzone.options.autoProcessQueue = true;
                myDropzone.processQueue();
                show_mensaje();
            });
        });

        this.on("success", function (file, data) {
            let val = data.charAt(data.length - 1);
            console.log(data);
            Swal.getContent().innerHTML = file.name;
            if (val == 2) {
                contenido = contenido + "<h5><span class='material-icons info'>info</span>" + file.name + "</h5>";
                b++;
            } else if (val == 1) {
                contenido = contenido + "<h5><span class='material-icons success'>check_circle</span>" + file.name + "</h5>";
                a++;
            } else {
                contenido = contenido + "<h5><span class='material-icons error'>error</span>" + file.name + "</h5>";
                c++;
            }
        });

        this.on("queuecomplete", function (file) {
            Swal.close();
            log_show(html(contenido, a, b, c));
            $(".contenido_log").perfectScrollbar();
            Dropzone.forElement("#myAwesomeDropzone").removeAllFiles(true);
            window.scroll(0, 0);
            myDropzone.options.autoProcessQueue = false;
            $("#enviar").html('');
            contenido = "";
            a = 0;
            b = 0;
            c = 0;

        });
    }
};


function show_mensaje() {
    Swal.fire({
        title: 'Subiendo archivos',
        html: 'Espere porfavor',
        allowOutsideClick: false,
        allowEscapeKey: false,
        onBeforeOpen: () => {
            Swal.showLoading()
        }
    });
};

function html(contenido, a, b, c) {
    return "<div class='log archivos text-center'>" +
        "<h3>RESUMEN DE IMPORTACIÓN</h3>" +
        "<div class='row informacion_archivos'>" +
        "<div class='col-10'>" +
        "<h5>Archivo ya ha sido cargado anteriormente <span class='material-icons info'>info</span></h5>" +
        "</div>" +
        "<div class='col-2'>" +
        "<p>" + b + "</p>" +
        "</div>" +
        "<div class='col-10'>" +
        "<h5>Archivo incompatible<span class='material-icons error'>error</span></h5>" +
        "</div>" +
        "<div class='col-2'>" +
        "<p>" + c + "</p>" +
        "</div>" +
        "<div class='col-10'>" +
        "<h5>Archivo cargado correctamente<span class='material-icons success'>check_circle</span></h5>" +
        "</div>" +
        "<div class='col-2'>" +
        "<p>" + a + "</p>" +
        "</div>" +
        "</div>" +
        "<div class='contenido_log text-left'>" + contenido + "</div>" +
        "</div>";
}

// function eliminar_archivos() {
//     $.ajax({
//         type: "POST",
//         url: "assets/php/eliminar_temporal.php",
//         success: function (data) {}
//     });
// }