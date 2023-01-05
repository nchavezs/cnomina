$(document).ready(function () {
    $(".pestana_1").click(function () {
        $(".pagina_2").addClass("adp-hide");
        ADP.show($(".pagina_1")[0], 'slide-left');
        $(".pagina_1").removeClass("adp-hide");
    });

    $(".pestana_2").click(function () {
        $(".pagina_1").addClass("adp-hide");
        ADP.show($(".pagina_2")[0], 'slide-right');
        $(".pagina_2").removeClass("adp-hide");
    });

    $('#tabla-puesto').DataTable({
        
        "order": [
            [0, "desc"]
        ],
       
        "ajax": {
            "type": "POST",
            "url": "assets/php/consulta-puesto.php"
        },
        "drawCallback": function (settings) {
            $('.main-panel .content').perfectScrollbar('update');
            ADP.show($(".pagina_1 .table-responsive")[0], 'slide-left');
        },
        "columnDefs": [
            {
                "orderable": false,
                "targets": [3]
            }
        ],
        "columns": [{
            "render": function (data, type, row) {
                return '<span class="negrita">' + row.numero + '</span>';
            }
        },
        {
            "render": function (data, type, row) {
                return "<span class='negrita'>" + row.puesto + "</span>";
            }
        },
        {
            "render": function (data, type, row) {
                return "<span>" + row.departamento + "</span>";
            }
        },
        {
            "render": function (data, type, row) {
                return '<span class="tipo">' + row.plazas + '</span>';
            }
        },
        {
            "data": "categoria"
        },
        {
            "render": function (data, type, row) {
                return row.opciones
            }
        }
        ]
    });

    $('#tabla-departamento').DataTable({
        
        "order": [
            [0, "desc"]
        ],
        
        "ajax": {
            "type": "POST",
            "url": "assets/php/consulta-departamento.php"
        },
        // "drawCallback": function( settings ) {
        //     $('.main-panel .content').perfectScrollbar('update');
        // },
        "columnDefs": [{
            "className": "text-center",
            "orderable": false,
            "targets": [2],
        }
        ],
        "columns": [{
            "render": function (data, type, row) {
                return '<span class="negrita">' + row.numero + '</span>';
            }
        },
        {
            "render": function (data, type, row) {
                return '<span class="negrita">' + row.nombre + '</span>';
            }
        },
        {
            "render": function (data, type, row) {
                return row.opciones;
            }
        }
        ]
    });

    $("#importar-puestos").change(function () {
        if ($(this).val() != "") {
            mensaje_cargar();

            var formData = new FormData();
            var files = $("#importar-puestos")[0].files[0];
            formData.append("file", files);

            $.ajax({
                url: "assets/php/importar_puesto.php",
                type: "post",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function (data) {
                    log_show(data);
                    $('#tabla-puesto').DataTable().ajax.reload();
                    $('#tabla-departamento').DataTable().ajax.reload();
                    $("#importar-puestos").val("");
                }
            });
        }
    });


    $("#importar-departamentos").change(function () {
        if ($(this).val() != "") {
            mensaje_cargar();

            var formData = new FormData();
            var files = $("#importar-departamentos")[0].files[0];
            formData.append("file", files);

            $.ajax({
                url: "assets/php/importar_depa.php",
                type: "post",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function (data) {
                    log_show(data);
                    $('#tabla-departamento').DataTable().ajax.reload();
                    $("#importar-departamentos").val("");
                }
            });
        }
    });

});

function exportar_puesto() {
    $.post("assets/php/exportar_puesto.php", function (data) {
        if (data != 0) {
            descargar(data, "Puestos.xlsx");
        } else {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo generar el archivo',
                type: 'error'
            });
        }
    });
};

function exportar_puesto_agrupado() {
    $.post("assets/php/exportar_puesto_agrupado.php", function (data) {
        if (data != 0) {
            descargar(data, "Puestos.xlsx");
        } else {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo generar el archivo',
                type: 'error'
            });
        }
    });
};

function exportar_depa() {
    $.post("assets/php/exportar_depa.php", function (data) {
        if (data != 0) {
            descargar(data, "Departamentos.xlsx");
        } else {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo generar el archivo',
                type: 'error'
            });
        }
    });
};

