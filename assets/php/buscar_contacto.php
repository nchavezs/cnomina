<?php
session_start();
include "conexion.php";
$conexion = conexion();
$texto = trim($_POST['texto']);
$myid = $_SESSION['usuario'];

$sql = "SELECT nombre, RFC, puesto, 
(SELECT elaboracion FROM Mensaje WHERE receptor = '" . $myid . "' AND emisor = Usuario.RFC ORDER BY elaboracion DESC LIMIT 1) AS ultimo 
FROM Usuario WHERE 
categoria = 'user' AND 
nombre LIKE '%" . $texto . "%' 
ORDER BY ultimo DESC";

$datos["total"] = 0;
$datos["html"] = "";

$consulta = mysqli_query($conexion, $sql);
$total = mysqli_num_rows($consulta);

if ($consulta && $total > 0) {
    while ($usuario = mysqli_fetch_array($consulta)) {
        $sql = "SELECT * FROM Mensaje WHERE
            receptor = '" . $myid . "' AND
            emisor = '" . $usuario["RFC"] . "' AND
            estado = 0
        ";
        $consulta2 = mysqli_query($conexion, $sql);
        $numero = mysqli_num_rows($consulta2);
        $datos["total"] = $datos["total"] + $numero;

        $sql = "SELECT * FROM Mensaje WHERE
            receptor = '" . $myid . "' AND
            emisor = '" . $usuario["RFC"] . "'
            ORDER BY elaboracion DESC
        ";
        $consulta3 = mysqli_query($conexion, $sql);
        $fecha = "";
        if ($consulta3 && mysqli_num_rows($consulta3) > 0) {
            $mensaje = mysqli_fetch_array($consulta3);
            $fecha = date("d-M", strtotime($mensaje["elaboracion"]));
        }

        $funcion_chat = "mostrar_chat('" . $usuario["RFC"] . "', '" . $usuario["nombre"] . "')";
        $datos["html"] = $datos["html"] . '<div id= "'.$usuario["RFC"].'" class="mensajeria_contacto" onclick="' . $funcion_chat . '">
                <img src="assets/img/user.png" alt="">
                <div>
                <p>' . $usuario["nombre"] . '</p>
                <small class="text-muted">' . $usuario["puesto"] . '</small>
                </div>
                <span class="mensajeria_fecha text-muted">' . $fecha . '</span>';
        if ($numero > 0) {
            $datos["html"] = $datos["html"] . '<span class="mensajeria_noti">' . $numero . '</span>';
        }

        $datos["html"] = $datos["html"] . '</div>';
    }
} else {
    $datos["html"] = '<div class="mensajeria_resultados">No se encontró ningún contacto</div> ';
}

mysqli_close($conexion);

echo json_encode($datos);