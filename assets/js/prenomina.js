$(document).ready(function () {
    $('#tabla-prenomina').DataTable.ext.pager.numbers_length = 5;
    var tabla = $('#tabla-prenomina').DataTable({
        "lengthChange": false,
        "pageLength": 5,
        "order": [
            [3, "desc"]
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
                "className": "oculto",
                "targets": [2, 3]
            },
            {
                "orderable": false,
                "targets": [4]
            }, {
                "className": "font-weight-bold",
                "targets": [0]
            }
        ],
        "columns": [{
                "render": function (data, type, row) {
                    return '<a class="">' + row.del + ' ➟ ' + row.al + '</a>';
                }
            },
            {
                "render": function (data, type, row) {
                    return '<a class="tipo">' + row.nombre + '</a>';
                }
            },
            {
                "data": "observacion"
            }, {
                "data": "elaboracion"
            }, {
                "render": function (data, type, row) {
                    return '<i class="material-icons btn1" onclick="' + "descargar('" + row.url + "','Prenomina')" + ';">download</i>';
                }
            },
            {
                "render": function (data, type, row) {
                    return '<i class="material-icons btn1-danger" onClick="eliminar(' + row.id_prenomina + ', event);">delete</i>';
                }
            }
        ]
    });
});

function nueva_prenomina() {
    $.post("assets/php/nuevaPrenomina.php").done(function (html) {
        Swal.fire({
            html: html,
            allowOutsideClick: false,
            showConfirmButton: false,
            width: "55em"
        });

        select_estilo();
        ultima_prenomina();

        $('#del').datepicker({
            maxDate: new Date(),
            language: 'es',
            autoClose: 'true',
            position: "bottom center",
            todayButton: new Date(),
            onSelect(formattedDate, date, inst) {
                let dias = 13;
                if ($("#periodo").val() == 2) {
                    dias = 29;
                }
                let al = moment(formattedDate, "DD/MM/YYYY").add(dias, "days").format("DD/MM/YYYY");
                $("#al").val(al);
            }
        });



        $("#periodo").change(function () {
            ultima_prenomina();
        });

        $("#check1").click(function () {
            if ($(this).is(':checked')) {
                ultima_prenomina();
                $("#del").prop("disabled", true);
                $("#al").prop("disabled", true);
            } else {
                $("#del").prop("disabled", false);
                $("#al").prop("disabled", false);
            }
        });

        $('#al').datepicker({
            minDate: new Date(),
            language: 'es',
            autoClose: 'true',
            position: "bottom center",
            todayButton: new Date(),
            onSelect(formattedDate, date, inst) {}
        });

        $("#form-prenomina").on("submit", function (e) {
            e.preventDefault();
            prenomina();
        });
    });
};

function ultima_prenomina() {
    $.ajax({
        url: "assets/php/ultima_prenomina.php",
        method: "POST",
        data: {
            periodo: $("#periodo").val()
        },
        success: function (data) {
            let dias = 14;
            if ($("#periodo").val() == 2) {
                dias = 30;
            }
            let del = moment(data, "DD/MM/YYYY").add(1, "days").format("DD/MM/YYYY");
            let al = moment(data, "DD/MM/YYYY").add(dias, "days").format("DD/MM/YYYY");
            $("#del").val(del);
            $("#al").val(al);
        }
    });
}

function prenomina() {
    $.ajax({
        url: "assets/php/prenomina.php",
        type: "POST",
        data: {
            del: $("#del").val(),
            al: $("#al").val(),
            observacion: $("#observacion").val(),
            periodo: $("#periodo").val()
        },
        success: function (data) {
            if (data != 0) {
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