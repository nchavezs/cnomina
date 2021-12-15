<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_SESSION['usuario'];
$condicion = $_POST["condicion"];
$texto = $_POST["texto"];

echo '<script>
		$(".msn-contenido").perfectScrollbar();
		$(".msn-responder").submit(function(e){
			e.preventDefault();
			var contenido = this.id.split("y");
			var datos = $(this).serialize()
			$.ajax({
				type: "POST",
				url: "assets/php/nuevoMensaje.php",
				data: datos,
				success: function (html) {
					if(html != 0){
						$("#"+contenido[0]+"z").append(html);
						$("#"+contenido[0]+"z").scrollTop($("#"+contenido[0]+"z")[0].scrollHeight);
						mensaje_enviado();
					}
					$("#"+contenido[0]+"y")[0].reset();
				}
			});

		});

		$(".btn-mostrar").click(function(){
			var contenido = this.id.split("x");
			if($("#"+contenido[0]+"x").text() === "Mostrar"){
				$("#"+contenido[0]).slideToggle();
				$("#"+contenido[0]+"z").scrollTop($("#"+contenido[0]+"z")[0].scrollHeight);
				$("#"+contenido[0]+"x").text("Ocultar");
			}
			else{
				$("#"+contenido[0]).slideToggle();
				$("#"+contenido[0]+"z").scrollTop($("#"+contenido[0]+"z")[0].scrollHeight);
				$("#"+contenido[0]+"x").text("Mostrar");
			}
		});

		$(".numero").click(function(){
			var contenido = this.id.split("f");
			if($("#"+contenido[0]+"x").text() === "Mostrar"){
				$("#"+contenido[0]).slideToggle();
				$("#"+contenido[0]+"z").scrollTop($("#"+contenido[0]+"z")[0].scrollHeight);
				$("#"+contenido[0]+"x").text("Ocultar");
			}
			else{
				$("#"+contenido[0]).slideToggle();
				$("#"+contenido[0]+"z").scrollTop($("#"+contenido[0]+"z")[0].scrollHeight);
				$("#"+contenido[0]+"x").text("Mostrar");
			}
		});


		$(".chat-nuevo").click(function(){
			$.ajax({
				url: "assets/php/chat-formulario.php",
				success: function (html) {
					nuevo(html);
				}
			});
		});

		$(".button-nuevo").click(function(){
			$.ajax({
				url: "assets/php/chat-formulario.php",
				success: function (html) {
					nuevo(html);
				}
			});
		});

		function nuevo(a) {
			Swal.fire({
				title: "Iniciar conversación",
				html: a,
				showCancelButton: false,
				showConfirmButton: false,
				allowOutsideClick: false,


			});

			$("#file").change(function () {
				if ($("#file").val() != "") {
					$.blockUI({
						message: "<div class=\'circulo\'></div><h5>Cargando archivo ...</h5>",
					});

					var formData = new FormData();
					var files = $("#file")[0].files[0];
					formData.append("file", files);

					$.ajax({
						url: "assets/php/mensajeArchivo.php",
						type: "post",
						data: formData,
						contentType: false,
						processData: false,
						cache: false,
						success: function (data) {
							$("#archivo").val(data);
							$.unblockUI();
							md.showNotification("top", "right", "Archivo cargado correctamente.");
						}
					});
				}
			});

			$("#formulario-chat").submit(function(e){
				e.preventDefault();
				var titulo = $("#form-titulo").val();
				var mensaje = $("#form-mensaje").val();
				var url = $("#archivo").val();

				$.ajax({
					type: "POST",
					url: "assets/php/nuevoChat.php",
					data: {
						"titulo": titulo,
						"mensaje": mensaje,
						"url": url
					},
					success: function (html) {
						if(html != 0){
							if($(".msn-caja")){
								$(".msn-caja").html(html);
							}else{
								$(".msn-caja-chat").html(html);
							}
							mensaje_enviado();
						}
						else{
							error();
							$.post( "assets/php/reversa.php" );
						}
					}
				});

			});
		};

		function cancelar(){
			swal.close();
			$.post( "assets/php/reversa.php");
		};

		function mensaje_enviado() {
			Swal.fire({
				title: "Correcto",
				text: "Enviado",
				type: "success"
			})
		};

		function error() {
			Swal.fire({
				title: "Error",
				text: "No enviado",
				type: "error",


			})
		};
	</script>';

