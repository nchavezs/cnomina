 <?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT fecha FROM Historial WHERE RFC = '".$id."' ORDER BY fecha DESC LIMIT 1";
$consulta = $conexion->query($sql);
$historial = mysqli_fetch_array($consulta);
$fecha = date("Y-m-d",strtotime($historial["fecha"]."+ 1 days"));

echo $fecha;
mysqli_close($conexion);