<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$archivo = $_FILES['file']['name'] ?? null;
$datos["html"] = "";
$datos["success"] = false;
// ------------------------------------------------------------------------------------------------
if (isset($archivo) && $archivo) {
    $sql = "SELECT * FROM Fichero WHERE id_fichero = " . $id;
    $consulta = $conexion->query($sql);
    $fichero = $consulta->fetch_assoc();

    $rfc = $fichero["RFC"];
    $ruta = '../ficheros/' . $rfc;
    if (!file_exists($ruta)) {
        mkdir($ruta, 0777, true);
    }
    
    $archivo_anterior = "../" . $fichero['url'];
    if ($fichero['url'] && file_exists($archivo_anterior)) {
        unlink($archivo_anterior);
    }

    $ext = pathinfo($archivo, PATHINFO_EXTENSION);
    $nombre = uniqid() . '.' . $ext;
    $url = "ficheros/" . $rfc . "/" . $nombre;
    $path = $ruta . "/" . $nombre;

    $sql = "UPDATE Fichero SET url = '" . $url . "' WHERE id_fichero = " . $id;
    $consulta = $conexion->query($sql);

    move_uploaded_file($_FILES['file']['tmp_name'], $path);

    $sql = "SELECT Fichero.*, (SELECT nombre FROM Documento WHERE id_documento = Fichero.id_documento) as documento
    FROM Fichero WHERE id_fichero = " . $id;
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