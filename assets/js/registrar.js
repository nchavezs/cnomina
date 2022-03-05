var rfc_global = "";

if ($(window).width() < 767) {
    $(document).on("click", ".ver_panel p", function () {
        $(".ver_contenedor").show();
        $(".ver_panel").addClass("adp-hide");

    });

    $(document).on("click", ".ver_boton .regresar", function () {
        ADP.show($(".ver_panel")[0], 'slide-left');
        $(".ver_panel").removeClass("adp-hide");

        $(".ver_contenedor").hide();
    });
}

$(document).ready(function () {
    select_estilo_3();

    $(".opciones_tabla select").change(function () {
        $('#tabla-empleado').DataTable().ajax.reload();
    });

    $("#importar-empleado").change(function () {
        if ($(this).val() !== "") {
            mensaje_cargar();

            var formData = new FormData();
            var files = $(this)[0].files[0];
            formData.append("file", files);

            $.ajax({
                url: "assets/php/importar_empleados.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function (datos) {
                    var data = JSON.parse(datos);
                    if (data.formato == true) {
                        Swal.fire({
                            position: 'center',
                            html: data.html,
                            showCloseButton: true,
                            showConfirmButton: false,
                        });
                        $(".log").perfectScrollbar();
                        $('#tabla-empleado').DataTable().ajax.reload();
                    } else {
                        Swal.close();
                        md.showNotification("top", "right", "Formato de archivo incorrecto.");
                    }
                    $("#importar-empleado").val("");
                }
            });
        }
    });

    $("#importar-empleado-puesto").change(function () {
        if ($(this).val() !== "") {
            mensaje_cargar();

            var formData = new FormData();
            var files = $(this)[0].files[0];
            formData.append("file", files);

            $.ajax({
                url: "assets/php/importar_empleados_puesto.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function (datos) {
                    var data = JSON.parse(datos);
                    if (data.formato == true) {
                        Swal.fire({
                            position: 'center',
                            html: data.html,
                            showCloseButton: true,
                            showConfirmButton: false,
                        });
                        $(".log").perfectScrollbar();
                        $('#tabla-empleado').DataTable().ajax.reload();
                    } else {
                        Swal.close();
                        md.showNotification("top", "right", "Formato de archivo incorrecto.");
                    }
                    $("#importar-empleado-puesto").val("");
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
            depa_change();
            puesto_change();
            $("#nombre").blur();


            $.post("assets/php/verificar_tope", function (datos) {
                let data = JSON.parse(datos);
                $('#fecha').datepicker({
                    minDate: new Date(data.del),
                    maxDate: new Date(data.al),
                    language: 'es',
                    autoClose: 'true',
                    toggleSelected: false,
                    onSelect(formattedDate, date, inst) {
                        $("#puesto").change();
                    }
                });
            });

            $("#form-empleado-1").submit(function (e) {
                e.preventDefault();
                $(".pagina_1").addClass("adp-hide");
                ADP.show($(".pagina_2")[0], 'fade');
                $(".pagina_2").removeClass("adp-hide");
            });

            $("#form-empleado-2").submit(function (e) {
                e.preventDefault();
                if ($("#plaza").val() == null) {
                    md.showNotification("top", "right", "Completa todos los campos.");
                } else {
                    let retroactivo = 0;
                    if ($("#retroactivo").is(":checked")) {
                        retroactivo = 1;
                    }
                    $.ajax({
                        type: "POST",
                        url: "assets/php/agregarEmpleado.php",
                        data: {
                            "ingreso": $("#fecha").val(),
                            "numero": $("#numero").val(),
                            "nombre": $("#nombre").val(),
                            "rfc": $("#rfc").val(),
                            "curp": $("#curp").val(),
                            "puesto": $("#puesto").val(),
                            "plaza": $("#plaza").val(),
                            "banca": $("#banca").val(),
                            "afiliacion": $("#afiliacion").val(),
                            "nombres": $("#nombres").val(),
                            "apellidop": $("#apellidop").val(),
                            "apellidom": $("#apellidom").val(),
                            "plaza": $("#plaza").val(),
                            "periodo": $("#periodo").val(),
                            "domicilio": $("#domicilio").val(),
                            "email": $("#email").val(),
                            "retroactivo": retroactivo
                        },
                        success: function (data) {
                            if (data == 1) {
                                Swal.fire({
                                    title: 'Correcto',
                                    text: 'Empleado registrado',
                                    type: 'success',
                                });
                                $('#tabla-empleado').DataTable().ajax.reload();
                            } else if (data == 2) {
                                md.showNotification("top", "right", "Este RFC ya se encuentra registrado.");
                            } else if (data == 3) {
                                md.showNotification("top", "right", "Este número de empleado ya se encuentra registrado.");
                            } else {
                                md.showNotification("top", "right", "Error el empleado no fue registrado.");
                            }
                        }
                    });
                }
            });
        });
    });

    let table = $('#tabla-empleado').DataTable({
        "lengthChange": false,
        "pageLength": 10,
        "language": {
            url: "assets/js/datatables/es.json"
        },
        "ajax": {
            "type": "POST",
            "url": "assets/php/consulta-empleado.php",
            "data": function (d) {
                d.estado = $("#estado").val();
            }
        },
        "drawCallback": function (settings) {
            $('.main-panel .content').perfectScrollbar('update');
            ADP.show($(".table-responsive")[0], 'slide-left');
        },
        "columnDefs": [{
                "className": "oculto",
                "targets": [2, 3, 4]
            },
            {
                "className": "negrita",
                "targets": [0]
            },
            {
                "orderable": false,
                "targets": [4, 5]
            }
        ],
        "columns": [
            {
                "render": function(data,type,row){
                    return "<i class='material-icons'>fingerprint</i> "+row.id_empleado;
                }
            },
            {
                "data": "nombre",
            },
            {
                "data": "puesto",
            },
            {
                "data": "departamento",
            },
            {
                "render": function (data, type, row) {
                    return '<a class="tipo">' + row.tipoTrabajador + '</a>';
                }
            },
            {
                "render": function (data, type, row) {

                    return '<span class="boton_tabla text-primary mr-3" onclick="editar_usuario(\'' + row.RFC + '\', event);"> <i class="material-icons">edit</i>  </span>'+
                    '<span class="boton_tabla text-danger" onclick="eliminar_usuario(\'' + row.RFC + '\',event);"><i class="material-icons">delete</i> </span>';
                }
            }
        ]
    });

    $(document).on("click", "#tabla-empleado tbody tr", function (e) {
        var data = table.row(this).data();
        ver(data[0], 0);
        //error consola en empty row
    });

    $(document).on("click", ".foto_usuario", function () {
        $("#input-foto").click();
    });

    $(document).on('change', '#input-foto', function () {
        $.blockUI({
            message: "<div class='circulo'></div><h5>Cargando foto de perfil ...</h5>",
        });
        var formData = new FormData();
        var files = $(this)[0].files[0];
        formData.append('file', files);
        formData.append('id', rfc_global);
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
                    $(".foto").attr('src', a);
                    md.showNotification("top", "right", "Foto de perfil actualizada.");
                } else {
                    md.showNotification("top", "right", "Formato no soportado.");
                }
            }
        });
    });
});

