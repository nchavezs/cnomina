$(document).ready(function () {
    // $.post("assets/php/actualizar_password.php", function(data) {});

    $("#generar-prenomina").click(function () {
        Swal.mixin({
            showCancelButton: true,
            progressSteps: ["1", "2", "3"],


        }).queue([{
                title: "Periodo",
                confirmButtonText: "Siguiente &rarr;",
                input: "select",
                inputClass: "swal2-input",
                inputPlaceholder: "SELECCIONA",
                inputOptions: {
                    "1": "PRIMERA QUINCENA",
                    "2": "SEGUNDA QUINCENA"
                },
                inputValidator: (value) => {
                    return !value && "Selecciona una opción"
                }
            },
            {
                title: "Mes",
                confirmButtonText: "Siguiente &rarr;",
                input: "select",
                inputClass: "swal2-input",
                inputPlaceholder: "SELECCIONA",
                inputOptions: {
                    "1": "ENERO",
                    "2": "FEBRERO",
                    "3": "MARZO",
                    "4": "ABRIL",
                    "5": "MAYO",
                    "6": "JUNIO",
                    "7": "JULIO",
                    "8": "AGOSTO",
                    "9": "SEPTIEMBRE",
                    "10": "OCTUBRE",
                    "11": "NOVIEMBRE",
                    "12": "DICIEMBRE"
                },
                inputValidator: (value) => {
                    return !value && "Selecciona una opción"
                }
            },
            {
                title: "Año",
                confirmButtonText: "Generar &rarr;",
                input: "select",
                inputClass: "swal2-input",
                inputPlaceholder: "SELECCIONA",
                inputOptions: {
                    "2022": "2022",
                    "2023": "2023",
                    "2024": "2024",
                    "2025": "2025"
                },
                inputValidator: (value) => {
                    return !value && "Selecciona una opción"
                }
            }
        ]).then((result) => {
            if (result.value) {
                var resultado = JSON.stringify(result.value);
                var datos = jQuery.parseJSON(resultado);
                var periodo = datos[0];
                var mes = datos[1];
                var ano = datos[2];
                mensaje_cargar();
                $.ajax({
                    url: "assets/php/prenomina.php",
                    type: "post",
                    data: {
                        "periodo": periodo,
                        "mes": mes,
                        "ano": ano
                    }
                }).done(function (file) {
                    if (file != 0) {
                        descargar(file, 'Prenomina');
                        Swal.close();
                        Swal.fire({
                            title: 'Correcto',
                            text: 'Prenomina generada correctamente',
                            type: 'success'
                        })
                    } else {
                        Swal.close();
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo generar el archivo',
                            type: 'error',


                        })
                    }
                });
            }
        });
    });


    $("#importar-empleado").change(function () {
        if ($(this).val() !== "") {
            mensaje_cargar();

            var formData = new FormData();
            var files = $("#importar-empleado")[0].files[0];
            formData.append("file", files);

            $.ajax({
                url: "assets/php/subirExcel.php",
                type: "post",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function (dato) {
                    if (dato == 1) {
                        $.post("assets/php/leerExcel.php", function (html) {
                            swal.close();
                            if (html != 0) {
                                Swal.fire({
                                    position: 'center',
                                    html: html,
                                    type: "warning",
                                    confirmButtonText: "Aceptar",
                                    allowOutsideClick: true,
                                    showConfirmButton: true,


                                });
                                $(".log-contenido").perfectScrollbar();
                                $('#tabla-empleado').DataTable().ajax.reload();
                            } else {
                                Swal.fire({
                                    title: 'Error de archivo',
                                    text: 'El formato del archivo no es el correcto',
                                    type: 'error'
                                })
                            }

                        });
                    } else {
                        Swal.fire({
                            title: 'Error de archivo',
                            text: 'El formato del archivo no es el correcto',
                            type: 'error',


                        })
                    }
                    $("#importar-empleado").val("");
                }
            });
        }
    });

    $("#nuevo-empleado").click(function () {
        mensaje_cargar();
        $.post("assets/php/nuevoEmpleado.php", function (html) {
            Swal.fire({
                position: 'center',
                html: html,
                width: '60em',
                showCloseButton: true,
                allowOutsideClick: false,
                showConfirmButton: false
            });

            $(".pagina_2_boton").click(function () {
                $(".pagina_2").addClass("adp-hide");
                ADP.show($(".pagina_1")[0], 'fade');
                $(".pagina_1").removeClass("adp-hide");
            });

            select_estilo();
            select_change();
            $("#nombre").blur();
            var ingreso;
            var date = new Date();
            date.setMonth(date.getMonth() - 1);

            $('#ingreso').datepicker({
                minDate: date,
                maxDate: new Date(),
                language: 'es',
                autoClose: 'true',
                position: "bottom center",
                todayButton: new Date(),
                onSelect(formattedDate, date, inst) {
                    if (date == '')
                        $('#ingreso').val(ingreso);
                    else
                        ingreso = formattedDate;
                }
            });

            $("#form-empleado-1").submit(function (e) {
                e.preventDefault();
                $(".pagina_1").addClass("adp-hide");
                ADP.show($(".pagina_2")[0], 'fade');
                $(".pagina_2").removeClass("adp-hide");
            });

            $("#form-empleado-2").submit(function (e) {
                e.preventDefault();
                if ($("#puesto").val() == "" || $("#departamento").val() == "" || $("#trabajador").val() == "" || $("#plaza").val() == "") {
                    md.showNotification("top", "right", "Completa todos los campos.");
                } else {
                    $.ajax({
                        type: "POST",
                        url: "assets/php/agregarEmpleado.php",
                        data: {
                            "ingreso": $("#ingreso").val(),
                            "numero": $("#numero").val(),
                            "nombre": $("#nombre").val(),
                            "rfc": $("#rfc").val(),
                            "curp": $("#curp").val(),
                            "puesto": $("#puesto option:selected").text(),
                            "departamento": $("#departamento option:selected").text(),
                            "plaza": $("#plaza").val(),
                            "banca": $("#banca").val(),
                            "afiliacion": $("#afiliacion").val(),
                            "trabajador": $("#trabajador").val(),
                            "nombres": $("#nombres").val(),
                            "apellidop": $("#apellidop").val(),
                            "apellidom": $("#apellidom").val(),
                            "plaza": $("#plaza").val()
                            // "archivo": $("#archivo").val()
                        },
                        success: function (data) {
                            $('#tabla-empleado').DataTable().ajax.reload();
                            if (data == 0) {
                                Swal.fire({
                                    title: 'Correcto',
                                    text: 'Empleado registrado',
                                    type: 'success',
                                });

                            } else if (data == 2) {
                                md.showNotification("top", "right", "Este RFC ya se encuentra registrado.");

                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'El empleado no fue registrado',
                                    type: 'error',
                                });

                            }
                        }
                    });
                }

            });
        });
    });

    $('#tabla-empleado').DataTable.ext.pager.numbers_length = 5;
    let table = $('#tabla-empleado').DataTable({
        "lengthChange": false,
        "pageLength": 10,
        "language": {
            url: "assets/js/datatables/es.json"
        },
        "ajax": {
            "type": "POST",
            "url": "assets/php/consulta-empleado.php"
        },
        "drawCallback": function (settings) {
            document.querySelector('.content').scrollTop = 1;
        },
        "columnDefs": [{
                "className": "oculto",
                "targets": [2, 3, 4]
            },
            {
                "className": "font-weight-bold",
                "targets": [0, 1]
            },
            {
                "orderable": false,
                "targets": [4, 5, 6]
            }
        ],
        "columns": [{
                "render": function (data, type, row) {
                    return '<div><i class="material-icons mr-1">fingerprint</i>' + row.id_usuario + '</div>';
                }
            },
            {
                "render": function (data, type, row) {
                    let html = "<div>" + row.nombre + "</div>" +
                        "<small>" + row.puesto + "</small>";
                    return html;
                }
            },
            {
                "data": "RFC"
            },
            {
                "data": "departamento",
            },
            {
                "render": function (data, type, row) {
                    var estado = row.estado;
                    var clase;
                    if (estado == 'alta')
                        clase = 'alta';
                    if (estado == 'baja')
                        clase = 'baja';

                    return '<a class="' + clase + '">' + estado + '</a>' + '<a class="tipo ml-3">' + row.tipoTrabajador + '</a>';
                }
            },
            {
                "render": function (data, type, row) {
                    return '<i class="material-icons btn1" onClick="editar_usuario(\'' + row.RFC + '\', event);">edit_note</i>';
                }
            },
            {
                "render": function (data, type, row) {
                    return '<i class="material-icons btn1-danger" onClick="eliminar_usuario(\'' + row.RFC + '\',event);">delete_sweep</i>';
                }
            }
        ]
    });

    $(document).on("click", "#tabla-empleado tr", function (e) {
        var data = table.row(this).data();
        ver(data[0], 0);
    });
});

