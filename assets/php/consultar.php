<?php
session_start();
include "conexion.php";
include "rol.php";
$conexion = conexion();
setlocale(LC_ALL, "spanish");
// $ano = $_POST["ano"];
$ano = $_SESSION["ano"];
$id_periodo = $_POST["id_periodo"];

if (in_array(7, rol())) {
    $bandera_eliminar = true;
} else {
    $bandera_eliminar = false;
}

if (in_array(9, rol())) {
    $bandera_ver = true;
} else {
    $bandera_ver = false;
}

$sql = "SELECT * FROM Archivo WHERE
   YEAR(del) = " . $ano . " AND
   id_periodo = " . $id_periodo . "
   ORDER BY del DESC";
$resultado = $conexion->query($sql);
if (mysqli_num_rows($resultado) == 0) {
    echo '{"data":[]}';
} else {
    while ($res = mysqli_fetch_array($resultado)) {
        if ($bandera_eliminar) {
            $eliminar = "eliminar($res[0])";
        } else {
            $eliminar = "bloqueo(event)";
        }

        if ($bandera_ver) {
            $ver = "ver($res[0])";
        } else {
            $ver = "bloqueo(event)";
        }
        
        $arreglo[] = [
            "nombre" => $res["nombre"],
            "del" => strftime("%d %B", strtotime($res["del"])),
            "al" => strftime("%d %B", strtotime($res["al"])),
            "eliminar" => '<i class="material-icons btn1-danger" onClick="' . $eliminar . '" >delete</i>',
            "ver" => '<i class="material-icons btn1" onClick="' . $ver . '">assignment</i>',
            "dias_pago" => $res["dias_pago"]
        ];
    }

    $datos = ["data" => $arreglo];

    echo json_encode($datos);
}

$conexion->close();
