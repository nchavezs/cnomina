<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$actualizar = $_POST['actualizar'] ?? null;

function get_fichero($conexion, $id)
{
    $sql = "SELECT Fichero.*, (SELECT nombre FROM Documento WHERE id_documento = Fichero.id_documento) as documento
    FROM Fichero WHERE id_fichero = " . $id;
    $consulta = $conexion->query($sql);
    return $consulta->fetch_assoc();
}

$fichero = get_fichero($conexion, $id);
$archivo_anterior = "../" . $fichero['url'];

if ($fichero['url'] && file_exists($archivo_anterior)) {
    unlink($archivo_anterior);
}

if ($actualizar) {
    $sql = "UPDATE Fichero SET url = NULL WHERE id_fichero = " . $id;
    $consulta = $conexion->query($sql);
    $fichero = get_fichero($conexion, $id);
    ob_start();
    include 'expediente/item.php';
    $html = ob_get_clean();
} else {
    $sql = "DELETE FROM Fichero WHERE id_fichero = " . $id;
    $consulta = $conexion->query($sql);
    $success = true;
}

$datos["html"] = $html ?? '';
$datos["success"] = $success ?? false;
$datos["mensaje"] = "Documento eliminado correctamente.";
echo json_encode($datos);
$conexion->close();