function mensaje_baja(id) {
    var user = id + "";
    var idUsuario = user.split("-");
    Swal.fire({
        title: 'Usuario dado de baja',
        text: 'Este usuario se encuentra actualmente dado de baja por lo que no podrá realizar nuevos movimientos.',
        type: 'warning',
        customClass: 'animated fadeInDown'
    }).then((result) => {
        ver(idUsuario[0], 1);

    })
}


function baja(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/nuevaBaja.php",
        data: {
            "id": id
        },
        success: function (html) {
            Swal.fire({
                showCloseButton: true,
                position: 'center',
                html: html,

                showConfirmButton: false,

            });

            $.post("assets/php/fechaInicio.php", {
                "id": id
            }, function (datos) {
                var date = new Date();
                var data = JSON.parse(datos);
                date.setFullYear(data.ano, data.mes, data.dia);
                $('#fecha1').datepicker({
                    minDate: date,
                    maxDate: new Date(),
                    language: 'es',
                    autoClose: 'true',
                    position: "bottom center",
                    todayButton: new Date(),
                    onSelect(formattedDate, date, inst) {
                        if (date == '')
                            $('#fecha1').val(valor1);
                        else
                            valor1 = formattedDate;
                    }
                });
            });

            $("#form-baja").submit(function (e) {
                e.preventDefault();
                var fechaBaja = $("#fecha1").val();
                var razon = $("#razon").val()
                Swal.fire({
                    title: 'Confirmar baja de usuario',
                    html: "<p>¿Desea dar de baja a " + $("#nombre").text() + "?</p><p>Motivo de baja: " + razon + "</p>",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, continuar',
                    cancelButtonText: 'No',


                }).then(function (result) {
                    if (result.value) {
                        baja_prenomina(fechaBaja, id, razon);
                    } else if (result.dismiss == 'cancel') {
                        baja(id);
                    }
                });
            });
        }
    });
};

function password(id) {
    Swal.fire({
        title: 'Reestablecer contraseña',
        html: "<p>¿Desea reestablecer la contraseña a la predeterminada?</p>",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, continuar',
        cancelButtonText: 'No',


    }).then(function (result) {
        if (result.value) {
            reestablecer_password(id);
        } else if (result.dismiss == 'cancel') {
            ver(id, 1);
        }
    });
};

function reestablecer_password(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/reestablecer_password.php",
        data: {
            "id": id
        },
        success: function (html) {
            Swal.fire({
                title: 'Correcto',
                text: 'Contraseña reestablecida',
                type: 'success',


            }).then((result) => {
                ver(id, 1);
            });
        }
    });
}

function baja_prenomina(fechaBaja, id, razon) {
    Swal.fire({
        title: '¿El empleado fué dado de baja en el periodo anterior?',
        html: "De ser así, los días a pagar al empleado en este periodo serán 0.",
        type: 'info',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',


    }).then(function (result) {
        if (result.value) {
            baja_empleado(fechaBaja, id, razon, 1);
        } else if (result.dismiss == 'cancel') {
            baja_empleado(fechaBaja, id, razon, 0);
        }
    });
};

function baja_empleado(fechaBaja, id, razon, condicion) {
    $.ajax({
        type: "POST",
        url: "assets/php/baja.php",
        data: {
            "fecha": fechaBaja,
            "id": id,
            "razon": razon,
            "condicion": condicion
        },
        success: function (html) {
            if (html == 1) {
                Swal.fire({
                    title: 'Correcto',
                    text: 'Empleado dado de baja',
                    type: 'success'
                }).then((result) => {
                    $('#tabla-empleado').DataTable().ajax.reload();
                    ver(id, 1);
                })
            } else if (html == 2) {
                Swal.fire({
                    title: 'No fue posible dar de baja al empleado',
                    text: 'Debe esperar al menos una semana para dar de baja a este empleado',
                    type: 'error'
                }).then((result) => {
                    $('#tabla-empleado').DataTable().ajax.reload();
                    baja(id);
                })
            } else if (html == 3) {
                Swal.fire({
                    title: 'No fue posible dar de baja al empleado',
                    text: 'El empleado no cuenta con una fecha de inicio laboral válida',
                    type: 'error'
                }).then((result) => {
                    $('#tabla-empleado').DataTable().ajax.reload();
                    baja(id);
                })
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'No fue posible dar de baja al empleado',
                    type: 'error'
                }).then((result) => {
                    $('#tabla-empleado').DataTable().ajax.reload();
                    baja(id);
                })
            }
        }
    });
};

function reingreso(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/reingreso.php",
        data: {
            "id": id
        },
        success: function (html) {
            Swal.fire({
                showCloseButton: true,
                position: 'center',
                html: html,

                showConfirmButton: false,

            });

            $.post("assets/php/fechaInicio.php", {
                "id": id
            }, function (datos) {
                var date = new Date();
                var data = JSON.parse(datos);
                date.setFullYear(data.ano, data.mes, data.dia);
                $('#fecha1').datepicker({
                    minDate: date,
                    maxDate: new Date(),
                    language: 'es',
                    autoClose: 'true',
                    position: "bottom center",
                    todayButton: new Date(),
                    onSelect(formattedDate, date, inst) {
                        if (date == '')
                            $('#fecha1').val(valor1);
                        else
                            valor1 = formattedDate;
                    }
                });
            });

            $("#form-reingreso").submit(function (e) {
                e.preventDefault();
                var fechaReingreso = $("#fecha1").val();
                var observaciones = $("#observaciones").val();
                Swal.fire({
                    title: 'Confirmar reingreso de usuario',
                    html: "<p>¿Desea volver a dar de alta a " + $("#nombre").text() + "?</p>",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, continuar',
                    cancelButtonText: 'No',


                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: "assets/php/alta.php",
                            data: {
                                "fecha": fechaReingreso,
                                "id": id,
                                "observaciones": observaciones
                            },
                            success: function (html) {
                                if (html == 1) {
                                    Swal.fire({
                                        title: 'Correcto',
                                        text: 'Empleado dado de alta',
                                        type: 'success',


                                    }).then((result) => {
                                        $('#tabla-empleado').DataTable().ajax.reload();
                                        ver(id, 1);
                                    })
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'No fue posible dar de alta al empleado',
                                        type: 'error',


                                    }).then((result) => {
                                        $('#tabla-empleado').DataTable().ajax.reload();
                                        reingreso(id);
                                    })
                                }
                            }
                        });

                    } else if (result.dismiss == 'cancel') {
                        reingreso(id);
                    }
                });
            });
        }
    });
};

var valor1;
var valor2;
var valor3;

