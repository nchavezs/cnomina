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
               <div class="col-5 text-right font-weight-bold">
                  <p># Empleado:</p>
                  <p>Nombre:</p>
                  <p>Fecha de inicio:</p>
                  <p>CURP:</p>
                  <p>RFC:</p>
                  <p>Puesto:</p>
                  <p>Departamento:</p>
                  <p>Fecha de pago:</p>
                  <p>Ver archivo:</p>
                  <p>Fecha de carga:</p>
                  <p>Estado:</p>
               </div>
               <div class="col-7">
                  <p>' . str_pad($res[1], 5, '0', STR_PAD_LEFT) . '</p>
                  <p>' . $res[9] . '</p>
                  <p>' . $res[12] . '</p>
                  <p>' . $res[11] . '</p>
                  <p>' . $res[10] . '</p>
                  <p>' . $res[13] . '</p>
                  <p>' . $res[14] . '</p>
                  <p>' . $res[8] . '</p>
                  <p><a class="tipo" target="_blank" href="' . $res[4] . '">' . $res[2] . ' </a></p>
                  <p>' . $res[15] . '</p>';

    $sql = "SELECT estado FROM Usuario WHERE RFC = '" . $res[10]."'";
    $resultado2 = mysqli_query($conexion, $sql);
    $estado = mysqli_fetch_array($resultado2);
    if($estado){
        if ($estado[0] == null) 
        echo '<p>-</p>';
        else{
            if (strcmp($estado[0], "alta") == 0) {
                echo '<p><span class="alta">' . strtoupper($estado[0]) . '</span></p>';
            } else {
                echo '<p><span class="baja">' . strtoupper($estado[0]) . '</span></p>';
            }
        }
    }else{
        echo '<p>-</p>'; 
    }
    
    echo '</div>
      </div>
   </div>';

} else {
    echo 0;
}

mysqli_close($conexion);
