$(document).ready(function () {
    cargar_prenominas();
});

function cargar_prenominas() {
    $.ajax({
        url: "assets/php/consulta_prenomina.php",
        type: "POST",
        success: function (data) {
            $(".prenominas").html(data);
        }
    });
}

function nueva_prenomina() {
    $.post("assets/php/nuevaPrenomina.php").done(function (html) {
        Swal.fire({
            html: html,
            allowOutsideClick: false,
            showConfirmButton: false,
            width: "55em"
        });

        $("#form-prenomina").on("submit", function (e) {
            e.preventDefault();
            prenomina();
        });
    });
};

function prenomina() {
    md.showNotification("top", "right", "Generando prenómina ...");
    Swal.close();
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
                cargar_prenominas();

                Swal.fire({
                    title: 'Correcto',
                    text: 'Prenómina generada',
                    type: 'success'
                });

                actualizar_periodo();
            } else {
                Swal.fire({
                    title: 'Error',
                    text: 'Registro no agregado',
                    type: 'error'
                })
            }
        }
    });
}

function autorizar() {
    $("#modal .modal_titulo").html("Cerrar el periodo actual");
    $("#modal .modal-body").html('Estas a punto de cerrar el periodo actual, por lo cual no podrás realizar ningún registro anterior, ¿deseas continuar?.');
    mostrar_modal();

    $("#modal_aceptar").off().click(function () {
        $.ajax({
            url: "assets/php/autorizar.php",
            type: "POST",
            success: function (data) {
                ocultar_modal();
                if (data != 0) {
                    cargar_prenominas();
                    actualizar_periodo();
                    Swal.fire({
                        title: 'Correcto',
                        text: 'Periodo actualizado',
                        type: 'success'
                    })
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Registro no agregado',
                        type: 'error'
                    })
                }
            }
        });
    });

}


function actualizar_periodo(){
    $.ajax({
        url:"assets/php/actualizar_periodo",
        type:"POST",
        success: function(data){
            $(".cambiar_periodo").parent().html(data);
        }
    })
}

// function eliminar(id, event) {
//     event.stopPropagation();
//     Swal.fire({
//         title: "Eliminar",
//         text: "¿Seguro que quieres eliminar este elemento?",
//         type: "warning",
//         showCancelButton: true,
//         confirmButtonText: "Si",
//         cancelButtonText: "Cancelar"
//     }).then((result) => {
//         if (result.value) {
//             $.ajax({
//                 type: "POST",
//                 url: "assets/php/eliminarPrenomina.php",
//                 data: {
//                     "id": id
//                 },
//                 success: function (html) {
//                     if (html == 1) {
//                         Swal.fire({
//                             title: 'Correcto',
//                             text: 'Eliminado correctamente',
//                             type: 'success'
//                         })
//                     } else {
//                         Swal.fire({
//                             title: 'Error',
//                             text: 'Elemento no eliminado',
//                             type: 'error',
//                         })
//                     }
//                     $('#tabla-prenomina').DataTable().ajax.reload();
//                 }
//             });
//         }
//     })
// };