function permiso(id) {
    $.post("assets/php/verificarBaja.php", {
        "id": id
    }, function (dato) {
        if (dato == 1) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevoPermiso.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    Swal.getContent().innerHTML = html;
                    select_estilo();
                    switcher();

                    $(".sources").change(function () {
                        if ($(this).val() == 1)
                            $(".materno").addClass("hide");
                        else
                            $(".materno").removeClass("hide");

                    });

                    /*$('#fecha1').datepicker({
                        language: 'es',
                        autoClose: 'true',
                        position: "bottom center",
                        todayButton: new Date(),
                        onSelect(formattedDate, date, inst) {
                            if (date == '')
                                $('#fecha1').val(valor1);
                            else
                                valor1 = formattedDate;
                        }
                    });*/

                    $.post("assets/php/fechaInicio.php", {
                        "id": id
                    }, function (datos) {
                        valor2 = $("#fecha2").val();
                        valor3 = $("#fecha3").val();
                        diferencia_fecha(valor2, valor3);

                        var date = new Date();
                        var data = JSON.parse(datos);
                        date.setFullYear(data.ano, data.mes, data.dia);
                        $('#fecha2').datepicker({
                            minDate: date,
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            todayButton: new Date(),
                            onSelect(formattedDate, date, inst) {
                                if (date == '')
                                    $('#fecha2').val(valor2);
                                else
                                    valor2 = formattedDate;

                                diferencia_fecha(valor2, valor3);
                            }
                        });
                        $('#fecha3').datepicker({
                            minDate: date,
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            todayButton: new Date(),
                            onSelect(formattedDate, date, inst) {
                                if (date == '')
                                    $('#fecha3').val(valor3);
                                else
                                    valor3 = formattedDate;
                                diferencia_fecha(valor2, valor3);
                            }
                        });
                    });



                    $("#form-permiso").submit(function (e) {
                        e.preventDefault();
                        $.post('assets/php/comprobarFechas.php', {
                            dias: $("#dias").val(),
                            fecha1: $("#fecha2").val(),
                            fecha2: $("#fecha3").val()
                        }).done(function (dato) {
                            if (dato != 1) {
                                $("#advertencia").removeClass("hide");
                                $("#advertencia").addClass("advertencia");
                            } else {
                                var materno = 0;
                                if ($("#materno").is(':checked') && $("#sources").val() == 0)
                                    materno = 1;

                                $.ajax({
                                    type: "POST",
                                    url: "assets/php/agregarPermiso.php",
                                    data: {
                                        "fecha1": $("#fecha1").val(),
                                        "fecha2": $("#fecha2").val(),
                                        "fecha3": $("#fecha3").val(),
                                        "id": id,
                                        "categoria": $("#sources").val(),
                                        "dias": $("#dias").val(),
                                        "descripcion": $("#descripcion").val(),
                                        "materno": materno
                                    },
                                    success: function (html) {
                                        $(".continuar").prop("disabled", true);
                                        if (html == 0) {
                                            Swal.fire({
                                                title: 'Correcto',
                                                text: 'Licencia agregada',
                                                type: 'success',


                                            }).then((result) => {
                                                var idUsuario = id.split("-");
                                                verPermisos(idUsuario[0]);
                                            })
                                        } else {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'Licencia no agregada',
                                                type: 'error',


                                            }).then((result) => {
                                                var idUsuario = id.split("-");
                                                verPermisos(idUsuario[0]);
                                            })
                                        }

                                    }
                                });
                            }
                        });
                    });

                }
            });
        } else {
            mensaje_baja(id);
        }
    });

};


function ver(id, ventana, event) {
    // if(event){
    //     event.stopPropagation();
    // }
    if (ventana == 0) {
        $.ajax({
            type: "POST",
            url: "assets/php/verEmpleado.php",
            data: {
                "id": id
            },
            success: function (html) {
                Swal.fire({
                    position: 'center',
                    html: html,
                    allowOutsideClick: true,
                    showCloseButton: true,
                    showConfirmButton: false,


                });
                cambiarFoto(id);
                $("#siguiente").on('click', function () {
                    opciones(id);
                });
            }
        });
    } else if (ventana == 1) {
        opciones(id);
    }
};

function cambiarFoto(id) {
    $(".foto-empleado").on('click', function () {
        document.getElementById("input-foto").click();
    });

    $('#input-foto').on('change', function () {
        $.blockUI({
            message: "<div class='circulo'></div><h5>Cargando foto de perfil ...</h5>",
        });
        var formData = new FormData();
        var files = $(this)[0].files[0];
        formData.append('file', files);
        formData.append('id', id);
        $.ajax({
            url: 'assets/php/foto.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            enctype: 'multipart/form-data',
            cache: false,
            success: function (a) {
                $.unblockUI();
                $("#input-foto").val("");
                if (a.includes("assets/")) {
                    $(".foto-empleado").attr('src', a);
                    md.showNotification("top", "right", "Foto de perfil actualizada.");
                } else {
                    md.showNotification("top", "right", "Formato no soportado.");
                }
            }
        });
    });
};


function opciones(id) {
    $.post("assets/php/masOpciones.php", {
        id: id
    }).done(function (html) {
        Swal.fire({
            position: 'center',
            html: html,
            allowOutsideClick: true,
            showCloseButton: true,
            showConfirmButton: false,


        });

        cambiarFoto(id);

        $("#anterior").on('click', function () {
            ver(id, 0);
        });

        $("#boton-baja").on('click', function () {
            baja(id);
        });


        $("#boton-password").on('click', function () {
            password(id);
        });

        $("#boton-reingreso").on('click', function () {
            reingreso(id);
        });

        $("#pases").click(function (e) {
            e.preventDefault();
            verPases(id);
        });

        $("#vacaciones").click(function (e) {
            e.preventDefault();
            verVacaciones(id);
        });

        $("#movimientos").click(function (e) {
            e.preventDefault();
            verMovimientos(id);
        });

        $("#descuentos").click(function (e) {
            e.preventDefault();
            verDescuentos(id);
        });

        $("#expediente").click(function (e) {
            e.preventDefault();
            verExpediente(id);
        });

        $("#gastos").click(function (e) {
            e.preventDefault();
            verGastos(id);
        });

        $("#fecha-link").click(function (e) {
            e.preventDefault();
            verPermisos(id);
        });

        $("#recibos-link").click(function (e) {
            e.preventDefault();
            verNominas(id);
        });

        $("#beneficiarios-link").click(function (e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "assets/php/verBeneficiarios.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    Swal.getContent().innerHTML = html;
                }
            });
        });
    });
}

function detalle(id) {
    $.post("assets/php/detallePermiso.php", {
        "id": id
    }, function (datos) {
        var data = JSON.parse(datos);
        Swal.fire({
            position: 'center',
            html: data.html,
            allowOutsideClick: true,
            showCloseButton: true,
            showConfirmButton: false,

        });

        var fecha2 = data.fecha2.split("/");
        var fecha3 = data.fecha3.split("/");
        $('#fecha').datepicker({
            startDate: new Date(fecha2[2], fecha2[1] - 1, fecha2[0]),
            language: 'es',
            minDate: new Date(fecha2[2], fecha2[1] - 1, fecha2[0]),
            maxDate: new Date(fecha3[2], fecha3[1] - 1, fecha3[0]),
            onRenderCell: function (date, cellType) {
                var ano = date.getFullYear();
                var mes = date.getMonth() + 1;
                var dia = date.getDay();
                var fecha = date.getDate();

                if (cellType == 'day' && comprobarFecha(data.fecha2, data.fecha3, date)) {
                    return {
                        html: '<div class="celda-fecha"><p>' + fecha + '</p></div>'
                    }
                }
            },
            onSelect: function onSelect(fd, date) {}
        });
    });
};

function comprobarFecha(fechaAnterior, fechaPosterior, fecha) {

    var fecha1 = fechaAnterior.split("/");
    var fecha2 = fechaPosterior.split("/");

    var fAnt = new Date(fecha1[2], fecha1[1] - 1, fecha1[0]);
    var fPos = new Date(fecha2[2], fecha2[1] - 1, fecha2[0]);
    var fAct = fecha;

    if (fAct >= fAnt && fAct <= fPos)
        return true;
    else
        return false;
};


