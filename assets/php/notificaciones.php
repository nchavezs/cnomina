<?php
session_start();
include("conexion.php");
$conexion = conexion();
$id = $_SESSION['usuario'];
$categoria = $_SESSION['categoria'];

$sql = "SELECT * FROM Mensaje WHERE estado = 0 AND receptor = '" . $id . "'";
$consulta = $conexion->query($sql);

if ($consulta) {
	$total = mysqli_num_rows($consulta);
	if ($total == 0) {
		echo '<a class="dropdown-item" href="#">No tiene notificaciones</a>';
	} else {
		$sql = "SELECT 
						id_chat, 
						(SELECT emisor FROM Mensaje WHERE id_chat = Mensaje.id_chat LIMIT 1), 
						(SELECT titulo FROM Chat WHERE id_chat = Mensaje.id_chat), COUNT(*) 
					FROM Mensaje 
					WHERE estado = 0 
					AND receptor = '" . $id . "' 
					GROUP BY id_chat";

		$resultado = $conexion->query($sql);

		while ($res = mysqli_fetch_row($resultado)) {
			echo '<a id="' . $res[0] . '-' . $res[2] . '" class="dropdown-item" href="#" onclick="accion(this.id);">' . $res[3] . ' mensaje(s) sin leer en ' . $res[2] . '</a>';
		}
	}
} else
	echo '<a class="dropdown-item" href="#">No tiene notificaciones</a>';

$conexion->close();
