<?php
// include "assets/php/conexion.php";
// $conexion = conexion();

// $sql = "SELECT * FROM Fichero";
// $consulta = $conexion->query($sql);

// while($row = mysqli_fetch_array($consulta)){
//     $url = $row["url"];
//     $rfc = $row["RFC"];
//     $nombre_archivo = basename($url);
//     $path_anterior = "assets/expediente/$nombre_archivo";

//     $ruta = "assets/ficheros/$rfc";
//     if (!file_exists($ruta)) {
//         mkdir($ruta, 0777, true);
//     }
//     copy($path_anterior, "assets/$url");
// }

// $conexion->close();