function verNominas(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/recibos.php",
        data: {
            "id": id
        },
        success: function (html) {
            Swal.fire({
                position: 'center',
                html: html,

                allowOutsideClick: true,
                showCloseButton: true,
                showConfirmButton: false,

            });

            select_estilo();

            tablas_nominas(id);
            $(".sources").change(function () {
                tablas_nominas(id);
            });
        }
    });
};

function tablas_nominas(id) {
    var ano = $("#ano :selected").val();
    $.ajax({
        type: "POST",
        url: "assets/php/mostrarRecibos.php",
        data: {
            "ano": ano,
            "id": id
        },
        success: function (html) {
            $(".caja-recibos").perfectScrollbar();
            $(".caja-recibos").html(html);
        }
    });
};


function tablas(id) {
    var ano = $("#ano :selected").val();
    var categoria = $("#permiso :selected").val();
    $.ajax({
        type: "POST",
        url: "assets/php/mostrarPermisos.php",
        data: {
            "ano": ano,
            "id": id,
            "categoria": categoria
        },
        success: function (html) {
            $("#caja-permiso").html(html);
        }
    });
};


function archivo2(url, id) {
    if (url === "") {
        Swal.fire({
            title: 'Sin archivo',
            text: 'No se ha encontrado ningún archivo',
            type: 'warning',


        }).then(function () {
            $.ajax({
                type: "POST",
                url: "assets/php/verBeneficiarios.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    Swal.fire({
                        html: html,

                        allowOutsideClick: true,
                        showCloseButton: true,
                        showConfirmButton: false,

                    });
                }
            });
        })
    } else
        window.open(url, '_blank');
};

function archivo(id, url, usuario, tabla, condicion) {
    if (url === "") {
        Swal.fire({
            title: 'No se ha encontrado archivo',
            html: '<div class="col-md-12"><p>Seleccione un archivo para continuar.</p></div><div class="col-md-12"><input type="file" accept=".pdf, .xlsx" id="file" /><label for="file" class="btn-3"><span><i class="material-icons">cloud_upload</i>Subir archivo</span></label></div>',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Continuar',
            cancelButtonText: 'Cancelar',


            preConfirm: () => {
                if (document.getElementById('file').value == "")
                    Swal.showValidationMessage("Seleccione un archivo")
            },
            onOpen: function () {
                $("#file").change(function () {
                    if ($("#file").val() !== "") {
                        $.blockUI({
                            message: "<div class='circulo'></div><h5>Cargando archivo ...</h5>",
                        });

                        var formData = new FormData();
                        var files = $("#file")[0].files[0];
                        formData.append("file", files);
                        formData.append("usuario", usuario);
                        formData.append("id", id);
                        formData.append("tabla", tabla);

                        $.ajax({
                            url: "assets/php/subirArchivo.php",
                            type: "post",
                            data: formData,
                            contentType: false,
                            processData: false,
                            cache: false,
                            success: function (html) {
                                url = html;
                                $.unblockUI();
                                md.showNotification("top", "right", "Archivo cargado correctamente.");
                            }
                        });
                    }
                });
            }
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "assets/php/actualizarArchivo.php",
                    data: {
                        "url": url,
                        "tabla": tabla,
                        "id": id
                    },
                    success: function () {
                        Swal.fire({
                            title: 'Correcto',
                            text: 'Archivo cargado',
                            type: 'success',


                        }).then((result) => {
                            ventanaRegresar(tabla, condicion, id, usuario);
                        })
                    }
                });
            } else if (result.dismiss == 'cancel') {
                ventanaRegresar(tabla, condicion, id, usuario);

            }
        })
    } else
        window.open(url, '_blank');
};

function ventanaRegresar(tabla, condicion, id, usuario) {
    if (tabla === "Permiso") {
        if (condicion == 0)
            verPermisos(usuario);
        else if (condicion == 1)
            detalle(id);
    } else if (tabla === "Vacacion") {
        if (condicion == 0)
            verVacaciones(usuario);
        else if (condicion == 1)
            detalle_vacacion(id);
    } else if (tabla === "Movimiento") {
        if (condicion == 0)
            verMovimientos(usuario);
        else if (condicion == 1)
            detalle_movimiento(id);
    } else if (tabla === "Descuento") {
        if (condicion == 0)
            verDescuentos(usuario);
        else if (condicion == 1)
            detalle_descuento(id);
    } else if (tabla === "Pase") {
        if (condicion == 0)
            verPases(usuario);
        else if (condicion == 1)
            detalle_pase(id);
    } else if (tabla === "Gastos") {
        if (condicion == 0)
            verGastos(usuario);
        else if (condicion == 1)
            detalle_gastos(id);
    }
};

function borrar(id) {
    Swal.fire({
        title: 'Eliminar',
        text: "¿Seguro que quieres eliminar este permiso?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, continuar',
        cancelButtonText: 'No',


    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarPermiso.php",
                data: {
                    "id": id,
                    "condicion": 1
                },
                success: function (data) {
                    Swal.fire({
                        title: 'Correcto',
                        text: 'Permiso eliminado',
                        type: 'success',


                    }).then((result) => {
                        verPermisos(data);
                    })
                }
            });
        } else if (result.dismiss == 'cancel') {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarPermiso.php",
                data: {
                    "id": id,
                    "condicion": 0
                },
                success: function (data) {
                    verPermisos(data);
                }
            });
        }
    })
};

function verPermisos(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/permisos.php",
        data: {
            "id": id
        },
        success: function (html) {
            Swal.fire({
                position: 'center',
                html: html,

                allowOutsideClick: true,
                showCloseButton: true,
                showConfirmButton: false,

            });

            select_estilo();

            tablas(id);
            $(".sources").change(function () {
                tablas(id);
            });
        }
    });
};

function verVacaciones(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/vacaciones.php",
        data: {
            "id": id
        },
        success: function (html) {
            Swal.fire({
                position: 'center',
                html: html,

                allowOutsideClick: true,
                showCloseButton: true,
                showConfirmButton: false,

            });
            select_estilo();

            tablas_vacaciones(id);
            $(".sources").change(function () {
                tablas_vacaciones(id);
            });
        }
    });
};

function vacacion(id) {
    $.post("assets/php/verificarBaja.php", {
        "id": id
    }, function (dato) {
        if (dato == 1) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevaVacacion.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    Swal.getContent().innerHTML = html;

                    // $('#fecha1').datepicker({
                    //     language: 'es',
                    //     autoClose: 'true',
                    //     position: "bottom center",
                    //     todayButton: new Date(),
                    //     onSelect(formattedDate, date, inst) {
                    //         if (date == '')
                    //             $('#fecha1').val(valor1);
                    //         else
                    //             valor1 = formattedDate;
                    //     }
                    // });
                    $.post("assets/php/fechaInicio.php", {
                        "id": id
                    }, function (datos) {

                        valor2 = $("#fecha2").val();
                        valor3 = $("#fecha3").val();
                        diferencia_fecha(valor2, valor3);
                        var date = new Date();
                        var data = JSON.parse(datos);
                        date.setFullYear(data.ano, data.mes, data.dia);
                        $('#fecha2').datepicker({
                            minDate: date,
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            todayButton: new Date(),
                            onSelect(formattedDate, date, inst) {
                                if (date == '')
                                    $('#fecha2').val(valor2);
                                else
                                    valor2 = formattedDate;
                                diferencia_fecha(valor2, valor3);
                            }
                        });
                        $('#fecha3').datepicker({
                            minDate: date,
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            todayButton: new Date(),
                            onSelect(formattedDate, date, inst) {
                                if (date == '')
                                    $('#fecha3').val(valor3);
                                else
                                    valor3 = formattedDate;
                                diferencia_fecha(valor2, valor3);
                            }
                        });
                    });

                    $("#form-vacacion").submit(function (e) {
                        e.preventDefault();
                        $.post('assets/php/comprobarFechas.php', {
                            dias: $("#dias").val(),
                            fecha1: $("#fecha2").val(),
                            fecha2: $("#fecha3").val()
                        }).done(function (dato) {
                            if (dato != 1) {
                                $("#advertencia").removeClass("hide");
                                $("#advertencia").addClass("advertencia");
                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "assets/php/agregarVacacion.php",
                                    data: {
                                        "fecha1": $("#fecha1").val(),
                                        "fecha2": $("#fecha2").val(),
                                        "fecha3": $("#fecha3").val(),
                                        "id": id,
                                        "dias": $("#dias").val(),
                                        "descripcion": $("#descripcion").val()

                                    },
                                    success: function (html) {
                                        var idUsuario = id.split("-");
                                        if (html == 0) {
                                            Swal.fire({
                                                title: 'Correcto',
                                                text: 'Vacaciones agregadas',
                                                type: 'success',


                                            }).then((result) => {
                                                verVacaciones(idUsuario[0]);
                                            })
                                        } else {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'No agregado',
                                                type: 'error',


                                            }).then((result) => {
                                                verVacaciones(idUsuario[0]);
                                            })
                                        }
                                    }
                                });
                            }
                        });

                    });
                }
            });
        } else {
            mensaje_baja(id);
        }
    });

};