function mensaje_error(data) {
    $("#modal .modal_titulo").html(data.titulo);
    $("#modal .modal-body").html(data.mensaje);
    mostrar_modal();
    $("#modal .modal-footer").hide();
}


function baja(id) {
    $.post("assets/php/verificar_periodo.php", {
        "id": id
    }, function (datos) {
        let data = JSON.parse(datos);
        if (data.success) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevaBaja.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    $(".ver_contenedor").html(html);
                    $.post("assets/php/fecha_movimiento.php", {
                        "id": id
                    }, function (datos) {
                        let data = JSON.parse(datos);
                        $('#fecha').datepicker({
                            minDate: new Date(data.del),
                            maxDate: new Date(data.al),
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            // todayButton: new Date(),
                            toggleSelected: false
                        });
                    });

                    $("#form-baja").submit(function (e) {
                        e.preventDefault();
                        let fechaBaja = $("#fecha").val();
                        let razon = $("#razon").val();
                        let retroactivo = 0;
                        if ($("#retroactivo").is(":checked")) {
                            retroactivo = 1;
                        }

                        $("#modal .modal_titulo").html("Confirmar baja de empleado");
                        $("#modal .modal-body").html("¿Seguro que quiere dar de baja a " + $("#nombre").text() + "?");
                        mostrar_modal();

                        $("#modal_aceptar").off().click(function () {
                            baja_empleado(fechaBaja, id, razon, retroactivo);
                        })
                    });
                }
            });
        } else {
            mensaje_error(data);
        }
    });
};

