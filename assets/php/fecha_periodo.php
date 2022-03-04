 <?php
session_start();
include "conexion.php";
$conexion = conexion();
$id_prenomina = $_SESSION["id_prenomina"];

$sql = "SELECT * FROM Prenomina WHERE id_prenomina = " . $id_prenomina;
$consulta = $conexion->query($sql);
$prenomina = mysqli_fetch_array($consulta);

$datos["del"] = date("Y-m-d",strtotime($prenomina["del"]."+ 1 days"));
$datos["al"] = date("Y-m-d",strtotime($prenomina["al"]."+ 1 days"));

$conexion->close();

echo json_encode($datos);