function tablas_vacaciones(id) {
    var ano = $("#ano :selected").val();
    $.ajax({
        type: "POST",
        url: "assets/php/mostrarVacaciones.php",
        data: {
            "ano": ano,
            "id": id
        },
        success: function (html) {
            $(".caja-vacaciones").html(html);
        }
    });
};

function detalle_vacacion(id) {
    $.post("assets/php/detalleVacacion.php", {
        "id": id
    }, function (datos) {
        var data = JSON.parse(datos);
        Swal.fire({
            position: 'center',
            html: data.html,

            allowOutsideClick: true,
            showCloseButton: true,
            showConfirmButton: false,

        });

        var fecha2 = data.fecha2.split("/");
        var fecha3 = data.fecha3.split("/");
        $('#fecha').datepicker({
            startDate: new Date(fecha2[2], fecha2[1] - 1, fecha2[0]),
            language: 'es',
            minDate: new Date(fecha2[2], fecha2[1] - 1, fecha2[0]),
            maxDate: new Date(fecha3[2], fecha3[1] - 1, fecha3[0]),
            onRenderCell: function (date, cellType) {
                var ano = date.getFullYear();
                var mes = date.getMonth() + 1;
                var dia = date.getDay();
                var fecha = date.getDate();

                if (cellType == 'day' && comprobarFecha(data.fecha2, data.fecha3, date)) {
                    return {
                        html: '<div class="celda-fecha"><p>' + fecha + '</p></div>'
                    }
                }
            },
            onSelect: function onSelect(fd, date) {}
        });
    });
};

function borrar_vacacion(id) {
    Swal.fire({
        title: 'Eliminar',
        text: "¿Seguro que quieres eliminar este registro?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, continuar',
        cancelButtonText: 'No',


    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarVacacion.php",
                data: {
                    "id": id,
                    "condicion": 1
                },
                success: function (data) {
                    Swal.fire({
                        title: 'Correcto',
                        text: 'Registro eliminado',
                        type: 'success',


                    }).then((result) => {
                        verVacaciones(data);
                    })
                }
            });
        } else if (result.dismiss == 'cancel') {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarVacacion.php",
                data: {
                    "id": id,
                    "condicion": 0
                },
                success: function (data) {
                    verVacaciones(data);
                }
            });
        }
    })
};


function verMovimientos(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/movimientos.php",
        data: {
            "id": id
        },
        success: function (html) {
            Swal.fire({
                position: 'center',
                html: html,
                allowOutsideClick: true,
                showCloseButton: true,
                showConfirmButton: false,

            });
            select_estilo();

            tablas_movimientos(id);
            $(".sources").change(function () {
                tablas_movimientos(id);
            });
        }
    });
};

function movimiento(id) {
    $.post("assets/php/verificarBaja.php", {
        "id": id
    }, function (dato) {
        if (dato == 1) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevoMovimiento.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    Swal.fire({
                        position: 'center',
                        html: html,
                        allowOutsideClick: true,
                        showCloseButton: true,
                        showConfirmButton: false,
                    });

                    select_estilo();
                    select_change();

                    $.post("assets/php/fechaInicio.php", {
                        "id": id
                    }, function (datos) {
                        var date = new Date();
                        var data = JSON.parse(datos);
                        date.setFullYear(data.ano, data.mes, data.dia);
                        $('#fecha1').datepicker({
                            minDate: date,
                            maxDate: new Date(),
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            todayButton: new Date(),
                            onSelect(formattedDate, date, inst) {
                                if (date == '')
                                    $('#fecha1').val(valor1);
                                else
                                    valor1 = formattedDate;
                            }
                        });
                    });


                    $("#form-movimiento").click(function () {
                        var fecha = $("#fecha1").val();
                        var puesto = $("#puesto option:selected").text();
                        var departamento = $("#departamento option:selected").text();
                        var observacion = $("#observacion").val();
                        var trabajador = $("#trabajador").val();
                        var plaza = $("#plaza").val();

                        if ($("#puesto").val() == "" || $("#departamento").val() == "" || $("#trabajador").val() == "" || $("#plaza").val() == "") {
                            $("#advertencia").removeClass("hide");
                            $("#advertencia").addClass("advertencia");
                        } else {
                            Swal.fire({
                                title: 'Confirmar movimiento',
                                html: puesto + "<p class='negrita2'>Nuevo puesto</p>" + departamento + "<p class='negrita2'>Nuevo departamento</p>",
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Si, continuar',
                                cancelButtonText: 'No'
                            }).then(function (result) {
                                if (result.value) {
                                    $.ajax({
                                        type: "POST",
                                        url: "assets/php/agregarMovimiento.php",
                                        data: {
                                            "fecha": fecha,
                                            "id": id,
                                            "puesto": puesto,
                                            "departamento": departamento,
                                            "observacion": observacion,
                                            "trabajador": trabajador,
                                            "plaza": plaza
                                        },
                                        success: function (data) {
                                            // var idUsuario = id.split("-");
                                            if (data != 0) {
                                                formato_movimiento(data);
                                                Swal.fire({
                                                    title: 'Correcto',
                                                    text: 'Movimiento agregado',
                                                    type: 'success'
                                                }).then((result) => {
                                                    $('#tabla-empleado').DataTable().ajax.reload();
                                                    verMovimientos(id);
                                                })
                                            } else {
                                                Swal.fire({
                                                    title: 'Error',
                                                    text: 'Movimiento no agregado',
                                                    type: 'error'
                                                }).then((result) => {
                                                    verMovimientos(id);
                                                })
                                            }
                                        }
                                    });

                                } else if (result.dismiss == 'cancel') {
                                    movimiento(id);
                                }
                            });
                        }


                    });
                }
            });
        } else {
            mensaje_baja(id);
        }
    });

};


function detalle_movimiento(id) {
    $.post("assets/php/detalleMovimiento.php", {
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

function tablas_movimientos(id) {
    var ano = $("#ano :selected").val();
    $.ajax({
        type: "POST",
        url: "assets/php/mostrarMovimientos.php",
        data: {
            "ano": ano,
            "id": id
        },
        success: function (html) {
            $(".caja-movimientos").html(html);
        }
    });
};


function borrar_movimiento(id) {
    $.post("assets/php/comprobarMovimiento.php", {
        "id": id
    }, function (salida) {
        var array = JSON.parse(salida);
        if (parseInt(array.maximo) != parseInt(id)) {
            Swal.fire({
                title: 'No se pudo eliminar',
                text: "Solo puedes eliminar el último movimiento realizado",
                type: 'warning',
                confirmButtonText: 'Aceptar',


            }).then(function () {
                verMovimientos(array.usuario);
            });
        } else {
            Swal.fire({
                title: 'Eliminar',
                text: "¿Seguro que quieres eliminar este registro?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si, continuar',
                cancelButtonText: 'No',


            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "assets/php/eliminarMovimiento.php",
                        data: {
                            "id": id,
                            "condicion": 1
                        },
                        success: function (data) {
                            Swal.fire({
                                title: 'Correcto',
                                text: 'Registro eliminado',
                                type: 'success',


                            }).then((result) => {
                                $('#tabla-empleado').DataTable().ajax.reload();
                                verMovimientos(data);
                            })
                        }
                    });
                } else if (result.dismiss == 'cancel') {
                    $.ajax({
                        type: "POST",
                        url: "assets/php/eliminarMovimiento.php",
                        data: {
                            "id": id,
                            "condicion": 0
                        },
                        success: function (data) {
                            verMovimientos(data);
                        }
                    });
                }
            });
        }
    });
};

