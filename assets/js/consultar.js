$(document).ready(function () {
    $('#consulta_tabla').DataTable.ext.pager.numbers_length = 5;
    $('#consulta_tabla').DataTable({
        "lengthChange": false,
        "pageLength": 8,
        "language": {
            url: "assets/js/datatables/es.json"
        },
        "ajax": {
            "type": "POST",
            "url": "assets/php/consultar.php"
        },
        "columnDefs": [ {
            "targets": [5,6],
            "orderable": false 
        },{
            "targets": [1,2,3,4],
            "className": "oculto"
        }],
        "columns": [{
                "data": "nombreEmpleado",
                
            },
            {
                "data": "fecha_pago"
            },
            {
                "data": "RFC"
            },
            {
                "data": "puesto",
                
            },
            {
                "data": "departamento",
               
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
                customClass: 'swal0-width animated fadeIn faster',
                
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
                        no_pasar();
                    } else {
                        error();
                    }
                    $('#consulta_tabla').DataTable().ajax.reload();
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
                customClass: 'swal0-width animated fadeIn faster',
                
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
                            $('#consulta_tabla').DataTable().ajax.reload();
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


function mensaje_cargar() {
    let timerInterval
    Swal.fire({
        title: 'Cargando',
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