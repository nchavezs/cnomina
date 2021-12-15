<?php
include "conexion.php";
$conexion = conexion();
session_start();
$id = $_SESSION['usuario'];
date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
$fecha = date('d/m/Y h:i a', time());
$receptor = $_POST["receptor"];
$mensaje = trim($_POST["mensaje"]);
$chat = $_POST["chat"];

if ($mensaje === "" || $mensaje == null) {
    echo 0;
} else {
    $sql = "INSERT INTO Mensaje(id_chat,mensaje,fecha,emisor,receptor) VALUES(" . $chat . ",'" . $mensaje . "','" . $fecha . "','" . $id . "', '" . $receptor . "')";
    if (mysqli_query($conexion, $sql)) {
        echo '<div class="msn-mensaje msn-derecha">
					  <h7>' . $mensaje . '</h7>
					  <h5 class="msn-fecha">' . substr($fecha, 10) . '</h5>
				  </div>';
    }
}

mysqli_close($conexion);