function verExpediente(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/expediente.php",
        data: {
            "id": id
        },
        success: function (html) {
            Swal.fire({
                position: 'center',
                html: html,

                allowOutsideClick: false,
                showCloseButton: true,
                showConfirmButton: false,

                width: '50em'
            });

            expediente_menu(id);
        }
    });
};

function expediente_menu(id) {
    var nombre = "";
    $("#expediente_file").on('change', function () {
        if ($(this).val() !== "" && nombre !== "") {
            $.blockUI({
                message: "<div class='circulo'></div><h5>Cargando archivo ...</h5>",
            });

            var formData = new FormData();
            var files = $(this)[0].files[0];
            formData.append("file", files);
            formData.append("nombre", nombre);
            formData.append("id", id);

            $.ajax({
                url: "assets/php/expedienteArchivo.php",
                type: "post",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function (data) {
                    $("#expediente_file").val("");

                    $.ajax({
                        type: "POST",
                        url: "assets/php/expediente.php",
                        data: {
                            "id": id
                        },
                        success: function (html) {
                            Swal.getContent().innerHTML = html;
                            expediente_menu(id);
                        }
                    });

                    $.unblockUI();
                    md.showNotification("top", "right", "Archivo cargado correctamente.");

                }
            });
        }
    });

    $(".descargar").click(function () {
        nombre = this.name + "";
        descargar_expediente(id, nombre);
    });

    $(".acta").click(function () {
        nombre = 'acta';
        $("#expediente_file").click();
    });

    $(".curp").click(function () {
        nombre = 'curp';
        $("#expediente_file").click();
    });

    $(".curriculum").click(function () {
        nombre = 'curriculum';
        $("#expediente_file").click();
    });

    $(".antecedentes").click(function () {
        nombre = 'antecedentes';
        $("#expediente_file").click();
    });

    $(".disciplinarios").click(function () {
        nombre = 'disciplinarios';
        $("#expediente_file").click();
    });

    $(".identificacion").click(function () {
        nombre = 'identificacion';
        $("#expediente_file").click();
    });
}

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

function eliminar_expediente(id, nombre) {
    Swal.fire({
        title: 'Eliminar',
        text: "¿Seguro que quieres eliminar este archivo?",
        type: 'warning',
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: 'Si, continuar',
        cancelButtonText: 'No',


    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarExpediente.php",
                data: {
                    "id": id,
                    "nombre": nombre
                },
                success: function (data) {
                    Swal.fire({
                        title: 'Correcto',
                        text: 'Archivo eliminado',
                        type: 'success',


                    }).then((result) => {
                        verExpediente(id);
                    })
                }
            });
        } else if (result.dismiss == 'cancel') {
            verExpediente(id);
        }
    })
};

function verGastos(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/gastos.php",
        data: {
            "id": id
        },
        success: function (html) {
            Swal.fire({
                position: 'center',
                html: html,

                allowOutsideClick: true,
                showCloseButton: true,
                showConfirmButton: false,

            });
            select_estilo();
            tablas_gastos(id);
            $(".sources").change(function () {
                tablas_gastos(id);
            });
        }
    });
};


function gastos(id) {
    $.post("assets/php/verificarBaja.php", {
        "id": id
    }, function (dato) {
        if (dato == 1) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevoGastos.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    Swal.getContent().innerHTML = html;

                    $('#monto').keypress(function (event) {
                        if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
                                $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                            event.preventDefault();
                        }
                    }).on('paste', function (event) {
                        event.preventDefault();
                    });

                    $.post("assets/php/fechaInicio.php", {
                        "id": id
                    }, function (datos) {
                        var date = new Date();
                        var data = JSON.parse(datos);
                        date.setFullYear(data.ano, data.mes, data.dia);

                        $('#fecha').datepicker({
                            minDate: date,
                            maxDate: new Date(),
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            todayButton: new Date(),
                            onSelect(formattedDate, date, inst) {
                                if (date == '')
                                    $('#fecha').val(valor1);
                                else
                                    valor1 = formattedDate;
                            }
                        });
                    });


                    $("#form-gastos").submit(function (e) {
                        e.preventDefault();
                        if ($("#monto").val() <= 0) {
                            $("#advertencia").removeClass("hide");
                            $("#advertencia").addClass("advertencia");
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "assets/php/agregarGastos.php",
                                data: {
                                    "fecha": $("#fecha").val(),
                                    "id": id,
                                    "monto": $("#monto").val(),
                                    "concepto": $("#concepto").val(),
                                    "nombre": $("#nombre").val()
                                },
                                success: function (html) {
                                    var idUsuario = id.split("-");
                                    if (html == 0) {
                                        Swal.fire({
                                            title: 'Correcto',
                                            text: 'Gastos médicos agregados',
                                            type: 'success',


                                        }).then((result) => {
                                            verGastos(idUsuario[0]);
                                        })
                                    } else {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'No agregado',
                                            type: 'error',


                                        }).then((result) => {
                                            verGastos(idUsuario[0]);
                                        })
                                    }
                                }
                            });
                        }
                    });
                }
            });
        } else {
            mensaje_baja(id);
        }
    });
};

function tablas_gastos(id) {
    var ano = $("#ano :selected").val();
    var mes = $("#mes :selected").val();
    $.ajax({
        type: "POST",
        url: "assets/php/mostrarGastos.php",
        data: {
            "ano": ano,
            "mes": mes,
            "id": id
        },
        success: function (html) {
            $(".caja-gastos").html(html);
        }
    });
};


function borrar_gastos(id) {
    Swal.fire({
        title: 'Eliminar',
        text: "¿Seguro que quieres eliminar este registro?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, continuar',
        cancelButtonText: 'No',


    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarGastos.php",
                data: {
                    "id": id,
                    "condicion": 1
                },
                success: function (data) {
                    Swal.fire({
                        title: 'Correcto',
                        text: 'Registro eliminado',
                        type: 'success',


                    }).then((result) => {
                        verGastos(data);
                    })
                }
            });
        } else if (result.dismiss == 'cancel') {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarGastos.php",
                data: {
                    "id": id,
                    "condicion": 0
                },
                success: function (data) {
                    verGastos(data);
                }
            });
        }
    })
};

