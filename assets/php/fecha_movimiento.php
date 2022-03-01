 <?php
 session_start();
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$id_prenomina = $_SESSION["id_prenomina"];

$sql = "SELECT * FROM Prenomina WHERE id_prenomina = " . $id_prenomina;
$consulta = $conexion->query($sql);
$prenomina = mysqli_fetch_array($consulta);
$del= date("Y-m-d",strtotime($prenomina["del"]."+ 1 days"));

$sql = "SELECT fecha FROM Historial WHERE RFC = '".$id."' AND id_prenomina = ".$id_prenomina." ORDER BY fecha DESC LIMIT 1";
$consulta = $conexion->query($sql);

if($consulta && mysqli_num_rows($consulta) > 0){
    $historial = mysqli_fetch_array($consulta);
    $del= date("Y-m-d",strtotime($historial["fecha"]."+ 2 days"));
}

$sql = "SELECT fecha FROM Movimiento WHERE RFC = '" . $id . "' AND id_prenomina = ".$id_prenomina." ORDER BY fecha DESC LIMIT 1";
$consulta = $conexion->query($sql);

if($consulta && mysqli_num_rows($consulta) > 0){
    $movimiento = mysqli_fetch_array($consulta);
    $fecha_movimiento= date("Y-m-d",strtotime($movimiento["fecha"]."+ 2 days"));
    if($fecha_movimiento > $del){
        $del = $fecha_movimiento;
    }
}



$datos["del"] = $del;
$datos["al"] = date("Y-m-d",strtotime($prenomina["al"]."+ 1 days"));
echo json_encode($datos);
mysqli_close($conexion);