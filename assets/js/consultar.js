$(document).ready(function () {
    select_estilo_3();

    $(".opciones_tabla select").change(function(){
        $('#tabla-nominas').DataTable().ajax.reload();
    });

    $('#tabla-nominas').DataTable({
        "lengthChange": false,
        "pageLength": 10,
        "language": {
            url: "assets/js/datatables/es.json"
        },
        "ajax": {
            "type": "POST",
            "url": "assets/php/consultar.php",
            "data": function(d){
                d.ano = $("#ano").val();
                d.id_periodo = $("#id_periodo").val();
            }
        },
        "drawCallback": function (settings) {
            $('.main-panel .content').perfectScrollbar('update');
            ADP.show($(".table-responsive")[0], 'slide-left');
        },
        "columnDefs": [ {
            "targets": [3,4],
            "orderable": false 
        },{
            "targets": [2],
            "className": "oculto"
        }],
        "columns": [{
                "data": "nombre",
                
            },{
                "render": function (data, type, row) {
                    return '<a class="">' + row.del + ' ➟ ' + row.al + '</a>';
                }
            },
            {
                "render": function (data, type, row) {
                    return '<span class="alta">'+row.dias_pago+'</span>';
                }
            },
            {
                "render": function (data, type, row) {
                    return '<i class="material-icons btn1" id="' + row.id_archivo + "x" + '" onClick="ver(this.id);">assignment</i>';
                }
            },
            {
                "render": function (data, type, row) {
                    return '<i class="material-icons btn1-danger" id="' + row.id_archivo + '" onClick="eliminar(this.id);" >delete</i>';
                }
            }

        ]
    });
});

function ver(x) {
    var id = x.replace("x", "");
    $.ajax({
        type: "POST",
        url: "assets/php/verRegistro.php",
        data: {
            "id": id
        },
        success: function (html) {
            Swal.fire({
                position: 'center',
                title: 'Detalle de archivo',
                html: html,
                allowOutsideClick: false,
                confirmButtonText: 'Salir',
                width: "40em"
                
            });
        }
    });
};



function eliminar(id) {
    Swal.fire({
        position: 'center',
        type: 'question',
        title: '¿Eliminar registro?',
        reverseButtons: true,
        showCancelButton: true,
        confirmButtonText: 'SI',
        cancelButtonText: 'NO',
        
        
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarRegistro.php",
                data: {
                    "id": id
                },
                success: function (a) {
                    if (a == 1) {
                        ok();
                    } else if (a == 2) {
                        bloqueo();
                    } else {
                        error();
                    }
                    $('#tabla-nominas').DataTable().ajax.reload();
                }
            });

        } else if (result.dismiss === Swal.DismissReason.cancel) {

        }
    });
};

function ok() {
    Swal.fire({
        position: 'center',
        type: 'success',
        title: 'Eliminado',
        
        
    });
};

function error() {
    Swal.fire({
        position: 'center',
        type: 'error',
        title: 'No eliminado',
        
        
    });
}


var valor1;
var valor2;

function eliminar_periodo() {
    $.ajax({
        type: "POST",
        url: "assets/php/periodo.php",
        success: function (html) {
            Swal.fire({
                html: html,
                showConfirmButton: false,
                allowOutsideClick: false,
                width: "35em"
                
            });
            $('#fecha_del').datepicker({
                language: 'es',
                autoClose: 'true',
                position: "bottom center",
                todayButton: new Date(),
                onSelect(formattedDate, date, inst) {
                    if (date == '')
                        $('#fecha_del').val(valor1);
                    else
                        valor1 = formattedDate;
                }
            });

            $('#fecha_al').datepicker({
                language: 'es',
                autoClose: 'true',
                position: "bottom center",
                todayButton: new Date(),
                onSelect(formattedDate, date, inst) {
                    if (date == '')
                        $('#fecha_al').val(valor2);
                    else
                        valor2 = formattedDate;
                }
            });

            $("#fecha_del").blur();

            $("#form-periodo").submit(function (e) {
                e.preventDefault();
                let fecha_del = $("#fecha_del").val();
                let fecha_al = $("#fecha_al").val();
                mensaje_cargar();
                $.ajax({
                    type: "POST",
                    url: "assets/php/eliminar_periodo.php",
                    data: {
                        del: fecha_del,
                        al: fecha_al,
                    },
                    success: function (data) {
                        cerrar();
                        if (data > 0) {
                            $('#tabla-nominas').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Correcto',
                                text: data + ' recibos de nómina eliminados',
                                type: 'success',

                                
                            });
                        } else {
                            Swal.fire({
                                text: 'No se eliminó ningún recibo de nómina',
                                type: 'warning',

                                
                            });
                        }
                    }
                });
            });
        }
    });
};