function password(id) {
    $("#modal .modal_titulo").html("Restablecer contraseña");
    $("#modal .modal-body").html('¿Restablecer contraseña del usuario a la predeterminada?');
    mostrar_modal();
    $("#modal_aceptar").off().click(function () {
        reestablecer_password(id);
        ocultar_modal();
    })

};

function reestablecer_password(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/reestablecer_password.php",
        data: {
            "id": id
        },
        success: function (html) {
            md.showNotification("top", "right", "Contraseña restablecida correctamente.");

        }
    });
}

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
            ocultar_modal();
            if (html == 1) {
                $('#tabla-empleado').DataTable().ajax.reload();
                md.showNotification("top", "right", "Empleado dado de baja correctamente.");
                verHistorial(id);
            } else if (html == 2) {
                md.showNotification("top", "right", "Espere 24 hrs para dar de baja a este empleado.");
            } else {
                md.showNotification("top", "right", "No fue posible dar de baja a este empleado.");
            }
        }
    });
};

function reingreso(id) {
    $.post("assets/php/verificar_periodo.php", {
        "id": id
    }, function (datos) {
        let data = JSON.parse(datos);
        if (data.success) {
            $.ajax({
                type: "POST",
                url: "assets/php/reingreso.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    $(".ver_contenedor").html(html);
                    select_estilo();
                    depa_change();
                    puesto_change();

                    $.post("assets/php/fecha_movimiento.php", {
                        "id": id
                    }, function (datos) {
                        let data = JSON.parse(datos);
                        $('#fecha').datepicker({
                            minDate: new Date(data.del),
                            maxDate: new Date(data.al),
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            // todayButton: new Date(),
                            toggleSelected: false,
                            onSelect(formattedDate, date, inst) {
                                $("#puesto").change();
                            }
                        });
                    });

                    $("#form-reingreso").submit(function (e) {
                        e.preventDefault();
                        var fechaReingreso = $("#fecha").val();
                        var observacion = $("#observacion").val();
                        var plaza = $("#plaza").val();

                        if (plaza != null) {
                            $("#modal .modal_titulo").html("Confirmar reingreso de empleado");
                            $("#modal .modal-body").html("¿Dar de alta a " + $("#nombre").val() + "?");

                            mostrar_modal();

                            $("#modal_aceptar").off().click(function () {
                                $.ajax({
                                    type: "POST",
                                    url: "assets/php/alta.php",
                                    data: {
                                        "fecha": fechaReingreso,
                                        "id": id,
                                        "observacion": observacion,
                                        "plaza": plaza
                                    },
                                    success: function (data) {
                                        ocultar_modal();
                                        if (data == 1) {
                                            md.showNotification("top", "right", "Empleado dado de alta correctamente.");
                                            $('#tabla-empleado').DataTable().ajax.reload();
                                            verHistorial(id);
                                        } else {
                                            md.showNotification("top", "right", "No fue posible dar de alta a este empleado.");
                                        }
                                    }
                                });
                            });
                        } else {
                            md.showNotification("top", "right", "Completa todos los campos.");
                        }
                    });
                }
            });
        } else {
            mensaje_error(data);
        }
    });
};