if ($condicion == 0) {
    $sql1 = "SELECT * FROM Chat WHERE RFC = '" . $id . "' AND (titulo LIKE '%" . $texto . "%' OR fecha LIKE '%" . $texto . "%') ORDER BY id_chat DESC";
} else {
    $sql1 = "SELECT * FROM Chat WHERE RFC = '" . $id . "' AND id_chat = " . $texto;
}

$resultado1 = mysqli_query($conexion, $sql1);

$total = mysqli_num_rows($resultado1);
if ($total == 0) {
    echo '<div class="vacia">
					<i class="material-icons btn2">sms_failed</i>
					<h1>Sin solicitudes</h1>
					<div class="chat-nuevo">
							<i id="chat-icono" class="material-icons">add</i>
							<p id="chat-texto">Inicia una nueva</p>
						</div>
					</div>
				</div>';
} else {
    echo '<div class="button-nuevo"><span>Nueva solicitud</span>
					<svg>
						<polyline class="o1" points="0 0, 250 0, 250 50, 0 50, 0 0"></polyline>
						<polyline class="o2" points="0 0, 250 0, 250 50, 0 50, 0 0"></polyline>
					</svg>
				</div>';
}
$total = $total + 1;
while ($res1 = mysqli_fetch_row($resultado1)) {
    $total = $total - 1;
    echo '<div class="card">
			<div class="msn-titulo">
				<div id="' . $res1[0] . 'f' . '" class="numero">
					<p>' . $total . '</p>
				</div>
				<div class="row">
					<div class="col-8">
						<h4>' . ucfirst(strtolower($res1[2])) . '</h4>
						<h7><i class="material-icons">date_range</i>' . $res1[3] . '</h7>
					</div>
					<div class="col-4 msn-mostrar">
						<button id="' . $res1[0] . 'x' . '" class="btn-mostrar">Mostrar</button>
					</div>
				</div>
			</div>
			<div id="' . $res1[0] . '" class="msn-chat">
				<div id="' . $res1[0] . 'z' . '" class="msn-contenido">';
    $sql2 = "SELECT * FROM Mensaje WHERE id_chat = " . $res1[0];
    $resultado2 = mysqli_query($conexion, $sql2);
    while ($res2 = mysqli_fetch_row($resultado2)) {
        echo '<div class="msn-mensaje ';
        if ($id == $res2[4]) {
            echo 'msn-derecha';
        } else {
            echo 'msn-izquierda';
        }

        echo '">
				  <h7>' . $res2[2] . '</h7>
				  <h5 class="msn-fecha">' . substr($res2[3], 10) . '</h5>
			  </div>';
        if (!is_null($res2[7])) {
            echo '<div class="msn-mensaje ';
            if ($id == $res2[4]) {
                $clase = 'msn-derecha';
                echo 'msn-derecha';
            } else {
                $clase = 'msn-izquierda';
                echo 'msn-izquierda';
            }
            $descarga = explode("/", $res2[7]);
            echo '">
						<a class="' . $clase . '"href="' . $res2[7] . '" download><i class="material-icons ">attach_file</i> ' . $descarga[3] . ' </a>
						<h5 class="msn-fecha">' . substr($res2[3], 10) . '</h5>
					</div>';
        }
    }
    echo '</div>
				<form id="' . $res1[0] . 'y' . '" class="msn-responder">
					<input type="text" name="mensaje" class="msn-input" placeholder="Escribe tu mensaje ..." autocomplete="off">
					<input type="hidden" name="receptor" value="admin">
					<input type="hidden" name="chat" value="' . $res1[0] . '">
					<button type="submit" class="msn-enviar" ><i class="material-icons enviar-icono">send</i></button>
				</form>
			</div>
		</div>';
}

mysqli_close($conexion);
