$(document).ready(
    function() {
        tablas();
        $('.sources').change(function() {
            tablas();
        });
    }
);

function tablas() {
    var ano = $("#ano :selected").val();
    var mes = $("#mes :selected").val();
    $.ajax({
        type: "POST",
        url: "assets/php/tablas.php",
        data: {
            "ano": ano,
            "mes": mes
        },
        success: function(html) {
            console.log(html);
            $("#tablas").html(html);
        },
        error: function() {}
    });
};