function permiso(id) {
    $.post("assets/php/verificar_baja.php", {
        "id": id
    }, function (datos) {
        let data = JSON.parse(datos);
        if (data.success) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevoPermiso.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    $(".ver_contenedor").html(html);
                    select_estilo_2();

                    $("#categoria").change(function () {
                        if ($(this).val() == 1) {
                            if ($("#materno").is(":checked")) {
                                $("#materno").click();
                            }
                            $("#materno").prop("disabled", true);
                        } else
                            $("#materno").prop("disabled", false);

                    });

                    $.post("assets/php/fecha_periodo.php", function (datos) {
                        var data = JSON.parse(datos);
                        $('#fecha2').datepicker({
                            minDate: new Date(data.del),
                            maxDate: new Date(data.al),
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            // todayButton: new Date(),
                            toggleSelected: false,
                            onSelect(formattedDate, date, inst) {
                                diferencia_fecha($("#fecha2").val(), $("#fecha3").val());
                            }
                        });
                        $('#fecha3').datepicker({
                            minDate: new Date(data.del),
                            maxDate: new Date(data.al),
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            // todayButton: new Date(),
                            toggleSelected: false,
                            onSelect(formattedDate, date, inst) {
                                diferencia_fecha($("#fecha2").val(), $("#fecha3").val());
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
                                if ($("#materno").is(':checked') && $("#categoria").val() == 0)
                                    materno = 1;

                                $.ajax({
                                    type: "POST",
                                    url: "assets/php/agregarPermiso.php",
                                    data: {
                                        // "fecha1": $("#fecha1").val(),
                                        "fecha2": $("#fecha2").val(),
                                        "fecha3": $("#fecha3").val(),
                                        "id": id,
                                        "categoria": $("#categoria").val(),
                                        "dias": $("#dias").val(),
                                        "descripcion": $("#descripcion").val(),
                                        "materno": materno
                                    },
                                    success: function (html) {
                                        $(".continuar").prop("disabled", true);
                                        if (html == 0) {
                                            md.showNotification("top", "right", "Licencia agregada correctamente.");
                                            verPermisos(id);
                                        } else {
                                            md.showNotification("top", "right", "Licencia no agregadas.");
                                        }

                                    }
                                });
                            }
                        });
                    });

                }
            });
        } else {
            mensaje_error(data);
        }
    });

};


function ver(id, ventana, event) {
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
                    padding: 0,
                    width: "60em",
                    showCloseButton: true,
                    showConfirmButton: false,
                });
                rfc_global = id;

                verPerfil(id);
            }
        });
    } else if (ventana == 1) {
        opciones(id);
    }
};



function verBeneficiarios(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/verBeneficiarios.php",
        data: {
            "id": id
        },
        success: function (html) {
            $(".ver_contenedor").html(html);
        }
    });
}

function detalle(id) {
    $.post("assets/php/detallePermiso.php", {
        "id": id
    }, function (datos) {
        var data = JSON.parse(datos);
        $(".ver_contenedor").html(data.html);

        $('#fecha').datepicker({
            language: 'es',
            minDate: new Date(data.del),
            maxDate: new Date(data.al),
            startDate: new Date(data.del),
            onRenderCell: function (date, cellType) {
                if (cellType == 'day' && comprobarFecha(data.del, data.al, date)) {
                    return {
                        html: '<div class="celda-fecha"><p>' + date.getDate() + '</p></div>'
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
            $(".ver_contenedor").html(html);

            select_estilo_3();

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
        md.showNotification("top", "right", "No se encontró ningún archivo.");
    } else
        window.open(url, '_blank');
};

function archivo(id, url, usuario, tabla, condicion) {
    if (url == "") {
        $.ajax({
            type: "POST",
            url: "assets/php/form_archivo.php",
            success: function (html) {
                $("#modal .modal_titulo").html("Archivo");
                $("#modal .modal-body").html(html);
                mostrar_modal();
                $("#modal .modal-footer").hide();

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
                                $.unblockUI();
                                md.showNotification("top", "right", "Archivo cargado correctamente.");
                                ocultar_modal();
                                ventanaRegresar(tabla, condicion, id, usuario);
                            }
                        });
                    }
                });
            }
        });
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
    $("#modal .modal_titulo").html("Eliminar pase");
    $("#modal .modal-body").html('¿Seguro que quieres eliminar este registro?');
    mostrar_modal();
    $("#modal_aceptar").off().click(function () {
        $.ajax({
            type: "POST",
            url: "assets/php/eliminarPermiso.php",
            data: {
                "id": id
            },
            success: function (data) {
                verPermisos(data);
                ocultar_modal();
            }
        });
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
            $(".ver_contenedor").html(html);
            select_estilo_3();

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
            $(".ver_contenedor").html(html);
            select_estilo_3();

            tablas_vacaciones(id);
            $(".sources").change(function () {
                tablas_vacaciones(id);
            });
        }
    });
};

