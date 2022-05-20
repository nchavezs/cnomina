<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$myid = $_SESSION['usuario'];

$sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Mensaje WHERE
    emisor = '" . $id . "' AND
    receptor = '" . $myid . "'
";
$consulta = $conexion->query($sql);
$total_nuevos = mysqli_num_rows($consulta);

$sql = "SELECT * FROM Mensaje WHERE
    emisor = '" . $myid . "' AND
    receptor = '" . $id . "' OR
    emisor = '" . $id . "' AND
    receptor = '" . $myid . "'";

$consulta = $conexion->query($sql);
$total = mysqli_num_rows($consulta);

$datos["html"] = "";
$datos["total"] = $total_nuevos;
$datos["nombre"] = $usuario["nombre"];

if ($consulta && $total > 0) {
    $sql = "UPDATE Mensaje SET estado = 1 WHERE
    receptor = '" . $myid . "' AND
    emisor = '" . $id . "' ";
    $conexion->query($sql);

    while ($mensaje = mysqli_fetch_array($consulta)) {
        $clase = "";
        if ($mensaje["emisor"] == $myid) {
            $clase = "mio";
        }
        $hora = date("h:i A", strtotime($mensaje["elaboracion"]));


        if($mensaje["url"] == ""){
            $contenido_mensaje = $mensaje["mensaje"] . '<div class="mensajeria_hora">' . $hora . '</div>';
        }else{
            $contenido_mensaje = '<a class="negrita text-white file_contenido" target="_blank" href="assets/mensajes/'.$id.'/'.$mensaje["url"].'">
            <i class="material-icons">file_present</i>'.$mensaje["url"].'<div class="mensajeria_hora">' . $hora . '</a>';
        }

        $datos["html"] = $datos["html"] . '<div class="mensajeria_mensaje ' . $clase . '">
                <img src="assets/img/user.png" alt="">
                <div class="mensajeria_contenido">'.$contenido_mensaje.'</div>
                </div>
            </div>';
    }
}

$conexion->close();

echo json_encode($datos);
