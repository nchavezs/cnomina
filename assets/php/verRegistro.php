<?php
include "conexion.php";

$conexion = conexion();

$consulta = "SELECT * FROM Archivo WHERE id_archivo = " . $_POST['id'];
$resultado = mysqli_query($conexion, $consulta);
if ($resultado) {
    $res = mysqli_fetch_array($resultado);

    echo '<div class="modal-archivo">
            <div class="row">
               <div class="col-5 text-right font-weight-bold">
                  <p>Nombre:</p>
                  <p>RFC:</p>
                  <p>Puesto:</p>
                  <p>Departamento:</p>
                  <p>Fecha de pago:</p>
                  <p>Dias de pago:</p>
                  <p>Ver archivo:</p>
                  <p>Fecha de carga:</p>
               </div>
               <div class="col-7">
                  <p>' . $res["nombre"] . '</p>
                  <p>' . $res["RFC"] . '</p>
                  <p>' . $res["puesto"] . '</p>
                  <p>' . $res["departamento"] . '</p>
                  <p>' . date("d/m/Y", strtotime($res["fecha_pago"] )). '</p>
                  <p>' . $res["dias_pago"] . '</p>
                  <p><a class="tipo" target="_blank" href="' . $res["url"] . '">' . $res["url"] . ' </a></p>
                  <p>' .date("d/m/Y h:i A", strtotime($res["elaboracion"] )) . '</p>';
    
    echo '</div>
      </div>
   </div>';

} else {
    echo 0;
}

mysqli_close($conexion);