function verPerfil(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/profile.php",
        data: {
            "id": id
        },
        success: function (html) {
            $(".ver_contenedor").html(html);
        }
    });
};

function verHistorial(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/historial.php",
        data: {
            "id": id
        },
        success: function (html) {
            $(".ver_contenedor").html(html);
            select_estilo_3();
            tablas_historial(id);
        }
    });
};

function tablas_historial(id) {
    var ano = $("#ano :selected").val();
    $.ajax({
        type: "POST",
        url: "assets/php/mostrarHistorial.php",
        data: {
            "ano": ano,
            "id": id
        },
        success: function (html) {
            $(".caja-historial").html(html);
        }
    });
};

function vacacion(id) {
    $.post("assets/php/verificar_baja.php", {
        "id": id
    }, function (datos) {
        let data = JSON.parse(datos);
        if (data.success) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevaVacacion.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    $(".ver_contenedor").html(html);
                    $.post("assets/php/fecha_periodo.php", function (datos) {
                        var data = JSON.parse(datos);
                        $('#fecha1').datepicker({
                            minDate: new Date(data.del),
                            maxDate: new Date(data.al),
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            // todayButton: new Date(),
                            toggleSelected: false,
                            onSelect(formattedDate, date, inst) {
                                diferencia_fecha($("#fecha1").val(), $("#fecha2").val());

                            }
                        });
                        $('#fecha2').datepicker({
                            minDate: new Date(data.del),
                            maxDate: new Date(data.al),
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            // todayButton: new Date(),
                            toggleSelected: false,
                            onSelect(formattedDate, date, inst) {
                                diferencia_fecha($("#fecha1").val(), $("#fecha2").val());

                            }
                        });
                    });

                    $("#form-vacacion").submit(function (e) {
                        e.preventDefault();
                        $.post('assets/php/comprobarFechas.php', {
                            dias: $("#dias").val(),
                        }).done(function (dato) {
                            if (dato != 1) {
                                $("#advertencia").removeClass("hide");
                                $("#advertencia").addClass("advertencia");
                            } else {
                                $.ajax({
                                    type: "POST",
                                    url: "assets/php/agregarVacacion.php",
                                    data: {
                                        "del": $("#fecha1").val(),
                                        "al": $("#fecha2").val(),
                                        "id": id,
                                        "dias": $("#dias").val(),
                                        "descripcion": $("#descripcion").val()

                                    },
                                    success: function (html) {
                                        if (html == 0) {
                                            md.showNotification("top", "right", "Vacaciones agregadas correctamente.");
                                            verVacaciones(id);
                                        } else {
                                            md.showNotification("top", "right", "Vacaciones no agregadas.");
                                        }
                                    }
                                });
                            }
                        });

                    });
                }
            });
        } else {
            mensaje_error(data);
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
        $(".ver_contenedor").html(data.html);

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
    $("#modal .modal_titulo").html("Eliminar pase");
    $("#modal .modal-body").html('¿Seguro que quieres eliminar este registro?');
    mostrar_modal();
    $("#modal_aceptar").off().click(function () {
        $.ajax({
            type: "POST",
            url: "assets/php/eliminarVacacion.php",
            data: {
                "id": id
            },
            success: function (data) {
                verVacaciones(data);
                ocultar_modal();
            }
        });
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
            $(".ver_contenedor").html(html);
            select_estilo_3();
            tablas_movimientos(id);
            $(".sources").change(function () {
                tablas_movimientos(id);
            });
        }
    });
};

