<?php
setlocale(LC_ALL, "spanish");
session_start();
include "conexion.php";
$conexion = conexion();
$ano = $_SESSION["ano"];
$id_periodo = $_SESSION["id_periodo"];

$sql = "SELECT * FROM Prenomina WHERE 
YEAR(del) = ".$ano." AND 
id_periodo = ".$id_periodo." 
ORDER BY id_prenomina DESC";

$consulta = $conexion->query($sql);
$total = mysqli_num_rows($consulta);

if ($total > 0) {
    echo '<div class="row">';
    while($prenomina = mysqli_fetch_array($consulta)){
        $nombre = strftime("%e de %B", strtotime($prenomina["del"]))." al ".strftime("%e de %B", strtotime($prenomina["al"]));
        $estado = "";
        if($prenomina["estado"] == 0){
            $estado = "activo";
        }else{
            $estado = "cerrado";
        }
        if($prenomina["url"] != null){
            $estado = $estado." generado";
        }
        echo '<div class="col-xl-3 col-6">
                <div class="prenomina">
                    <div class="card '.$estado.'">
                        <div class="card-body text-center">
                            <p class="negrita m-0">Periodo '.$total--.'</p>
                            <div class="prenomina_titulo negrita text-secondary mb-3">'.$nombre.'</div>';
                            if($prenomina["url"] == null){
                                echo '<div onclick="nueva_prenomina();" class="btn4"><i class="material-icons">save_as</i> Generar</div>';
                            }else{
                                echo '<div class="btn4"><i class="material-icons">file_download</i> <a href="assets/prenominas/'.$prenomina["url"].'" download>Descargar</a> </div>';
                                if($prenomina["estado"] == 0){
                                    echo '<div onclick="nueva_prenomina();" class="btn4 extra"><i class="material-icons">receipt_long</i> Generar</div>
                                    <div onclick="autorizar();" class="btn4 extra"><i class="material-icons">task_alt</i> Autorizar</div>
                                    <i class="prenomina_titulo extra text-muted pt-3 text-wrap">'.$prenomina["observacion"].'</i>';
                                }
                            }
                            
                        echo '</div>
                    </div>
                </div>
            </div> ';
    }
    echo '</div>';
}

$conexion->close();




