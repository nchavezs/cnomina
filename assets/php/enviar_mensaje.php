<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$url = $_POST['url'];
$mensaje = trim($_POST['mensaje']);
$myid = $_SESSION['usuario'];

$sql = "INSERT INTO Mensaje(receptor, emisor, mensaje,url) VALUES(
    '" . $id . "',
    '" . $myid . "',
    '" . $mensaje . "',
    '" . $url . "'
)";

if ($url == "") {
    if ($id != "" && $mensaje != "") {

        $consulta = $conexion->query($sql);
        if ($consulta) {
            $id_mensaje = mysqli_insert_id($conexion);
            $sql = "SELECT * FROM Mensaje WHERE id_mensaje = " . $id_mensaje;
            $consulta = $conexion->query($sql);
            $res = mysqli_fetch_array($consulta);
            $hora = date("h:i A", strtotime($res["elaboracion"]));
            echo '<div class="mensajeria_mensaje mio">
                    <div class="mensajeria_contenido">' . $mensaje . '
                        <div class="mensajeria_hora">' . $hora . '</div>
                    </div>
                </div>';
        } else {
            echo 0;
        }

    } else {
        echo 0;
    }

} else {
    if ($id != "" && $url != "") {

        $consulta = $conexion->query($sql);
        if ($consulta) {
            $id_mensaje = mysqli_insert_id($conexion);
            $sql = "SELECT * FROM Mensaje WHERE id_mensaje = " . $id_mensaje;
            $consulta = $conexion->query($sql);
            $res = mysqli_fetch_array($consulta);
            $hora = date("h:i A", strtotime($res["elaboracion"]));
            echo '<div class="mensajeria_mensaje mio">
                    <div class="mensajeria_contenido">
                        
                        <a class="negrita text-info" target="_blank" href="assets/mensajes/'.$id.'/'.$res["url"].'">
                            <i class="material-icons">file_present</i>'.$res["url"].'
                        </a>
                        <div class="mensajeria_hora">' . $hora . '</div>
                    </div>
                </div>';
        } else {
            echo 0;
        }

    } else {
        echo 0;
    }
}

$conexion->close();