function movimiento(id) {
    $.post("assets/php/verificar_baja.php", {
        "id": id
    }, function (datos) {
        let data = JSON.parse(datos);
        if (data.success) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevoMovimiento.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    $(".ver_contenedor").html(html);

                    select_estilo();
                    depa_change();
                    puesto_change();

                    $.post("assets/php/fecha_movimiento.php", {
                        "id": id
                    }, function (datos) {
                        let data = JSON.parse(datos);

                        $('#fecha').datepicker({
                            minDate: new Date(data.del),
                            maxDate: new Date(data.al),
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            // todayButton: new Date(),
                            toggleSelected: false,
                            onSelect(formattedDate, date, inst) {
                                $("#puesto").change();
                            }
                        });
                    });


                    $("#form-movimiento").submit(function (e) {
                        e.preventDefault();
                        let puesto = $("#puesto").val();
                        let departamento = $("#departamento").val();
                        let observacion = $("#observacion").val();
                        let plaza = $("#plaza").val();
                        let fecha = $("#fecha").val();
                        if (plaza == null) {
                            md.showNotification("top", "right", "Completa todos los campos.");
                        } else {
                            $("#modal .modal_titulo").html("Confirmar movimiento");
                            $("#modal .modal-body").html($("#puesto :selected").text() +
                                "<p>Nuevo puesto</p>" +
                                $("#departamento :selected").text() +
                                "<p>Nuevo departamento</p>");

                            mostrar_modal();

                            $("#modal_aceptar").off().click(function () {
                                $.ajax({
                                    type: "POST",
                                    url: "assets/php/agregarMovimiento.php",
                                    data: {
                                        "fecha": fecha,
                                        "id": id,
                                        "puesto": puesto,
                                        "departamento": departamento,
                                        "observacion": observacion,
                                        "plaza": plaza
                                    },
                                    success: function (data) {
                                        ocultar_modal();
                                        if (data != 0) {
                                            formato_movimiento(data);
                                            md.showNotification("top", "right", "Datos agregados.");
                                            $('#tabla-empleado').DataTable().ajax.reload();
                                            verMovimientos(id);
                                        } else {
                                            md.showNotification("top", "right", "Ocurrió un error.");
                                        }
                                    }
                                });
                            });
                        }
                    });
                }
            });
        } else {
            mensaje_error(data);
        }
    });

};


function detalle_movimiento(id) {
    $.post("assets/php/detalleMovimiento.php", {
        "id": id
    }, function (html) {
        $(".ver_contenedor").html(html);
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
            $(".ver_contenedor").html(html);
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
                            $(".ver_contenedor").html(html);
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

    $(".constancia").click(function () {
        nombre = 'constancia';
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
    $("#modal .modal_titulo").html("Eliminar archivo");
    $("#modal .modal-body").html('¿Seguro que quieres eliminar este archivo?');
    mostrar_modal();
    $("#modal_aceptar").off().click(function () {
        $.ajax({
            type: "POST",
            url: "assets/php/eliminarExpediente.php",
            data: {
                "id": id,
                "nombre": nombre
            },
            success: function (data) {
                md.showNotification("top", "right", "Archivo eliminado correctamente.");
                ocultar_modal();
                verExpediente(id);
            }
        });
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
            $(".ver_contenedor").html(html);
            select_estilo_3();
            tablas_gastos(id);
            $(".sources").change(function () {
                tablas_gastos(id);
            });
        }
    });
};


function gastos(id) {
    $.post("assets/php/verificar_baja.php", {
        "id": id
    }, function (datos) {
        let data = JSON.parse(datos);
        if (data.success) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevoGastos.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    $(".ver_contenedor").html(html);

                    $.post("assets/php/fecha_periodo.php", function (datos) {
                        var data = JSON.parse(datos);
                        $('#fecha').datepicker({
                            minDate: new Date(data.del),
                            maxDate: new Date(data.al),
                            language: 'es',
                            autoClose: 'true',
                            position: "bottom center",
                            // todayButton: new Date(),
                            toggleSelected: false,
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
                                        md.showNotification("top", "right", "Gastos médicos agregados correctamente.");
                                        verGastos(id);
                                    } else {
                                        md.showNotification("top", "right", "Gastos médicos no agregados.");
                                    }
                                }
                            });
                        }
                    });
                }
            });
        } else {
            mensaje_error(data);
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
    $("#modal .modal_titulo").html("Eliminar pase");
    $("#modal .modal-body").html('¿Seguro que quieres eliminar este registro?');
    mostrar_modal();
    $("#modal_aceptar").off().click(function () {
        $.ajax({
            type: "POST",
            url: "assets/php/eliminarGastos.php",
            data: {
                "id": id
            },
            success: function (data) {
                verGastos(data);
                ocultar_modal();
            }
        });
    })
};

