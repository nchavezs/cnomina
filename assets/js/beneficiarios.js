inicio();

function inicio() {
    $.ajax({
        type: "POST",
        url: "assets/php/beneficiarios.php",
        success: function (data) {
            $(".bene-caja").html(data);
        }
    });
}

function nuevoBeneficiario() {
    var url = "";
    Swal.mixin({
        showCancelButton: true,
        progressSteps: ["1", "2", "3"],
        

        allowOutsideClick: false,
    }).queue([{
            title: "Nombre del beneficiario",
            confirmButtonText: "Siguiente &rarr;",
            input: "text",
            inputValidator: (value) => {
                return !value && "Completa los campos"
            }
        },
        {
            title: "Parentesco",
            confirmButtonText: "Siguiente &rarr;",
            input: "select",
            inputClass: "swal2-input",
            inputPlaceholder: "SELECCIONA",
            inputOptions: {
                "PADRE": "PADRE",
                "MADRE": "MADRE",
                "HERMANO(A)": "HERMANO(A)",
                "HIJO(A)": "HIJO(A)",
                "ESPOSO(A)": "ESPOSO(A)",
                "CONYUGUE": "CONYUGUE",
                "OTRO": "OTRO"
            },
            inputValidator: (value) => {
                return !value && "Selecciona una opción"
            }
        },
        {
            title: 'Subir archivo',
            html: '<div class="col-md-12"><p>Seleccione un archivo, podrá cargar el archivo en cualquier momento.</p></div><div class="col-md-12"><input type="file" accept=".pdf, .xlsx" id="file" /><label for="file" class="btn-3"><span><i class="material-icons">cloud_upload</i>Subir archivo</span></label></div>',
            confirmButtonText: "Finalizar registro &rarr;",
            onOpen: function () {
                $("#file").change(function () {
                    if ($("#file").val() != "") {
                        $.blockUI({
                            message: "<div class='circulo'></div><h5>Cargando archivo ...</h5>",
                        });

                        var formData = new FormData();
                        var files = $("#file")[0].files[0];
                        formData.append("file", files);

                        $.ajax({
                            url: "assets/php/beneficiarioArchivo.php",
                            type: "post",
                            data: formData,
                            contentType: false,
                            processData: false,
                            cache: false,
                            success: function (data) {
                                url = data;
                                $("#file").val("");
                                $.unblockUI();
                                md.showNotification("top", "right", "Archivo cargado correctamente.");
                            }
                        });
                    }
                });
            }
            // preConfirm: () => {
            //     if (document.getElementById('file').value == "")
            //         Swal.showValidationMessage("Seleccione un archivo")
            // },
        }
    ]).then((result) => {
        if (result.value) {
            var resultado = JSON.stringify(result.value);
            var datos = jQuery.parseJSON(resultado);
            var nombre = datos[0];
            var parentesco = datos[1];
            $.post("assets/php/nuevoBeneficiario.php", {
                nombre: nombre,
                parentesco: parentesco,
                url: url
            }).done(function (data) {
                Swal.fire(
                    "Correcto",
                    "Nuevo beneficiario agregado",
                    "success"
                )
                inicio();
            });
        }
    })
}

$(document).on("click", "#bene-nuevo", function () {
    nuevoBeneficiario();
});

$(document).on("click", ".btn-bene", function () {
    nuevoBeneficiario();
});

$(document).on("click", ".editar", function () {
    var data = this.name.split("-");
    var url = data[2];
    Swal.mixin({
        showCancelButton: true,
        progressSteps: ["1", "2", "3"],
        

        allowOutsideClick: false,
    }).queue([{
            title: "Nombre del beneficiario",
            confirmButtonText: "Siguiente &rarr;",
            input: "text",
            inputValue: data[0],
            inputValidator: (value) => {
                return !value && "Completa los campos"
            }
        },
        {
            title: "Parentesco",
            confirmButtonText: "Siguiente &rarr;",
            input: "select",
            inputValue: data[1],
            inputClass: "swal2-input",
            inputPlaceholder: "SELECCIONA",
            inputOptions: {
                "PADRE": "PADRE",
                "MADRE": "MADRE",
                "HERMANO(A)": "HERMANO(A)",
                "HIJO(A)": "HIJO(A)",
                "ESPOSO(A)": "ESPOSO(A)",
                "CONYUGUE": "CONYUGUE",
                "OTRO": "OTRO"
            },
            inputValidator: (value) => {
                return !value && "Selecciona una opción"
            }
        }, {
            title: 'Sobreescribir archivo',
            html: '<div class="col-md-12"><p>Seleccione un archivo, podrá modificar el archivo en cualquier momento.</p></div><div class="col-md-12"><input type="file" accept=".pdf, .xlsx" id="file" /><label for="file" class="btn-3"><span><i class="material-icons">cloud_upload</i>Subir archivo</span></label></div>',
            confirmButtonText: "Guardar &rarr;",
            onOpen: function () {
                $("#file").change(function () {
                    if ($("#file").val() != "") {
                        $.blockUI({
                            message: "<div class='circulo'></div><h5>Cargando archivo ...</h5>",
                        });

                        var formData = new FormData();
                        var files = $("#file")[0].files[0];
                        formData.append("file", files);

                        $.ajax({
                            url: "assets/php/beneficiarioArchivo.php",
                            type: "post",
                            data: formData,
                            contentType: false,
                            processData: false,
                            cache: false,
                            success: function (data) {
                                url = data;
                                $("#file").val("");
                                $.unblockUI();
                                md.showNotification("top", "right", "Archivo cargado correctamente.");
                            }
                        });
                    }
                });
            }
            // preConfirm: () => {
            //     if (document.getElementById('file').value == "")
            //         Swal.showValidationMessage("Seleccione un archivo")
            // },
        }
    ]).then((result) => {
        if (result.value) {
            var resultado = JSON.stringify(result.value);
            var datos = jQuery.parseJSON(resultado);
            var nombre = datos[0];
            var parentesco = datos[1];
            $.post("assets/php/modificarBeneficiario.php", {
                nombre: nombre,
                parentesco: parentesco,
                id: this.id,
                url: url
            }).done(function (a) {
                Swal.fire(
                    "Correcto",
                    "Beneficiario modificado",
                    "success"
                )
                inicio();
            });
        }
    })
});

$(document).on("click", ".eliminar", function () {
    Swal.fire({
        title: "Eliminar beneficiario",
        text: "¿Seguro que quieres eliminar a este beneficiario?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
        reverseButtons: "true"
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "assets/php/eliminarBeneficiario.php",
                data: {
                    "id": this.id
                },
                success: function () {
                    Swal.fire(
                        "Correcto",
                        "Eliminado correctamente",
                        "success"
                    );
                    inicio();
                }
            });
        }
    })
});

function archivo(url) {
    if (url === "") {
        Swal.fire(
            'Sin archivo',
            'No se ha encontrado ningún archivo',
            'warning'
        )
    } else
        window.open(url, '_blank');
};