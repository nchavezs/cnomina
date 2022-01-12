var empleados = [];
var fecha1;
var fecha2;
var fecha3;
var fecha4;
var fecha5;
var fecha6;
var fecha7;
var fecha8;
var fecha9;
var fecha10;
var fecha11;
var fecha12;
var fecha13;
var fecha14;
var fecha15;
var fecha16;
var fecha17;
var fecha18;
var beneficiarios;


$(document).ready(function () {

    $("#generar").click(function (e) {
        e.preventDefault();
        mensaje();

        if ($("#check6").is(':checked'))
            beneficiarios = 1;
        else
            beneficiarios = 0;


        if ($("#check7").is(':checked')) {
            fecha1 = $("#fecha11").val();
            fecha2 = $("#fecha12").val();
            fecha3 = $("#fecha11").val();
            fecha4 = $("#fecha12").val();
            fecha5 = $("#fecha11").val();
            fecha6 = $("#fecha12").val();
            fecha7 = $("#fecha11").val();
            fecha8 = $("#fecha12").val();
            fecha9 = $("#fecha11").val();
            fecha10 = $("#fecha12").val();
            fecha13 = $("#fecha11").val();
            fecha14 = $("#fecha12").val();
            fecha15 = $("#fecha11").val();
            fecha16 = $("#fecha12").val();
            fecha17 = $("#fecha11").val();
            fecha18 = $("#fecha12").val();
        } else {
            if ($("#check1").is(':checked')) {
                fecha1 = $("#fecha1").val();
                fecha2 = $("#fecha2").val();
            } else {
                fecha1 = 0;
                fecha2 = 0;
            }
            if ($("#check2").is(':checked')) {
                fecha3 = $("#fecha3").val();
                fecha4 = $("#fecha4").val();
            } else {
                fecha3 = 0;
                fecha4 = 0;
            }
            if ($("#check3").is(':checked')) {
                fecha5 = $("#fecha5").val();
                fecha6 = $("#fecha6").val();
            } else {
                fecha5 = 0;
                fecha6 = 0;
            }
            if ($("#check4").is(':checked')) {
                fecha7 = $("#fecha7").val();
                fecha8 = $("#fecha8").val();
            } else {
                fecha7 = 0;
                fecha8 = 0;
            }
            if ($("#check5").is(':checked')) {
                fecha9 = $("#fecha9").val();
                fecha10 = $("#fecha10").val();
            } else {
                fecha9 = 0;
                fecha10 = 0;
            }
            if ($("#check8").is(':checked')) {
                fecha13 = $("#fecha13").val();
                fecha14 = $("#fecha14").val();
            } else {
                fecha13 = 0;
                fecha14 = 0;
            }
            if ($("#check9").is(':checked')) {
                fecha15 = $("#fecha15").val();
                fecha16 = $("#fecha16").val();
            } else {
                fecha15 = 0;
                fecha16 = 0;
            }
            if ($("#check10").is(':checked')) {
                fecha17 = $("#fecha17").val();
                fecha18 = $("#fecha18").val();
            } else {
                fecha17 = 0;
                fecha18 = 0;
            }

        }

        if ($("#todos_empleado").is(':checked')) {
            $.ajax({
                type: "POST",
                url: "assets/php/reporte_empleados.php",
                data: {
                    "puesto": $("#puesto").val(),
                    "fecha1": fecha1,
                    "fecha2": fecha2,
                    "fecha3": fecha3,
                    "fecha4": fecha4,
                    "fecha5": fecha5,
                    "fecha6": fecha6,
                    "fecha7": fecha7,
                    "fecha8": fecha8,
                    "fecha9": fecha9,
                    "fecha10": fecha10,
                    "fecha13": fecha13,
                    "fecha14": fecha14,
                    "fecha15": fecha15,
                    "fecha16": fecha16,
                    "fecha17": fecha17,
                    "fecha18": fecha18,
                    "departamento": $("#departamento").val(),
                    "beneficiarios": beneficiarios
                },
                success: function (file) {
                    Swal.close();
                    if (file != 0)
                        window.open(file, '_blank');
                    else {
                        Swal.fire({
                            title: 'No se encontraron resultados',
                            text: '',
                            type: 'info',

                            
                        })
                    }

                }
            });
        } else {
            if (empleados.length == 0)
                empleados = "";
            $.ajax({
                type: "POST",
                url: "assets/php/reporte.php",
                data: {
                    "fecha1": fecha1,
                    "fecha2": fecha2,
                    "fecha3": fecha3,
                    "fecha4": fecha4,
                    "fecha5": fecha5,
                    "fecha6": fecha6,
                    "fecha7": fecha7,
                    "fecha8": fecha8,
                    "fecha9": fecha9,
                    "fecha10": fecha10,
                    "fecha17": fecha17,
                    "fecha18": fecha18,
                    "empleados": empleados,
                    "beneficiarios": beneficiarios
                },
                success: function (file) {
                    swal.close();
                    if (file != 0)
                        window.open(file);
                    else {
                        Swal.fire({
                            title: 'No se encontraron resultados',
                            text: '',
                            type: 'info',
                        })
                    }
                }
            });
        }

    });

    $("#check1").change(function () {
        if ($(this).is(':checked')) {
            $("#fecha1").removeClass("apagado");
            $("#fecha2").removeClass("apagado");
            if ($("#check7").is(':checked'))
                $("#check7").click();
        } else {
            $("#fecha1").addClass("apagado");
            $("#fecha2").addClass("apagado");
        }
    });

    $("#check2").change(function () {
        if ($(this).is(':checked')) {
            $("#fecha3").removeClass("apagado");
            $("#fecha4").removeClass("apagado");
            if ($("#check7").is(':checked'))
                $("#check7").click();
        } else {
            $("#fecha3").addClass("apagado");
            $("#fecha4").addClass("apagado");
        }
    });

    $("#check3").change(function () {
        if ($(this).is(':checked')) {
            $("#fecha5").removeClass("apagado");
            $("#fecha6").removeClass("apagado");
            if ($("#check7").is(':checked'))
                $("#check7").click();
        } else {
            $("#fecha5").addClass("apagado");
            $("#fecha6").addClass("apagado");
        }
    });

    $("#check4").change(function () {
        if ($(this).is(':checked')) {
            $("#fecha7").removeClass("apagado");
            $("#fecha8").removeClass("apagado");
            if ($("#check7").is(':checked'))
                $("#check7").click();
        } else {
            $("#fecha7").addClass("apagado");
            $("#fecha8").addClass("apagado");
        }
    });

    $("#check5").change(function () {
        if ($(this).is(':checked')) {
            $("#fecha9").removeClass("apagado");
            $("#fecha10").removeClass("apagado");
            if ($("#check7").is(':checked'))
                $("#check7").click();
        } else {
            $("#fecha9").addClass("apagado");
            $("#fecha10").addClass("apagado");
        }
    });

    $("#check8").change(function () {
        if ($(this).is(':checked')) {
            $("#fecha13").removeClass("apagado");
            $("#fecha14").removeClass("apagado");
            if ($("#check7").is(':checked'))
                $("#check7").click();
        } else {
            $("#fecha13").addClass("apagado");
            $("#fecha14").addClass("apagado");
        }
    });

    $("#check9").change(function () {
        if ($(this).is(':checked')) {
            $("#fecha15").removeClass("apagado");
            $("#fecha16").removeClass("apagado");
            if ($("#check7").is(':checked'))
                $("#check7").click();
        } else {
            $("#fecha15").addClass("apagado");
            $("#fecha16").addClass("apagado");
        }
    });

    $("#check10").change(function () {
        if ($(this).is(':checked')) {
            $("#fecha17").removeClass("apagado");
            $("#fecha18").removeClass("apagado");
            if ($("#check7").is(':checked'))
                $("#check7").click();
        } else {
            $("#fecha17").addClass("apagado");
            $("#fecha18").addClass("apagado");
        }
    });

    $("#check7").click(function () {
        if ($(this).is(':checked')) {
            $("#fecha11").removeClass("apagado");
            $("#fecha12").removeClass("apagado");
            if ($("#check1").is(':checked'))
                $("#check1").click();

            if ($("#check2").is(':checked'))
                $("#check2").click();

            if ($("#check3").is(':checked'))
                $("#check3").click();

            if ($("#check4").is(':checked'))
                $("#check4").click();

            if ($("#check5").is(':checked'))
                $("#check5").click();

            if ($("#check8").is(':checked'))
                $("#check8").click();

            if ($("#check9").is(':checked'))
                $("#check9").click();

            if ($("#check10").is(':checked'))
                $("#check10").click();
        } else {
            $("#fecha11").addClass("apagado");
            $("#fecha12").addClass("apagado");
        }
    });

    $("#todos_empleado").click(function () {
        if ($(this).is(':checked')) {
            // $(".caja-por-empleado").removeClass("col-md-5 col-md-6 col-md-7");
            // $(".caja-por-empleado").addClass("col-md-5");
            // $(".caja-todos-empleado").removeClass("col-md-5 col-md-6 col-md-7");
            // $(".caja-todos-empleado").addClass("col-md-7");
            $(".carta-dos").removeClass("card-header-primary apagado2");
            $(".carta-dos").addClass("card-header-success");
            $(".estado-todos").removeClass("apagado2");
            $("#puesto").removeClass("apagado");
            $("#departamento").removeClass("apagado");
            $("#nuevo-empleado").removeClass("nuevo-empleado");
            $("#nuevo-empleado").addClass("nuevo-empleado-apagado");
            $(".estado-empleado").addClass("apagado2");
            $(".carta-uno").removeClass("card-header-success");
            $(".carta-uno").addClass("card-header-primary apagado2");
            $(".caja-todos-empleado").css('width', "60%");
            $(".caja-por-empleado").css('width', "40%");
            $("#todos_empleado").prop('checked', true);
            $("#por_empleado").prop('checked', false);
            $(".no_aplica").show();

        } else {
            // $(".caja-por-empleado").removeClass("col-md-5 col-md-6 col-md-7");
            // $(".caja-por-empleado").addClass("col-md-7");
            // $(".caja-todos-empleado").removeClass("col-md-5 col-md-6 col-md-7");
            // $(".caja-todos-empleado").addClass("col-md-5");
            $("#nuevo-empleado").removeClass("nuevo-empleado-apagado");
            $("#nuevo-empleado").addClass("nuevo-empleado");
            $(".carta-uno").removeClass("card-header-primary apagado2");
            $(".carta-uno").addClass("card-header-success");
            $(".estado-empleado").removeClass("apagado2");
            $(".carta-dos").removeClass("card-header-success");
            $(".carta-dos").addClass("card-header-primary apagado2");
            $(".estado-todos").addClass("apagado2");
            $(".caja-por-empleado").css('width', "60%");
            $(".caja-todos-empleado").css('width', "40%");
            $("#todos_empleado").prop('checked', false);
            $("#por_empleado").prop('checked', true);
            $(".no_aplica").hide();
            if ($("#check8").is(':checked'))
                $("#check8").click();

            if ($("#check9").is(':checked'))
                $("#check9").click();
        }
    });

    $("#por_empleado").change(function () {
        if ($(this).is(':checked')) {
            // $(".caja-por-empleado").removeClass("col-md-5 col-md-6 col-md-7");
            // $(".caja-por-empleado").addClass("col-md-7");
            // $(".caja-todos-empleado").removeClass("col-md-5 col-md-6 col-md-7");
            // $(".caja-todos-empleado").addClass("col-md-5");
            $("#nuevo-empleado").removeClass("nuevo-empleado-apagado");
            $("#nuevo-empleado").addClass("nuevo-empleado");
            $(".carta-uno").removeClass("card-header-primary apagado2");
            $(".carta-uno").addClass("card-header-success");
            $(".estado-empleado").removeClass("apagado2");
            $(".carta-dos").removeClass("card-header-success");
            $(".carta-dos").addClass("card-header-primary apagado2");
            $(".estado-todos").addClass("apagado2");
            $(".caja-todos-empleado").css('width', "40%");
            $(".caja-por-empleado").css('width', "60%");
            $("#todos_empleado").prop('checked', false);
            $("#por_empleado").prop('checked', true);
            $(".no_aplica").hide();
            if ($("#check8").is(':checked'))
                $("#check8").click();

            if ($("#check9").is(':checked'))
                $("#check9").click();
        } else {
            // $(".caja-por-empleado").removeClass("col-md-5 col-md-6 col-md-7");
            // $(".caja-por-empleado").addClass("col-md-5");
            // $(".caja-todos-empleado").removeClass("col-md-5 col-md-6 col-md-7");
            // $(".caja-todos-empleado").addClass("col-md-7");
            $(".carta-dos").removeClass("card-header-primary apagado2");
            $(".carta-dos").addClass("card-header-success");
            $(".estado-todos").removeClass("apagado2");
            $("#puesto").removeClass("apagado");
            $("#departamento").removeClass("apagado");
            $("#nuevo-empleado").removeClass("nuevo-empleado");
            $("#nuevo-empleado").addClass("nuevo-empleado-apagado");
            $(".estado-empleado").addClass("apagado2");
            $(".carta-uno").removeClass("card-header-success");
            $(".carta-uno").addClass("card-header-primary apagado2");
            $(".caja-por-empleado").css('width', "40%");
            $(".caja-todos-empleado").css('width', "60%");
            $("#todos_empleado").prop('checked', true);
            $("#por_empleado").prop('checked', false);
            $(".no_aplica").show();
        }
    });

    $(".estado-empleado").perfectScrollbar();

    var x = 0;

    $('.fecha-reporte').datepicker({
        language: 'es',
        position: "top center",
        autoClose: 'true',
        todayButton: new Date(),
        onShow: function (dp, animationCompleted) {
            x = 1;
        },
        onHide: function (dp, animationCompleted) {
            x = 0;
        }
    });

    $("#fecha1").data('datepicker').selectDate(new Date());
    $("#fecha2").data('datepicker').selectDate(new Date());
    $("#fecha3").data('datepicker').selectDate(new Date());
    $("#fecha4").data('datepicker').selectDate(new Date());
    $("#fecha5").data('datepicker').selectDate(new Date());
    $("#fecha6").data('datepicker').selectDate(new Date());
    $("#fecha7").data('datepicker').selectDate(new Date());
    $("#fecha8").data('datepicker').selectDate(new Date());
    $("#fecha9").data('datepicker').selectDate(new Date());
    $("#fecha10").data('datepicker').selectDate(new Date());
    $("#fecha11").data('datepicker').selectDate(new Date());
    $("#fecha12").data('datepicker').selectDate(new Date());
    $("#fecha13").data('datepicker').selectDate(new Date());
    $("#fecha14").data('datepicker').selectDate(new Date());
    $("#fecha15").data('datepicker').selectDate(new Date());
    $("#fecha16").data('datepicker').selectDate(new Date());
    $("#fecha17").data('datepicker').selectDate(new Date());
    $("#fecha18").data('datepicker').selectDate(new Date());

    $('.main-panel').scroll(function () {
        if (x == 1)
            $('.fecha-reporte').blur();
    });


    $("#puesto").bind("keyup click", function () {
        var texto = $("#puesto").val();
        $.ajax({
            type: "POST",
            url: "assets/php/filtro.php",
            data: {
                "texto": texto,
                "categoria": "Puesto"
            },
            success: function (html) {
                if (html != 0) {
                    $("#filtro-puesto").html(html);
                    $(".barra-filtro").perfectScrollbar();
                    $(".filtro-caja").mousedown(function () {
                        $("#puesto").val(this.id);
                    });

                } else {
                    $("#filtro-puesto").html("");
                }
            }
        });
    });

    $("#puesto").blur(function () {
        $("#filtro-puesto").html("");
    });

    $("#departamento").bind("keyup click", function () {
        var texto = $("#departamento").val();

        $.ajax({
            type: "POST",
            url: "assets/php/filtro.php",
            data: {
                "texto": texto,
                "categoria": "Departamento"
            },
            success: function (html) {
                if (html != 0) {
                    $("#filtro-departamento").html(html);
                    $(".barra-filtro").perfectScrollbar();
                    $(".filtro-caja").mousedown(function () {
                        $("#departamento").val(this.id);
                    });

                } else {
                    $("#filtro-departamento").html("");
                }
            }
        });
    });

    $("#departamento").blur(function () {
        $("#filtro-departamento").html("");
    });

    $("#nuevo-empleado").click(function () {
        var agregar = 0;
        var nombre = "";
        var numero = "";
        Swal.fire({
            html: '<div class="row titulo-buscar">' +
                '<div class="col-4">' +
                '<h4>RFC</h4>' +
                '</div>' +
                '<div class="col-8">' +
                '<h4>Nombre de empleado</h4>' +
                '</div>' +
                '<div class="col-4">' +
                '<input type="input" class="numero-empleado"/>' +
                '</div>' +
                '<div class="col-8">' +
                '<div class="nombre-empleado" >Sin resultados</div>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            

            width: '45em',
            onOpen: function () {
                $(".numero-empleado").focus();

                $(".numero-empleado").keyup(function () {
                    var texto = $(".numero-empleado").val();
                    $.ajax({
                        type: "POST",
                        url: "assets/php/filtroEmpleado.php",
                        data: {
                            "texto": texto
                        },
                        success: function (html) {
                            if (html != 0) {
                                $(".nombre-empleado").html(html);
                                agregar = 1;
                                nombre = $(".nombre-empleado").html();
                                numero = $(".numero-empleado").val();
                            } else {
                                $(".nombre-empleado").html("Sin resultados");
                                agregar = 0;
                            }

                        }
                    });
                });
            }
        }).then((result) => {
            if (result.value) {
                if ((agregar == 1) && comprobar(numero)) {
                    $(".chat-nuevo").hide();
                    $(".titulos").html('<th>ID</th>' +
                        '<th>Nombre de empleado</th>' +
                        '<th>Eliminar</th>');


                    $(".tabla-temporal").append('<tr><td class="titulo2">' + numero + '</td><td class="titulo2">' + nombre + '</td><td> <a class="material-icons btn1" id="' + numero + '" onclick="borrar(this.id)">delete</a></td></tr>');

                    Swal.fire({
                        title: 'Correcto',
                        text: 'Empleado agregado',
                        type: 'success',

                        
                    });

                    if (empleados.length == 0)
                        empleados = [];

                    empleados.push(numero);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al agregar el empleado',
                        type: 'error',

                        
                    })
                }
            }
        });
    });
});


function borrar(id) {
    Swal.fire({
        title: 'Eliminar',
        text: "¿Eliminar este elemento?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si',
        cancelButtonText: 'No',
        
        
    }).then(function (result) {
        if (result.value) {
            $("#" + id).closest('tr').remove();
            empleados.splice(empleados.indexOf(id), 1);
            if (empleados.length === 0) {
                $(".chat-nuevo").show();
                $(".titulos").html('<th></th>' +
                    '<th></th>' +
                    '<th></th>');
            }
        }
    })
};

function comprobar(numero) {
    if (empleados.indexOf(numero) == -1)
        return true;
    else
        return false;
};

function mensaje() {
    let timerInterval
    Swal.fire({
        title: 'Cargando archivo',
        html: 'Espere porfavor',
        allowOutsideClick: false,
        

        onBeforeOpen: () => {
            Swal.showLoading()
        },
        onClose: () => {
            clearInterval(timerInterval)

        }
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {}
    })
};