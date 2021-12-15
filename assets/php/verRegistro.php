<?php
include "conexion.php";

$conexion = conexion();

$consulta = "SELECT * FROM Archivo WHERE id_archivo = " . $_POST['id'];
$resultado = mysqli_query($conexion, $consulta);
if ($resultado) {
    $res = mysqli_fetch_row($resultado);

    if ($res[9] == null || $res[9] === "") {
        $res[9] = "-";
    }
    if ($res[12] == null || $res[12] === "") {
        $res[12] = "-";
    }
    if ($res[11] == null || $res[11] === "") {
        $res[11] = "-";
    }
    if ($res[10] == null || $res[10] === "") {
        $res[10] = "-";
    }
    if ($res[13] == null || $res[13] === "") {
        $res[13] = "-";
    }
    if ($res[8] == null || $res[8] === "") {
        $res[8] = "-";
    }
    if ($res[15] == null || $res[15] === "") {
        $res[15] = "-";
    }

    echo '<div class="modal-archivo">
            <div class="row">
               <div class="col-5">
                  <h5>No. de empleado:</h5>
                  <h5>Nombre:</h5>
                  <h5>Fecha de inicio:</h5>
                  <h5>CURP:</h5>
                  <h5>RFC:</h5>
                  <h5>Puesto:</h5>
                  <h5>Departamento:</h5>
                  <h5>Fecha de pago:</h5>
                  <h5>Ver archivo:</h5>
                  <h5>Fecha de carga:</h5>
                  <h5>Estado del empleado:</h5>
               </div>
               <div class="col-7">
                  <h5>' . str_pad($res[1], 5, '0', STR_PAD_LEFT) . '</h5>
                  <h5>' . $res[9] . '</h5>
                  <h5>' . $res[12] . '</h5>
                  <h5>' . $res[11] . '</h5>
                  <h5>' . $res[10] . '</h5>
                  <h5>' . $res[13] . '</h5>
                  <h5>' . $res[14] . '</h5>
                  <h5>' . $res[8] . '</h5>
                  <a target="_blank" href="' . $res[4] . '"><h5>' . $res[2] . ' </h5></a>
                  <h5>' . $res[15] . '</h5>';

    $sql = "SELECT estado FROM Usuario WHERE RFC = '" . $res[10]."'";
    $resultado2 = mysqli_query($conexion, $sql);
    $estado = mysqli_fetch_array($resultado2);
    if($estado){
        if ($estado[0] == null) 
        echo '<h5>-</h5>';
        else{
            if (strcmp($estado[0], "alta") == 0) {
                echo '<h5 class="alta">' . strtoupper($estado[0]) . '</h5>';
            } else {
                echo '<h5 class="baja">' . strtoupper($estado[0]) . '</h5>';
            }
        }
    }else{
        echo '<h5>-</h5>'; 
    }
    
    echo '</div>
      </div>
   </div>';

} else {
    echo 0;
}

mysqli_close($conexion);
