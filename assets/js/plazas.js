$(document).ready(function () {
    $('#tabla-plaza').DataTable.ext.pager.numbers_length = 5;
    var tabla = $('#tabla-plaza').DataTable({
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
            "url": "assets/php/consulta-plaza.php"
        },
        "drawCallback": function (settings) {
            document.querySelector('.content').scrollTop = 1;
        },
        "columnDefs": [{
                "className": "font-weight-bold",
                "targets": [0, 1]
            },
            {
                "orderable": false,
                "targets": [3]
            }
        ],
        "columns": [{
                "data": "id_plaza"
            },
            {
                "render": function (data, type, row) {
                    let html = "<div>" + row.puesto + "</div>" + "<small>" + row.departamento + "</small>";
                    return html;
                }
            },
            {
                "data": "usuario"
            },
            {
                "render": function (data, type, row) {
                    return '<a class="baja">' + row.ocupados + '</a>';
                }
            },
            {
                "render": function (data, type, row) {
                    return '<a class="tipo">' + row.vacantes + '</a>';
                }
            },
            {
                "render": function (data, type, row) {
                    return '<i class="material-icons btn1-danger" onClick="eliminar(' + row.id_plaza + ', event);">delete</i>';
                }
            }
        ]
    });

    $(document).on("click", "#tabla-plaza tr", function (e) {
        let data = tabla.row(this).data();
        detalle_plaza(data[0]);
    });

    $("#importar-plazas").change(function () {
        if ($(this).val() != "") {
            mensaje_cargar();

            var formData = new FormData();
            var files = $("#importar-plazas")[0].files[0];
            formData.append("file", files);

            $.ajax({
                url: "assets/php/importar_plazas.php",
                type: "post",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function (data) {
                    log_show(data);
                    $('#tabla-plaza').DataTable().ajax.reload();
                    $("#importar-plazas").val("");
                }
            });
        }
    });
});

// function exportar_puesto() {
//     $.post("assets/php/exportar_puesto.php", function (data) {
//         if (data != 0) {
//             descargar(data, "Puestos.xlsx");
//         } else {
//             Swal.fire({
//                 title: 'Error',
//                 text: 'No se pudo generar el archivo',
//                 type: 'error'
//             });
//         }
//     });
// };


function nueva_plaza() {
    $.post("assets/php/nuevaPlaza.php").done(function (html) {
        Swal.fire({
            html: html,
            allowOutsideClick: false,
            showConfirmButton: false
        });
        select_estilo();
        document.getElementById("dias").max = "" + dias_ano();
        $("#dias").val(dias_ano());
        $("#fecha").css("color", "green");
        
        select_change();

        $("#form-plaza").on("submit", function (e) {
            e.preventDefault();
            guardar_plaza();
        });

        $("#dias").on("keyup", function (event) {
            if (this.value > dias_ano()) {
                this.value = dias_ano();
            } else if (this.value < 1) {
                $("#fecha").css("color", "red");
            }else{
                $("#fecha").css("color", "green");
            }
            $("#fecha").val(fecha_presupuesto(this.value));
        });
    });
};

function guardar_plaza() {
    $.ajax({
        url: "assets/php/agregarPlaza.php",
        type: "POST",
        data: {
            puesto: $("#puesto").val(),
            dias: $("#dias").val(),
            cantidad: $("#cantidad").val()
        },
        success: function (data) {
            if (data == 1) {
                Swal.fire({
                    title: 'Correcto',
                    text: 'Registro agregado',
                    type: 'success'
                })
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Registro no agregado',
                    type: 'error'
                })
            }
            $('#tabla-plaza').DataTable().ajax.reload();
        }
    });
}

function eliminar(id, event) {
    event.stopPropagation();
    Swal.fire({
        title: "Eliminar",
        text: "¿Seguro que quieres eliminar este elemento?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarPlaza.php",
                data: {
                    "id": id
                },
                success: function (html) {
                    if (html == 1) {
                        Swal.fire({
                            title: 'Correcto',
                            text: 'Eliminado correctamente',
                            type: 'success'
                        })
                    } else if (html == 2) {
                        no_pasar();
                    } else if (html == 3) {
                        md.showNotification("top", "right", "No puedes eliminar esta plaza mientras esté ocupada.");

                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Elemento no eliminado',
                            type: 'error',
                        })
                    }
                    $('#tabla-plaza').DataTable().ajax.reload();
                }
            });
        }
    })
};

function fecha_presupuesto(dias) {
    var fecha = moment("01-01" + (moment().year() + 1), 'D/M/YYYY').subtract(dias, 'days').format("DD/MM/YYYY");
    return fecha;
}


function dias_ano() {
    var d1 = moment("01-01" + moment().year(), "D/M/YYYY");
    var d2 = moment("01-01" + (moment().year() + 1), "D/M/YYYY");

    var dias = moment.duration(d2.diff(d1)).asDays();
    return dias;
}

function detalle_plaza(id) {
    $.ajax({
        type: "POST",
        url: "assets/php/detalle_plaza.php",
        data: {
            "id": id
        },
        success: function (data) {
            if(data != 0){
                Swal.fire({
                    position: 'center',
                    html: data,
                    padding: 0,
                    allowOutsideClick: true,
                    showCloseButton: true,
                    showConfirmButton: false
                });
            }else{
                md.showNotification("top", "right", "Sin historial de vacantes.");
            }
            
        }
    });
}