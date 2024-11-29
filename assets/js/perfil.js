foto();

function ver_expediente(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/expediente/index.php",
        data: {
            id,
        },
        success: function (html) {
            Swal.fire({
                html,
                width: '50em',
                background: "#EEEEEE",
                showConfirmButton: false,
                showCloseButton: true
            });
        },
    });
}

function expediente_archivos() { }

function subir_fichero(id) {
    $("#expediente_file").click();

    $("#expediente_file").off("change").on("change", function () {
        if ($(this).val() !== "") {
            $.blockUI({
                message: "<div class='circulo'></div><h5>Cargando archivo ...</h5>",
            });

            let formData = new FormData();
            formData.append("file", this.files[0]);
            formData.append("id", id);

            $.ajax({
                url: "assets/php/guardar_fichero.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function (data) {
                    let datos = JSON.parse(data);
                    console.log(datos)
                    $("[data-id=" + id + "]").closest(".expediente_caja").parent().replaceWith(datos.html);
                    $.unblockUI();
                    md.showNotification("top", "right", "Archivo cargado correctamente.");
                    $("#expediente_file").val("");
                },
            });
        }
    });
}

function eliminar_fichero(id, actualizar) {
    $("#modal .modal_titulo").html("¿Eliminar este documento?");
    $("#modal .modal-body").html("¿Seguro que quieres eliminar este documento?");
    mostrar_modal();

    $("#modal_aceptar")
        .off()
        .click(function () {
            $.post("/assets/php/eliminar_fichero.php", { id, actualizar }, function (data) {
                let datos = JSON.parse(data);
                let el = $("[data-id=" + id + "]").closest(".expediente_caja").parent();
                md.showNotification("top", "right", datos.mensaje);
                ocultar_modal();

                if (datos.success) {
                    el.remove();
                } else {
                    el.replaceWith(datos.html);
                }
            });
        });
}

$(document).ready(function () {
    $("#expediente").click(function () {
        ver_expediente($("#id").val());
        //     $.ajax({
        //         type: "POST",
        //         url: "assets/php/usuario/expediente/expediente.php",
        //         success: function (html) {
        //             Swal.fire({
        //                 html: html,
        //                 width: '50em',
        //                 background: "#EEEEEE",
        //                 showConfirmButton: false,
        //                 showCloseButton: true
        //             });
        //             expediente_menu($("#id").val());
        //         }
        //     });
    });

    $("#form-user").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "assets/php/datosUsuario.php",
            data: {
                telefono: $("#telefono").val(),
                email: $("#email").val(),
                nombre: $("#nombre").val(),
            },
            success: function (data) {
                if (data == 1) {
                    ok2();
                }
            }
        });
    });

    $("#form-empleado").submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "assets/php/datos_empleado.php",
            data: {
                telefono: $("#telefono").val(),
                email: $("#email").val(),
                banca: $("#banca").val(),
                domicilio: $("#domicilio").val(),
                afiliacion: $("#afiliacion").val()
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

            } else if (result.dismiss == 'cancel') { }
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

// function descargar_expediente(id, nombre) {
//     $.ajax({
//         type: "POST",
//         url: "assets/php/descargarExpediente.php",
//         data: {
//             "id": id,
//             "nombre": nombre
//         },
//         success: function (url) {
//             descargar(url, nombre);
//         }
//     });
// };


// function expediente_menu(id) {
//     var nombre = "";
//     $("#expediente_file").on('change', function () {
//         if ($(this).val() !== "" && nombre !== "") {
//             $.blockUI({
//                 message: "<div class='circulo'></div><h5>Cargando archivo ...</h5>",
//             });

//             var formData = new FormData();
//             var files = $(this)[0].files[0];
//             formData.append("file", files);
//             formData.append("nombre", nombre);
//             formData.append("id", id);

//             $.ajax({
//                 url: "assets/php/expedienteArchivo.php",
//                 type: "post",
//                 data: formData,
//                 contentType: false,
//                 processData: false,
//                 cache: false,
//                 success: function (data) {
//                     $("#expediente_file").val("");

//                     $.ajax({
//                         type: "POST",
//                         url: "assets/php/usuario/expediente/expediente.php",
//                         data: {
//                             "id": id
//                         },
//                         success: function (html) {
//                             $(".expediente").html(html);
//                             expediente_menu(id);
//                         }
//                     });

//                     $.unblockUI();
//                     md.showNotification("top", "right", "Archivo cargado correctamente.");

//                 }
//             });
//         }
//     });

//     $(".acta").click(function () {
//         nombre = 'acta';
//         $("#expediente_file").click();
//     });

//     $(".curp").click(function () {
//         nombre = 'curp';
//         $("#expediente_file").click();
//     });

//     $(".curriculum").click(function () {
//         nombre = 'curriculum';
//         $("#expediente_file").click();
//     });

//     $(".antecedentes").click(function () {
//         nombre = 'antecedentes';
//         $("#expediente_file").click();
//     });

//     $(".disciplinarios").click(function () {
//         nombre = 'disciplinarios';
//         $("#expediente_file").click();
//     });

//     $(".identificacion").click(function () {
//         nombre = 'identificacion';
//         $("#expediente_file").click();
//     });

//     $(".constancia").click(function () {
//         nombre = 'constancia';
//         $("#expediente_file").click();
//     });
//     $(".recomendacion").click(function () {
//         nombre = 'recomendacion';
//         $("#expediente_file").click();
//     });
//     $(".estudios").click(function () {
//         nombre = 'estudios';
//         $("#expediente_file").click();
//     });
// }

// function eliminar_expediente(id, nombre) {
//     $("#modal .modal_titulo").html("Eliminar archivo");
//     $("#modal .modal-body").html('¿Seguro que quieres eliminar este archivo?');
//     mostrar_modal();
//     $("#modal_aceptar").off().click(function () {
//         $.ajax({
//             type: "POST",
//             url: "assets/php/eliminarExpediente.php",
//             data: {
//                 "id": id,
//                 "nombre": nombre
//             },
//             success: function (data) {
//                 md.showNotification("top", "right", "Archivo eliminado correctamente.");
//                 ocultar_modal();
//                 $.ajax({
//                     type: "POST",
//                     url: "assets/php/usuario/expediente/expediente.php",
//                     data: {
//                         "id": id
//                     },
//                     success: function (html) {
//                         $(".expediente").html(html);
//                         expediente_menu(id);
//                     }
//                 });

//             }
//         });
//     })
// };