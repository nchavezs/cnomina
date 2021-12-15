 <?php
include "conexion.php";
$conexion = conexion();
$id = explode("-",$_POST['id']);
$id = $id[0];

$sql = "SELECT fechaRelLab FROM Usuario WHERE RFC = '".$id."'";
$consulta = mysqli_query($conexion, $sql);
$fecha = mysqli_fetch_row($consulta);

$fecha = explode("/",$fecha[0]);
if(sizeof($fecha) == 3){
    $datos["dia"] = $fecha[0];
    $datos["mes"] = $fecha[1] - 1;
    $datos["ano"] = $fecha[2];
}else{
    date_default_timezone_set('America/Mexico_City');
	setlocale(LC_TIME, 'es_CO.UTF-8');
    $hoy = date('d/m/Y');
    $fecha = explode("/",$hoy);
    $datos["dia"] = $fecha[0];
    $datos["mes"] = $fecha[1] - 1;
    $datos["ano"] = $fecha[2];
}


mysqli_close($conexion);
echo json_encode($datos);