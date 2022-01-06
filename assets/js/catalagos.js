$(document).ready(function () {
    $('#tabla-puesto').DataTable.ext.pager.numbers_length = 5;
    $('#tabla-puesto').DataTable({
        "lengthChange": false,
        "pageLength": 5,
        "order": [
            [0, "desc"]
        ],
        "language": {
            url: "assets/js/datatables/es.json"
        },
        "ajax": {
            "type": "POST",
            "url": "assets/php/consulta-puesto.php"
        },
        "drawCallback": function( settings ) {
            document.querySelector('.content').scrollTop = 1;
        },
        "columnDefs": [
            {
                "className": "font-weight-bold",
                "targets": [0,1]
            },
            {
                "orderable": false,
                "targets": [3]
            }
        ],
        "columns": [{
                "data": "numero"
            },
            {
                "render": function (data, type, row) {
                    let html = "<div>" + row.puesto + "</div>" + "<small>" + row.departamento + "</small>";
                    return html;
                }
            },
            {
                "render": function (data, type, row) {
                    return '<a class="tipo">' + row.plazas + '</a>';
                }
            },
            {
                "render": function (data, type, row) {
                    return '<i class="material-icons btn1-danger" onClick="eliminar(' + row.id_puesto + ', \'Puesto\');">delete</i>';
                }
            }
        ]
    });

    $('#tabla-departamento').DataTable.ext.pager.numbers_length = 5;
    $('#tabla-departamento').DataTable({
        "lengthChange": false,
        "pageLength": 5,
        "order": [
            [0, "desc"]
        ],
        "language": {
            url: "assets/js/datatables/es.json"
        },
        "ajax": {
            "type": "POST",
            "url": "assets/php/consulta-departamento.php"
        },
        // "drawCallback": function( settings ) {
        //     document.querySelector('.content').scrollTop = 1;
        // },
        "columnDefs": [
            {
                "className": "font-weight-bold",
                "targets": [0]
            },
            {
                "orderable": false,
                "targets": [2]
            }
        ],
        "columns": [{
                "data": "numero"
            },
            {
                "data": "nombre"
            },
            {
                "render": function (data, type, row) {
                    return '<i class="material-icons btn1-danger" onClick="eliminar(' + row.id_departamento + ', \'Departamento\');">delete</i>';
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

function descargar(uri, name) {
    var link = document.createElement("a");
    link.download = name;
    link.href = uri;
    link.click();
}

function nuevo_puesto() {
    $.post("assets/php/opciones_departamento.php").done(function (data) {
        var opciones = jQuery.parseJSON(data);
        Swal.mixin({
            showCancelButton: true,
            progressSteps: ["1", "2", "3"],
        }).queue([{
                title: "Nombre del puesto",
                confirmButtonText: "Siguiente",
                input: "text",
                inputValidator: (value) => {
                    return !value && "Completa los campos"
                }
            },
            {
                title: "Departamento",
                confirmButtonText: "Siguiente",
                input: "select",
                inputOptions: opciones,
                inputValidator: (value) => {
                    return !value && "Completa los campos"
                }
            },
            // {
            //     title: "Plazas",
            //     confirmButtonText: "Guardar",
            //     input: "number",
            //     inputValue: 1,
            //     inputValidator: (value) => {
            //         if (!value)
            //             return "Completa los campos"
            //         else if (value < 1)
            //             return "Valor no valido"
            //     }
            // },
        ]).then((result) => {
            if (result.value) {
                var resultado = JSON.stringify(result.value);
                var datos = jQuery.parseJSON(resultado);
                var nombre = datos[0];
                var departamento = datos[1];
                // var cantidad = datos[2];

                $.post("assets/php/nuevoPuesto.php", {
                        nombre: nombre,
                        departamento: departamento
                        // cantidad: cantidad
                    })
                    .done(function (html) {
                        if (html == 1) {
                            Swal.fire({
                                title: 'Correcto',
                                text: 'Registro agregado',
                                type: 'success'
                            })
                        } else if (html == 2) {
                            Swal.fire({
                                title: 'Advertencia',
                                text: 'El registro ya se encuentra agregado',
                                type: 'warning'
                            })
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'Registro no agregado',
                                type: 'error'
                            })
                        }
                        $('#tabla-puesto').DataTable().ajax.reload();
                    });
            }
        });
    });


};

function nuevo_departamento() {
    Swal.mixin({
        showCancelButton: true,
        progressSteps: ["1"],
    }).queue([{
        title: "Nombre del departamento",
        confirmButtonText: "Guardar",
        input: "text",
        inputValidator: (value) => {
            return !value && "Completa los campos"
        }
    }, ]).then((result) => {
        if (result.value) {
            var resultado = JSON.stringify(result.value);
            var datos = jQuery.parseJSON(resultado);
            var nombre = datos[0];

            $.post("assets/php/nuevoDepa.php", {
                    nombre: nombre
                })
                .done(function (html) {
                    if (html == 1) {
                        Swal.fire({
                            title: 'Correcto',
                            text: 'Registro agregado',
                            type: 'success'
                        })
                    } else if (html == 2) {
                        Swal.fire({
                            title: 'Advertencia',
                            text: 'El registro ya se encuentra agregado',
                            type: 'warning'
                        })
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Registro no agregado',
                            type: 'error'
                        })
                    }
                    $('#tabla-departamento').DataTable().ajax.reload();
                });
        }
    });
};

function log_show(html) {
    Swal.fire({
        html: html,
        allowOutsideClick: false,
        allowEscapeKey: false,
        padding: 0
    });
}



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
                            type: 'success',


                        })
                    } else if (html == 2) {
                        no_pasar();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Elemento no eliminado',
                            type: 'error',


                        })
                    }
                    $('#tabla-puesto').DataTable().ajax.reload();
                    $('#tabla-departamento').DataTable().ajax.reload();
                }
            });
        }
    })
};