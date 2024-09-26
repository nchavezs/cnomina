<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$archivo = $_FILES['file']['name'] ?? null;
$datos["html"] = "";
$datos["success"] = false;
// ------------------------------------------------------------------------------------------------
if (isset($archivo) && $archivo) {
    $ruta = '../ficheros/' . $id;
    if (!file_exists($ruta)) {
        mkdir($ruta, 0777, true);
    }

    $ext = pathinfo($archivo, PATHINFO_EXTENSION);
    $url = time() . '.' . $ext;
    $path = $ruta . "/" . $url;

    $sql = "UPDATE Fichero SET url = '".$url."' WHERE id_fichero = " . $id ;
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





// PARA ACTUALIZAR DOC

// // Verificar si ya existe un fichero asociado
// $sql = "SELECT * FROM Fichero WHERE id_documento = " . $id_documento . " AND RFC = '" . $id . "'";
// $consulta = $conexion->query($sql);
// $total = mysqli_num_rows($consulta);

// if ($total > 0) {
//     // Si existe, eliminar el archivo anterior
//     $row = $consulta->fetch_assoc();
//     $archivo_anterior = $ruta . "/" . $row['url'];

//     if (file_exists($archivo_anterior)) {
//         unlink($archivo_anterior); // Eliminar el archivo del servidor
//     }

//     // Actualizar registro con el nuevo archivo
//     $sql = "UPDATE Fichero SET url = '" . $url . "' WHERE id_fichero = " . $row["id_fichero"];
//     $conexion->query($sql);
// } else {
//     // Insertar nuevo registro de fichero
//     $sql = "INSERT INTO Fichero(id_documento, RFC, url) VALUES(" . $id_documento . ", '" . $id . "', '" . $url . "');";
//     $conexion->query($sql);
// }
