 <?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT fecha FROM BAJA WHERE RFC = '".$id."' ORDER BY elaboracion DESC LIMIT 1";
$consulta = $conexion->query($sql);
$baja = mysqli_fetch_array($consulta);
$fecha = $baja["fecha"];

echo $fecha;
mysqli_close($conexion);