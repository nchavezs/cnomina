var ano;
var id_periodo;
var id_prenomina;

function seleccionar_tipoperiodo() {
    $(".periodo .pagina").addClass("adp-hide");
    ADP.show($(".periodo .pagina_1")[0], 'slide-left');
}

function seleccionar_ano(id) {
    if (id != null) {
        id_periodo = id;
    }
    $(".periodo .pagina").addClass("adp-hide");
    ADP.show($(".periodo .pagina_2")[0], 'slide-left');
}

function seleccionar_periodo(id) {
    ano = id;
    $(".periodo .pagina").addClass("adp-hide");
    ADP.show($(".periodo .pagina_3")[0], 'slide-left');

    $.ajax({
        url: "assets/php/consulta_periodo.php",
        type: "POST",
        data: {
            ano: ano,
            id_periodo: id_periodo
        },
        success: function (data) {
            $(".periodos").html(data);
        }
    })

}

function finalizar(id) {
    $.ajax({
        url: "assets/php/iniciar_sesion.php",
        type: "POST",
        data: {
            id_prenomina: id
        },
        success: function (data) {
            window.location.href = data;
        }
    })
}