function detalle_gastos(id) {
    $.post("assets/php/detalleGastos.php", {
        "id": id
    }, function (datos) {
        var data = JSON.parse(datos);
        Swal.fire({
            position: 'center',
            html: data.html,

            allowOutsideClick: true,
            showCloseButton: true,
            showConfirmButton: false,

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

function verDescuentos(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/descuentos.php",
        data: {
            "id": id
        },
        success: function (html) {
            Swal.fire({
                position: 'center',
                html: html,

                allowOutsideClick: true,
                showCloseButton: true,
                showConfirmButton: false,

            });
            select_estilo();
            tablas_descuentos(id);
            $(".sources").change(function () {
                tablas_descuentos(id);
            });
        }
    });
};

function descuento(id) {
    $.post("assets/php/verificarBaja.php", {
        "id": id
    }, function (dato) {
        if (dato == 1) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevoDescuento.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    Swal.getContent().innerHTML = html;
                    $('#dias').focusin();
                    $('#dias').val("0");

                    $.post("assets/php/fechaInicio.php", {
                        "id": id
                    }, function (datos) {
                        var date = new Date();
                        var data = JSON.parse(datos);
                        date.setFullYear(data.ano, data.mes, data.dia);
                        $('#fecha').datepicker({
                            minDate: date,
                            maxDate: new Date(),
                            language: 'es',
                            multipleDates: true,
                            multipleDatesSeparator: ",",
                            onSelect(formattedDate, date, inst) {
                                if (date == '')
                                    $('#dias').val("0");
                                else {
                                    $('#fecha1').val(formattedDate);
                                    var fechas = $('#fecha1').val().split(",");
                                    $('#dias').val(fechas.length);
                                }
                            }
                        });
                    });


                    $("#form-descuento").submit(function (e) {
                        e.preventDefault();
                        if ($("#dias").val() == 0) {
                            $("#advertencia").removeClass("hide");
                            $("#advertencia").addClass("advertencia");
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "assets/php/agregarDescuento.php",
                                data: {
                                    "fecha1": $("#fecha1").val(),
                                    "id": id,
                                    "dias": $("#dias").val(),
                                    "motivo": $("#motivo").val()
                                },
                                success: function (html) {
                                    var idUsuario = id.split("-");
                                    if (html == 0) {
                                        Swal.fire({
                                            title: 'Correcto',
                                            text: 'Descuento agregado',
                                            type: 'success',


                                        }).then((result) => {
                                            verDescuentos(idUsuario[0]);
                                        })
                                    } else {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'No agregado',
                                            type: 'error',


                                        }).then((result) => {
                                            verDescuentos(idUsuario[0]);
                                        })
                                    }
                                }
                            });
                        }
                    });
                }
            });
        } else {
            mensaje_baja(id);
        }
    });
};

function tablas_descuentos(id) {
    var ano = $("#ano :selected").val();
    var mes = $("#mes :selected").val();
    $.ajax({
        type: "POST",
        url: "assets/php/mostrarDescuentos.php",
        data: {
            "ano": ano,
            "mes": mes,
            "id": id
        },
        success: function (html) {
            $(".caja-descuentos").html(html);
        }
    });
};

function borrar_descuento(id) {
    Swal.fire({
        title: 'Eliminar',
        text: "¿Seguro que quieres eliminar este registro?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, continuar',
        cancelButtonText: 'No',


    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarDescuento.php",
                data: {
                    "id": id,
                    "condicion": 1
                },
                success: function (data) {
                    Swal.fire({
                        title: 'Correcto',
                        text: 'Registro eliminado',
                        type: 'success',


                    }).then((result) => {
                        verDescuentos(data);
                    })
                }
            });
        } else if (result.dismiss == 'cancel') {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarDescuento.php",
                data: {
                    "id": id,
                    "condicion": 0
                },
                success: function (data) {
                    verDescuentos(data);
                }
            });
        }
    })
};

function detalle_descuento(id) {
    $.post("assets/php/detalleDescuento.php", {
        "id": id
    }, function (datos) {
        var data = JSON.parse(datos);
        Swal.fire({
            position: 'center',
            html: data.html,

            allowOutsideClick: true,
            showCloseButton: true,
            showConfirmButton: false,

        });

        var fechas = data.fechas.split(",");
        var fecha1 = fechas[0].split("/");
        var fechasDate = [];
        for (var i = 0; i < fechas.length; i++) {
            var fecha2 = fechas[i].split("/");
            fechasDate.push(new Date(fecha2[2], fecha2[1] - 1, fecha2[0]));
        }
        var max = new Date(Math.max.apply(null, fechasDate));
        var min = new Date(Math.min.apply(null, fechasDate));

        $('#fecha').datepicker({
            startDate: min,
            minDate: min,
            maxDate: max,
            language: 'es',
            onRenderCell: function (date, cellType) {
                var ano = date.getFullYear();
                var mes = date.getMonth() + 1;
                var dia = date.getDay();
                var fecha = date.getDate();
                if (cellType == 'day' && comparar_fechas(fechas, date)) {
                    return {
                        html: '<div class="celda-fecha"><p>' + fecha + '</p></div>'
                    }
                }
            },
            onSelect: function onSelect(fd, date) {}
        });
    });
};

function comparar_fechas(fechas, fecha) {
    var y = 0;
    for (var i = 0; i < fechas.length; i++) {
        var fecha1 = fechas[i].split("/");
        var fecha2 = new Date(fecha1[2], fecha1[1] - 1, fecha1[0]);
        if (fecha2.getTime() === fecha.getTime()) {
            y = 1;
            break;
        }
    }
    if (y == 1)
        return true;
    else
        return false;
};

function eliminar_usuario(id, event) {
    event.stopPropagation();
    Swal.fire({
        title: '¿Estás seguro de eliminar?',
        text: "Esto eliminará toda información del empleado",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, continuar',
        cancelButtonText: 'No',


    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminar_empleado.php",
                data: {
                    "id": id
                },
                success: function (data) {
                    if (data == 1) {
                        $('#tabla-empleado').DataTable().ajax.reload();
                        Swal.fire({
                            title: 'Correcto',
                            text: 'Empleado eliminado',
                            type: 'success',


                        })
                    } else if (data == 2) {
                        no_pasar();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Empleado no eliminado',
                            type: 'error',


                        })
                    }

                }
            });
        }
    })
};

function editar_usuario(id, event) {
    event.stopPropagation();
    mensaje_cargar();
    $.post("assets/php/verificarBaja.php", {
        "id": id
    }, function (dato) {
        if (dato == 1) {
            $.post("assets/php/editarEmpleado.php", {
                id: id
            }, function (html) {
                Swal.fire({
                    position: 'center',
                    html: html,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    width: '50em'
                });
                // select_estilo();
                // select_change_update();
                $("#ingreso").blur();

                // $("#descargar-btn").click(function () {
                //     descargar($("#descargar-input").val(), 'Archivo')
                // });

                // $("#file").change(function () {
                //     if ($("#file").val() !== "") {
                //         $.blockUI({
                //             message: "<div class='circulo'></div><h5>Cargando archivo ...</h5>",
                //         });

                //         var formData = new FormData();
                //         var files = $("#file")[0].files[0];
                //         formData.append("file", files);

                //         $.ajax({
                //             url: "assets/php/empleadoArchivo.php",
                //             type: "post",
                //             data: formData,
                //             contentType: false,
                //             processData: false,
                //             cache: false,
                //             success: function (data) {
                //                 $("#archivo").val(data);
                //                 $("#file").val("");
                //                 $.unblockUI();
                //                 md.showNotification("top", "right", "Archivo cargado correctamente.");
                //             }
                //         });
                //     }
                // });

                $(".readonly").keydown(function (e) {
                    e.preventDefault();
                });

                $('#ingreso').datepicker({
                    language: 'es',
                    autoClose: 'true',
                    position: "bottom center",
                    todayButton: new Date(),
                    onSelect(formattedDate, date, inst) {
                        if (date == '')
                            $('#ingreso').val(valor1);
                        else
                            valor1 = formattedDate;
                    }
                });

                $("#form-empleado").submit(function (e) {
                    e.preventDefault();

                    $.ajax({
                        type: "POST",
                        url: "assets/php/actualizarEmpleado.php",
                        data: {
                            "id": $("#numero").val(),
                            "rfc": $("#rfc").val(),
                            "curp": $("#curp").val(),
                            // "puesto": $("#puesto").val(),
                            // "departamento": $("#departamento").val(),
                            "banca": $("#banca").val(),
                            "afiliacion": $("#afiliacion").val(),
                            "nombres": $("#nombres").val(),
                            "apellidop": $("#apellidop").val(),
                            "apellidom": $("#apellidom").val(),
                            // "trabajador": $("#trabajador").val(),
                            "archivo": $("#archivo").val(),
                            "ingreso": $("#ingreso").val()
                        },
                        success: function (data) {
                            $('#tabla-empleado').DataTable().ajax.reload();
                            if (data == 1) {
                                Swal.fire({
                                    title: 'Correcto',
                                    text: 'Datos de empleado actualizados',
                                    type: 'success'
                                })
                            } else if (data == 2) {
                                no_pasar();
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Datos de empleado no actualizados',
                                    type: 'error',


                                })
                            }
                        }
                    });

                    // if ($("#puesto").val() === "" || $("#departamento").val() === "" || $("#trabajador").val() === "") {
                    //     $("#advertencia").removeClass("hide");
                    //     $("#advertencia").addClass("advertencia");
                    // } else {

                    // }

                });

            });
        } else {
            Swal.fire({
                title: 'Usuario dado de baja',
                text: 'Este usuario se encuentra actualmente dado de baja por lo que no podrá realizar nuevos movimientos.',
                type: 'warning',
                customClass: 'animated fadeInDown'
            })
        }
    });
};

