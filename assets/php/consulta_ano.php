<?php
$sql = "SELECT * FROM Prenomina";
$consulta = $conexion->query($sql);

if(mysqli_num_rows($consulta) > 0){
    $sql = "SELECT MIN(YEAR(del)) AS min FROM Prenomina";
    $consulta = $conexion->query($sql);
    $res = mysqli_fetch_array($consulta);
    $min = $res["min"];

    $sql = "SELECT * FROM Prenomina WHERE estado = 0";
    $consulta = $conexion->query($sql);

    if(mysqli_num_rows($consulta) == 0){
        $max = date("Y");   
    }else{
        $sql = "SELECT MAX(YEAR(del)) AS max FROM Prenomina";
        $consulta = $conexion->query($sql);
        $res = mysqli_fetch_array($consulta);
        $max = $res["max"];
    }
}else{
    $min = date("Y");
    $max = $min;    
}
$prenomina = mysqli_fetch_array($consulta);

for ($i = $min; $i <= $max; $i++) {
    echo '<div class="col-xl-2 col-4">
            <div onclick="seleccionar_periodo(' . $i . ');" class="card periodo_elemento">
                <div class="card-body text-center">
                    <p>' . $i . '</p>
                </div>
            </div>
        </div>';
}