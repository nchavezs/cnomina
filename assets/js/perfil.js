foto();

$(document).ready(function () {
    document.getElementById('telefono').addEventListener('input', function (e) {
        var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });

    $("#expediente").click(function () {
        $.ajax({
            type: "POST",
            url: "assets/php/usuario/expediente/expediente.php",
            success: function (html) {
                Swal.fire({
                    html: html,
                    width: '50em',
                    showConfirmButton: false,
                    showCloseButton: true
                });

                $(".descargar").click(function () {
                    var id = $("#id").val();
                    descargar_expediente(id, this.name);
                })
            }
        });
    });

    $("#form-user").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "assets/php/datosUsuario.php",
            data: {
                nombre: $("#nombre").val(),
                telefono: $("#telefono").val(),
                email: $("#email").val()
            },
            success: function (data) {
                if (data == 1) {
                    ok2();
                }
            }
        });
    });

    $("#subir").on('click', function () {
        document.getElementById("archivo").click();
    });

    $("#eliminar_nominas").on('click', function () {
        Swal.fire({
            title: 'Eliminar recibos de nómina',
            text: "¿Está seguro de eliminar todos los archivos CFDI?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si, continuar',
            cancelButtonText: 'Cancelar',
            
            
        }).then(function (result) {
            if (result.value) {
                eliminando();
                $.ajax({
                    type: "POST",
                    url: "assets/php/eliminar_nominas.php",
                    success: function (data) {
                        Swal.close();
                        if (data == 1) {
                            Swal.fire({
                                title: 'Correcto',
                                text: 'Recibos de nómina eliminados',
                                type: 'success',

                                
                            })
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'No fue posible eliminar los recibos de nómina',
                                type: 'error',

                                
                            })
                        }
                    }
                });

            } else if (result.dismiss == 'cancel') {}
        });
    });

    $("#archivo").change(function () {
        $.blockUI({
            message: "<div class='circulo'></div><h5>Cargando foto de perfil ...</h5>",
        });
        var formData = new FormData();
        var files = $('#archivo')[0].files[0];  
        formData.append('file', files);
        $.ajax({
            url: 'assets/php/foto_perfil.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            enctype: 'multipart/form-data',
            cache: false,
            success: function (a) {
                $.unblockUI();
                $("#archivo").val("");
                if (a.includes("assets/")) {
                    imagen();
                    $("#foto").attr('src', a);
                    $(".sidebar .avatar img").attr('src', a);
                } else {
                    formato();
                }
            }
        });
    });

    $("#form-cambiar").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "assets/php/cambiarPassword.php",
            data: $("#form-cambiar").serialize(),
            success: function (data) {
                if (data == 0) {
                    error();
                }
                if (data == 1) {
                    ok();
                }
                if (data == 2) {
                    warning();
                }
                $("#pass").val("");
                $("#newPass").val("");
                $("#confirmacion").val("");
            }
        });
    });
});

function eliminando() {
    let timerInterval
    Swal.fire({
        title: 'Eliminando',
        html: 'Espere porfavor',
        allowOutsideClick: false,
        

        onBeforeOpen: () => {
            Swal.showLoading()
        },
        onClose: () => {
            clearInterval(timerInterval)

        }
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {

        }
    })
};

function error() {
    Swal.fire({
        title: 'Error',
        text: 'No se actualizó tu contraseña',
        type: 'error',

        
    })
};

function formato() {
    Swal.fire({
        title: 'Error',
        text: 'Formato de archivo no válido',
        type: 'error',

        
    })
};

function ok() {
    Swal.fire({
        title: 'Correcto',
        text: 'Contraseña actualizada',
        type: 'success',

        
    })
};

function ok2() {
    Swal.fire({
        title: 'Correcto',
        text: 'Datos actualizados',
        type: 'success',

        
    })
};

function imagen() {
    Swal.fire({
        title: 'Correcto',
        text: 'Foto de perfil actualizada',
        type: 'success',

        
    })
};

function warning() {
    Swal.fire({
        title: 'Error',
        text: 'Los campos no coinciden',
        type: 'error',

        
    })
};

function foto() {
    $.post("assets/php/perfilFoto.php", function (a) {
        if (a != 0)
            $("#foto").attr('src', a);
    });
};

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
};

function descargar_expediente(id, nombre) {
    $.ajax({
        type: "POST",
        url: "assets/php/descargarExpediente.php",
        data: {
            "id": id,
            "nombre": nombre
        },
        success: function (url) {
            descargar(url, nombre);
        }
    });
};