function generar_empleados() {
    mensaje_cargar();
    $.post("assets/php/generarExcel.php", function (data) {
        swal.close();
        if (data !== 0) {
            descargar(data, 'Empleados');
        } else {

        }
    });
};

function verPases(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/pases.php",
        data: {
            "id": id
        },
        success: function (html) {
            Swal.fire({
                position: 'center',
                html: html,

                allowOutsideClick: true,
                showCloseButton: true,
                showConfirmButton: false,

            });
            select_estilo();
            tablas_pases(id);
            $(".sources").change(function () {
                tablas_pases(id);
            });
        }
    });
};

function pase(id) {
    $.post("assets/php/verificarBaja.php", {
        "id": id
    }, function (dato) {
        if (dato == 1) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevoPase.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    Swal.getContent().innerHTML = html;

                    $.post("assets/php/fechaInicio.php", {
                        "id": id
                    }, function (datos) {
                        var date = new Date();
                        var data = JSON.parse(datos);
                        date.setFullYear(data.ano, data.mes, data.dia);
                        $('#fecha').datepicker({
                            minDate: date,
                            language: 'es',
                            timepicker: true,
                            onSelect(formattedDate, date, inst) {
                                $("#temporal").val(formattedDate);
                            }
                        });
                    });

                    select_estilo();

                    $("#form-pase").submit(function (e) {
                        e.preventDefault();
                        if ($("#temporal").val() === "") {
                            $("#advertencia").removeClass("hide");
                            $("#advertencia").addClass("advertencia");
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "assets/php/agregarPase.php",
                                data: {
                                    "fecha": $("#temporal").val(),
                                    "id": id,
                                    "categoria": $("#categoria").val(),
                                    "observacion": $("#observacion").val(),
                                },
                                success: function (html) {
                                    var idUsuario = id.split("-");
                                    if (html == 1) {
                                        Swal.fire({
                                            title: 'Correcto',
                                            text: 'Pase agregado',
                                            type: 'success',
                                        }).then((result) => {
                                            verPases(idUsuario[0]);
                                        })
                                    } else if (html == 2) {
                                        Swal.fire({
                                            title: 'Advertencia',
                                            text: 'Debe especificar fecha y hora',
                                            type: 'warning',


                                        }).then((result) => {
                                            verPases(idUsuario[0]);
                                        })
                                    } else {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'No agregado',
                                            type: 'error',


                                        }).then((result) => {
                                            verPases(idUsuario[0]);
                                        })
                                    }
                                }
                            });
                        }

                    });
                }
            });
        } else {
            mensaje_baja(id);
        }
    });

};

function tablas_pases(id) {
    var ano = $("#ano :selected").val();
    var mes = $("#mes :selected").val();
    var categoria = $("#categoria :selected").val();
    $.ajax({
        type: "POST",
        url: "assets/php/mostrarPases.php",
        data: {
            "ano": ano,
            "mes": mes,
            "id": id,
            "categoria": categoria
        },
        success: function (html) {
            $(".caja-pases").html(html);
        }
    });
};

function borrar_pase(id) {
    Swal.fire({
        title: 'Eliminar',
        text: "¿Seguro que quieres eliminar este registro?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, continuar',
        cancelButtonText: 'No',


    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarPase.php",
                data: {
                    "id": id,
                    "condicion": 1
                },
                success: function (data) {
                    Swal.fire({
                        title: 'Correcto',
                        text: 'Registro eliminado',
                        type: 'success',


                    }).then((result) => {
                        verPases(data);
                    })
                }
            });
        } else if (result.dismiss == 'cancel') {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarPase.php",
                data: {
                    "id": id,
                    "condicion": 0
                },
                success: function (data) {
                    verPases(data);
                }
            });
        }
    })
};

function detalle_pase(id) {
    $.post("assets/php/detallePase.php", {
        "id": id
    }, function (datos) {
        var data = JSON.parse(datos);
        Swal.fire({
            position: 'center',
            html: data.html,

            allowOutsideClick: true,
            showCloseButton: true,
            showConfirmButton: false,

        });
        var date1 = data.fecha.split("/");
        var date2 = new Date(date1[2], parseInt(date1[1]) - 1, date1[0]);
        var hora1 = data.hora.split(" ");
        var hora2 = hora1[0].split(":");
        var hora = parseInt(hora2[0]);
        var minutos = parseInt(hora2[1]);
        $('#fecha').datepicker({
            startDate: date2,
            timepicker: true,
            language: 'es',
            minHours: hora,
            minMinutes: minutos,
            maxHours: hora,
            maxMinutes: minutos,
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

function switcher() {
    $('.cb-value').click(function () {
        var mainParent = $(this).parent('.toggle-btn');
        if ($(mainParent).find('input.cb-value').is(':checked')) {
            $(mainParent).addClass('active');
        } else {
            $(mainParent).removeClass('active');
        }
    });
};

function formato_movimiento(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/formatoMovimiento.php",
        data: {
            "id": id
        },
        success: function (url) {
            descargar(url, "Movimiento");
        }
    });
}

function eliminar_archivo(id, tabla) {
    Swal.fire({
        title: 'Eliminar',
        text: "¿Seguro que quieres eliminar el archivo actual?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, continuar',
        cancelButtonText: 'No',


    }).then(function (result) {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarArchivo.php",
                data: {
                    "id": id,
                    "tabla": tabla
                },
                success: function (data) {
                    Swal.fire({
                        title: 'Correcto',
                        text: 'Archivo eliminado',
                        type: 'success',


                    }).then((result) => {
                        ventanaRegresar(tabla, 1, id, 0);
                    })
                }
            });
        } else if (result.dismiss == 'cancel') {
            ventanaRegresar(tabla, 1, id, 0);
        }
    })

}

function diferencia_fecha(fecha1, fecha2) {
    var a = moment(fecha1, 'D/M/YYYY').subtract(1, 'days');;
    var b = moment(fecha2, 'D/M/YYYY');
    var diffDays = b.diff(a, 'days');
    if (diffDays <= 0)
        diffDays = 0;
    $("#dias").val(diffDays);
}

// function select_change() {
//     $("#departamento").on("change", function () {
//         $.ajax({
//             url: "assets/php/depa_change.php",
//             type: "POST",
//             data: {
//                 departamento: $("#departamento").val(),
//             },
//             success: function (data) {
//                 $("#puesto").html(data);
//                 tail.select("#puesto").reload();
//             }
//         });
//     });

//     $("#departamento").change();
// }

// function select_change_update() {
//     $("#departamento").on("change", function () {
//         $.ajax({
//             url: "assets/php/depa_change_update.php",
//             type: "POST",
//             data: {
//                 departamento: $("#departamento").val(),
//                 RFC: $("#rfc").val(),
//             },
//             success: function (data) {
//                 $("#puesto").html(data);
//                 tail.select("#puesto").reload();
//             }
//         });
//     });

//     $("#departamento").change();
// }