function nuevo_departamento() {
    $.ajax({
        type: "POST",
        url: "assets/php/nuevoDepartamento.php",
        success: function (html) {
            Swal.fire({
                html: html,
                showCancelButton: false,
                showConfirmButton: false,
                width: "30em"
            });

            select_estilo();

            $("#form").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "assets/php/agregar_departamento.php",
                    data: {
                        nombre: $("#nombre").val(),
                    },
                    success: function (data) {
                        if (data == 1) {
                            md.showNotification("top", "right", "Departamento agregado.");
                            $('#tabla-departamento').DataTable().ajax.reload();
                            Swal.close();
                        } else if (data == 2) {
                            md.showNotification("top", "right", "Ya existe un departamento con este nombre.");
                        } else {
                            md.showNotification("top", "right", "Ocurrio un error.");
                        }
                    }
                });
            })
        }
    });
};

function nuevo_puesto() {
    $.ajax({
        type: "POST",
        url: "assets/php/nuevoPuesto.php",
        success: function (html) {
            Swal.fire({
                html: html,
                showCancelButton: false,
                showConfirmButton: false,
                width: "30em"
            });

            select_estilo();

            $("#form").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "assets/php/agregar_puesto.php",
                    data: {
                        nombre: $("#nombre").val(),
                        departamento: $("#departamento").val(),
                        trabajador: $("#trabajador").val()
                    },
                    success: function (data) {
                        if (data == 1) {
                            md.showNotification("top", "right", "Puesto agregado.");
                            $('#tabla-puesto').DataTable().ajax.reload();
                            $('#tabla-departamento').DataTable().ajax.reload();
                            Swal.close();
                        } else if (data == 2) {
                            md.showNotification("top", "right", "Ya existe un puesto con este nombre.");
                        } else {
                            md.showNotification("top", "right", "Ocurrio un error.");
                        }
                    }
                });
            })
        }
    });
};

function eliminar(id, categoria) {
    Swal.fire({
        title: "Eliminar",
        text: "¿Seguro que quieres eliminar este elemento?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",


    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarPuestoDepa.php",
                data: {
                    "id": id,
                    "categoria": categoria
                },
                success: function (html) {
                    if (html == 1) {
                        Swal.fire({
                            title: 'Correcto',
                            text: 'Eliminado correctamente',
                            type: 'success'
                        })
                    } else if (html == 2) {
                        bloqueo();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Elemento no eliminado',
                            type: 'error'
                        })
                    }
                    $('#tabla-puesto').DataTable().ajax.reload();
                    $('#tabla-departamento').DataTable().ajax.reload();
                }
            });
        }
    })
};

function editar_departamento(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/editar_departamento.php",
        data: {
            "id": id,
        },
        success: function (html) {
            Swal.fire({
                html: html,
                showCancelButton: false,
                showConfirmButton: false,
                width: "30em"
            });

            $("#form").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "assets/php/actualizar_departamento.php",
                    data: {
                        id: id,
                        nombre: $("#nombre").val()
                    },
                    success: function (data) {
                        if (data == 1) {
                            md.showNotification("top", "right", "Departamento actualizado.");
                            $('#tabla-puesto').DataTable().ajax.reload();
                            $('#tabla-departamento').DataTable().ajax.reload();
                            Swal.close();
                        } else if (data == 2) {
                            md.showNotification("top", "right", "Ya existe un departamento con este nombre.");
                        } else {
                            md.showNotification("top", "right", "Ocurrio un error.");

                        }
                    }
                });
            })
        }
    });
};

function editar_puesto(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/editar_puesto.php",
        data: {
            "id": id,
        },
        success: function (html) {
            Swal.fire({
                html: html,
                showCancelButton: false,
                showConfirmButton: false,
                width: "30em"
            });

            select_estilo();

            $("#form").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "assets/php/actualizar_puesto.php",
                    data: {
                        id: id,
                        nombre: $("#nombre").val(),
                        departamento: $("#departamento").val(),
                        trabajador: $("#trabajador").val()
                    },
                    success: function (data) {
                        if (data == 1) {
                            md.showNotification("top", "right", "Puesto actualizado.");
                            $('#tabla-puesto').DataTable().ajax.reload();
                            $('#tabla-departamento').DataTable().ajax.reload();
                            Swal.close();
                        } else if (data == 2) {
                            md.showNotification("top", "right", "Ya existe un puesto con este nombre.");
                        } else {
                            md.showNotification("top", "right", "Ocurrio un error.");
                        }
                    }
                });
            })
        }
    });
};