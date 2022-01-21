<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$myid = $_SESSION['usuario'];

$sql = "SELECT nombre FROM Usuario WHERE RFC = '".$id."'";
$consulta = mysqli_query($conexion, $sql);
$usuario = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Mensaje WHERE 
    emisor = '".$id."' AND 
    receptor = '".$myid."'
";
$consulta = mysqli_query($conexion, $sql);
$total_nuevos = mysqli_num_rows($consulta);

$sql = "SELECT * FROM Mensaje WHERE 
    emisor = '".$myid."' AND 
    receptor = '".$id."' OR 
    emisor = '".$id."' AND 
    receptor = '".$myid."'
";

$consulta = mysqli_query($conexion, $sql);
$total = mysqli_num_rows($consulta);

$datos["html"] = "";
$datos["total"] = $total_nuevos;
$datos["nombre"] = $usuario["nombre"];

if($consulta &&  $total > 0){
    $sql = "UPDATE Mensaje SET estado = 1 WHERE 
    receptor = '".$myid."' AND 
    emisor = '".$id."' ";
    mysqli_query($conexion, $sql);

    while($mensaje = mysqli_fetch_array($consulta)){
        $clase = "";
        if($mensaje["emisor"] == $myid){
            $clase = "mio";
        }
        $hora = date("h:i A", strtotime($mensaje["elaboracion"]));

        $datos["html"] = $datos["html"].'<div class="mensajeria_mensaje '.$clase.'">
                <img src="assets/img/user.png" alt="">
                <div class="mensajeria_contenido">'.$mensaje["mensaje"].'
                    <div class="mensajeria_hora">'.$hora.'</div>
                </div>
            </div>';
    }
}

mysqli_close($conexion);

echo json_encode($datos);