function detalle_gastos(id) {
    $.post("assets/php/detalleGastos.php", {
        "id": id
    }, function (datos) {
        var data = JSON.parse(datos);
        $(".ver_contenedor").html(data.html);
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
            $(".ver_contenedor").html(html);
            select_estilo_3();
            tablas_descuentos(id);
            $(".sources").change(function () {
                tablas_descuentos(id);
            });
        }
    });
};

function descuento(id) {
    $.post("assets/php/verificar_baja.php", {
        "id": id
    }, function (datos) {
        let data = JSON.parse(datos);
        if (data.success) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevoDescuento.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    $(".ver_contenedor").html(html);

                    $.post("assets/php/fecha_periodo.php", function (datos) {
                        var data = JSON.parse(datos);
                        $('#fecha').datepicker({
                            minDate: new Date(data.del),
                            maxDate: new Date(data.al),
                            language: 'es',
                            multipleDates: true,
                            multipleDatesSeparator: ",",
                            onSelect(formattedDate, date, inst) {
                                if (date == '')
                                    $('#dias').val("0");
                                else {
                                    $('#fechas').val(formattedDate);
                                    var fechas = $('#fechas').val().split(",");
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
                                    "fechas": $("#fechas").val(),
                                    "id": id,
                                    "dias": $("#dias").val(),
                                    "motivo": $("#motivo").val()
                                },
                                success: function (html) {
                                    var idUsuario = id.split("-");
                                    if (html == 0) {
                                        md.showNotification("top", "right", "Descuento agregado correctamente.");
                                        verDescuentos(id);
                                    } else {
                                        md.showNotification("top", "right", "Descuento no agregado.");
                                    }
                                }
                            });
                        }
                    });
                }
            });
        } else {
            mensaje_error(data);
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
    $("#modal .modal_titulo").html("Eliminar pase");
    $("#modal .modal-body").html('¿Seguro que quieres eliminar este registro?');
    mostrar_modal();
    $("#modal_aceptar").off().click(function () {
        $.ajax({
            type: "POST",
            url: "assets/php/eliminarDescuento.php",
            data: {
                "id": id
            },
            success: function (data) {
                verDescuentos(data);
                ocultar_modal();
            }
        });
    })

};

