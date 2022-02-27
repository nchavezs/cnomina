<?php
include "conexion.php";
$conexion = conexion();
session_start();
// $id_prenomina = $_SESSION["id_prenomina"];
$id_periodo = $_SESSION["id_periodo"];
$ano = date("Y");

$sql = "SELECT *,
(SELECT dias FROM Periodo WHERE id_periodo = Prenomina.id_periodo) AS dias 
FROM Prenomina WHERE 
YEAR(del) = ".$ano." AND 
id_periodo = ".$id_periodo." 
ORDER BY id_prenomina DESC LIMIT 1";

$consulta = $conexion->query($sql);
$prenomina = mysqli_fetch_array($consulta);

$sql = "UPDATE Prenomina SET estado = 1 WHERE id_prenomina = " . $prenomina["id_prenomina"];
$conexion->query($sql);

$del = date("Y-m-d", strtotime($prenomina["al"] . "+ 1 days"));
$al = date("Y-m-d", strtotime($prenomina["al"]  . "+ " . ($prenomina["dias"] - 1) . " days"));

$ano_del = explode("-", $del);
$ano_del = array_shift($ano_del);
if ($ano_del == $ano) {
    
    $ano_al = explode("-", $al);
    $ano_al = array_shift($ano_al);
    if ($ano_al != $ano) {
        $al = $ano . "-12-31";
    }

    $sql = "INSERT INTO Prenomina(del, al, id_periodo) VALUES(
        '" . $del . "',
        '" . $al . "',
        " . $id_periodo . "
    )";

    if ($conexion->query($sql)) {
        $_SESSION["id_prenomina"] = mysqli_insert_id($conexion);

        echo 1;
    } else {
        echo 0;
    }
} else {
    echo 1;
}

mysqli_close($conexion);
