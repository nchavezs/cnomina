 <?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$ano = date("Y");
$hoy = date("Y-m-d");

$sql = "SELECT fechaRelLab FROM Empleado WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
if ($consulta && mysqli_num_rows($consulta) > 0) {
    $usuario = mysqli_fetch_array($consulta);
    $fechaRelLab = date("Y-m-d", strtotime(str_replace("/", "-", $usuario["fechaRelLab"])));
    $fecha = $fechaRelLab;
    $ano_fecha_inicio = date('Y', strtotime($fechaRelLab));

    $sql = "SELECT fecha FROM Movimiento WHERE RFC = '" . $id . "' ORDER BY fecha DESC LIMIT 1";
    $consulta = $conexion->query($sql);

    if($ano_fecha_inicio < $ano){
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $movimiento = mysqli_fetch_array($consulta);
            $fecha = date("Y-m-d",strtotime($movimiento["fecha"]."+ 1 days"));
        }else{
            $fecha = date("Y-m-d",strtotime($hoy."- 45 days"));
            $ano_fecha = date('Y', strtotime($fecha));
            if($ano_fecha < $ano){
                $fecha = $ano."-01-01";
            }
        }
    }else{
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $movimiento = mysqli_fetch_array($consulta);
            $fecha = date("Y-m-d",strtotime($movimiento["fecha"]."+ 1 days"));
        }else{
            $fecha = date("Y-m-d",strtotime($hoy."- 45 days"));
            $ano_fecha = date('Y', strtotime($fecha));
            if($fecha < $fechaRelLab){
                $fecha = $fechaRelLab;
            }
        }
    }
}

echo $fecha;

mysqli_close($conexion);