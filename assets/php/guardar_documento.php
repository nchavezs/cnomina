<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$nombre = $_POST['nombre'] ?? null;
$datos["html"] = "";
$datos["success"] = false;
$datos["mensaje"] = "Completa todos los campos.";
// ------------------------------------------------------------------------------------------------
if ($nombre) {
    $sql = "SELECT * FROM Documento WHERE nombre = '" . $nombre . "'";
    $consulta = $conexion->query($sql);
    $total = mysqli_num_rows($consulta);

    if ($total == 0) {
        $sql = "INSERT INTO Documento(nombre) VALUES('" . $nombre . "');";
        $conexion->query($sql);
        $id_documento = mysqli_insert_id($conexion);
    } else {
        $row = $consulta->fetch_assoc();
        $id_documento = $row['id_documento'];
    }

    $sql = "INSERT INTO Fichero(id_documento, RFC) VALUES(" . $id_documento . ", '" . $id . "');";
    $consulta = $conexion->query($sql);
    $nuevo_id = $conexion->insert_id;

    $sql = "SELECT Fichero.*, (SELECT nombre FROM Documento WHERE id_documento = Fichero.id_documento) as documento
    FROM Fichero WHERE id_fichero = " . $nuevo_id;
    $consulta = $conexion->query($sql);
    $fichero = $consulta->fetch_assoc();

    ob_start();
    include 'expediente/item.php'; 
    $html = ob_get_clean();
    
    $datos["html"] = $html;
    $datos["success"] = true;
    $datos["mensaje"] = "Documento agregado correctamente.";
}

$conexion->close();

echo json_encode($datos);