function detalle_descuento(id) {
    $.post("assets/php/detalleDescuento.php", {
        "id": id
    }, function (datos) {
        var data = JSON.parse(datos);
        $(".ver_contenedor").html(data.html);

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
    $.post("assets/php/verificar_baja.php", {
        "id": id
    }, function (datos) {
        let data = JSON.parse(datos);
        if (data.success) {
            mensaje_cargar();
            $.post("assets/php/editarEmpleado.php", {
                id: id
            }, function (html) {
                Swal.fire({
                    position: 'center',
                    html: html,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    width: '60em'
                });

                select_estilo();

                $('#fecha').datepicker({
                    language: 'es',
                    autoClose: 'true',
                    position: "top center",
                    // todayButton: new Date(),
                    toggleSelected: false,
                });

                $("#form-empleado-1").submit(function (e) {
                    e.preventDefault();
                    $(".pagina_1").addClass("adp-hide");
                    ADP.show($(".pagina_2")[0], 'fade');
                    $(".pagina_2").removeClass("adp-hide");
                });

                $(".pagina_2_boton").click(function () {
                    $(".pagina_2").addClass("adp-hide");
                    ADP.show($(".pagina_1")[0], 'fade');
                    $(".pagina_1").removeClass("adp-hide");
                });

                $("#form-empleado-2").submit(function (e) {
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
                            "domicilio": $("#domicilio").val(),
                            "email": $("#email").val(),
                            "ingreso": $("#fecha").val(),
                            "periodo": $("#periodo").val()
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

                });

            });
        } else {
            mensaje_error(data);
        }
    });
};

function generar_empleados() {
    mensaje_cargar();
    $.ajax({
        url: "assets/php/generarExcel.php",
        data: {
            estado: $("#estado").val()
        },
        type: "POST",
        success: function (data) {
            Swal.close();
            if (data !== 0) {
                descargar(data, 'Empleados');
            }
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
            $(".ver_contenedor").html(html);
            select_estilo_3();
            tablas_pases(id);
            $(".sources").change(function () {
                tablas_pases(id);
            });
        }
    });
};

function pase(id) {
    $.post("assets/php/verificar_baja.php", {
        "id": id
    }, function (datos) {
        let data = JSON.parse(datos);
        if (data.success) {
            $.ajax({
                type: "POST",
                url: "assets/php/nuevoPase.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    $(".ver_contenedor").html(html);

                    $.post("assets/php/fecha_periodo.php", function (datos) {
                        var data = JSON.parse(datos);
                        $('#fecha').datepicker({
                            minDate: new Date(data.del),
                            maxDate: new Date(data.al),
                            language: 'es',
                            timepicker: true,
                            onSelect(formattedDate, date, inst) {
                                $("#pase").val(formattedDate);
                            }
                        });
                    });

                    select_estilo_2();

                    $("#form-pase").submit(function (e) {
                        e.preventDefault();
                        if ($("#pase").val() === "") {
                            $("#advertencia").removeClass("hide");
                            $("#advertencia").addClass("advertencia");
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "assets/php/agregarPase.php",
                                data: {
                                    "fecha": $("#pase").val(),
                                    "id": id,
                                    "categoria": $("#categoria").val(),
                                    "observacion": $("#observacion").val(),
                                },
                                success: function (html) {
                                    if (html == 1) {
                                        md.showNotification("top", "right", "Pase agregado correctamente.");
                                        verPases(id);
                                    } else if (html == 2) {
                                        md.showNotification("top", "right", "Deber especificar una fecha y hora.");
                                    } else {
                                        md.showNotification("top", "right", "Pase no agregado.");
                                    }
                                }
                            });
                        }

                    });
                }
            });
        } else {
            mensaje_error(data);
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
    $("#modal .modal_titulo").html("Eliminar pase");
    $("#modal .modal-body").html('¿Seguro que quieres eliminar este registro?');
    mostrar_modal();
    $("#modal_aceptar").off().click(function () {
        $.ajax({
            type: "POST",
            url: "assets/php/eliminarPase.php",
            data: {
                "id": id
            },
            success: function (data) {
                verPases(data);
                ocultar_modal();
            }
        });
    })
};

function detalle_pase(id) {
    $.post("assets/php/detallePase.php", {
        "id": id
    }, function (datos) {
        var data = JSON.parse(datos);
        $(".ver_contenedor").html(data.html);
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

function formato_movimiento(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/formato_movimiento.php",
        data: {
            "id": id
        },
        success: function (url) {
            descargar(url, "Movimiento");
        }
    });
}

function formato_historial(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/formato_historial.php",
        data: {
            "id": id
        },
        success: function (url) {
            descargar(url, "Historial");
        }
    });
}

function eliminar_archivo(id, tabla) {

    $("#modal .modal_titulo").html("Eliminar archivo");
    $("#modal .modal-body").html('¿Seguro que quieres eliminar este archivo?');
    mostrar_modal();
    $("#modal_aceptar").off().click(function () {
        $.ajax({
            type: "POST",
            url: "assets/php/eliminarArchivo.php",
            data: {
                "id": id,
                "tabla": tabla
            },
            success: function (data) {
                md.showNotification("top", "right", "Archivo eliminado correctamente.");
                ventanaRegresar(tabla, 1, id, null);
                ocultar_modal();
            }
        });
    })



}

function diferencia_fecha(fecha1, fecha2) {
    var a = moment(fecha1, 'DD/MM/YYYY').subtract(1, 'days');
    var b = moment(fecha2, 'DD/MM/YYYY');
    var diffDays = b.diff(a, 'days');
    if (diffDays <= 0 || isNaN(diffDays))
        diffDays = 0;

    $("#dias").val(diffDays);
}

// function depa_change() {
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