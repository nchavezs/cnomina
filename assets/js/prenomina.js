$(document).ready(function () {
    $('#tabla-prenomina').DataTable.ext.pager.numbers_length = 5;
    var tabla = $('#tabla-prenomina').DataTable({
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
            "url": "assets/php/consulta-prenomina.php"
        },
        "drawCallback": function (settings) {
            document.querySelector('.content').scrollTop = 1;
        },
        "columnDefs": [{
                "className": "font-weight-bold",
                "targets": [0]
            },
            {
                "orderable": false,
                "targets": [3]
            }
        ],
        "columns": [{
                "data": "elaboracion"
            },
            {
                "render": function (data, type, row) {
                    return '<a class="tipo">' + row.del + ' - ' + row.al + '</a>';
                }
            }, {
                "data": "observaciones"
            }, {
                "render": function (data, type, row) {
                    return '<a href="' + row.url + '" download><i class="material-icons btn1">download</i></a>';
                }
            },
        ]
    });
});

var valor1 = "";
var valor2 = "";
var check1 = 0;

$("#check1").change(function () {
    if ($(this).is(':checked')) {
        check1 = 1;
    } else {
        check1 = 0;
    }
});

function nueva_prenomina() {
    $.post("assets/php/nuevaPrenomina.php").done(function (html) {
        Swal.fire({
            html: html,
            allowOutsideClick: false,
            showConfirmButton: false,
            width: "50em"
        });

        select_estilo();

        $('#del').datepicker({
            maxDate: new Date(),
            language: 'es',
            autoClose: 'true',
            position: "top center",
            todayButton: new Date(),
            onSelect(formattedDate, date, inst) {
                if (date == '')
                    $('#del').val(valor1);
                else
                    valor1 = formattedDate;
            }
        });

        $("#form-prenomina").on("submit", function (e) {
            e.preventDefault();
            prenomina();
        });
    });
};

function prenomina() {
    $.ajax({
        url: "assets/php/prenomina.php",
        type: "POST",
        data: {
            del: $("#del").val(),
            al: $("#al").val(),
            observacion: $("#observacion").val()
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
            $('#tabla-prenomina').DataTable().ajax.reload();
        }
    });
}