<?php
session_start();
include "conexion.php";
$conexion = conexion();

$id = $_SESSION['usuario'];
$ano =$_POST['ano'];
$mes =$_POST['mes'];

$x = 0;

for ($i = 1; $i <= 12; $i++) {
    if ($i == 1) {
        $m = 'ENERO';
    }

    if ($i == 2) {
        $m = 'FEBRERO';
    }

    if ($i == 3) {
        $m = 'MARZO';
    }

    if ($i == 4) {
        $m = 'ABRIL';
    }

    if ($i == 5) {
        $m = 'MAYO';
    }

    if ($i == 6) {
        $m = 'JUNIO';
    }

    if ($i == 7) {
        $m = 'JULIO';
    }

    if ($i == 8) {
        $m = 'AGOSTO';
    }

    if ($i == 9) {
        $m = 'SEPTIEMBRE';
    }

    if ($i == 10) {
        $m = 'OCTUBRE';
    }

    if ($i == 11) {
        $m = 'NOVIEMBRE';
    }

    if ($i == 12) {
        $m = 'DICIEMBRE';
    }

    $sql = "SELECT * FROM Archivo WHERE mes = " . $i . " AND ano = " . $ano . " AND mes = " . $mes . " AND RFC = '". $id."'";
    $resultado = mysqli_query($conexion, $sql);
    if ($res = mysqli_fetch_row($resultado)) {
        echo '<div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title ">' . $m . '</h4>
                    <p class="card-category"> Nóminas para el mes de ' . strtolower($m) . '</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table centrado">
                            <thead class=" text-primary">
                                <th class="titulo">Quincena</th>
                                                <th class="titulo">Fecha pago</th>
                                <th class="col-puesto">Puesto</th>
                                                <th class="col-depa">Departamento</th>
                                <th class="titulo">Ver</th>
                                <th class="titulo">Descargar</th>
                            </thead>
                            <tbody>';
        $sql = "SELECT * FROM Archivo WHERE mes = " . $i . " AND ano = " . $ano . " AND dia <= 15" . " AND RFC = '". $id."'";
        $resultado = mysqli_query($conexion, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $x = 1;
            while($res = mysqli_fetch_array($resultado)){
                if($res['dia'] == 0)
                    $primera = "Extraordinario";
                else
                    $primera = "Primera";
            echo '<tr>
                    <td>'.$primera.'</td>
                    <td>' . $res[8] . '</td>
                    <td class="col-puesto"> ' . $res[13] . '</td>
                    <td class="col-depa"> ' . $res[14] . '</td>
                    <td> <a class="material-icons btn1" target="_blank" href="' . $res[4] . '">visibility</a></td>
                    <td> <a class="material-icons btn1" href="' . $res[4] . '" download>cloud_download</a></td>
                </tr>';
            }
        }

        $sql = "SELECT * FROM Archivo WHERE mes = " . $i . " AND ano = " . $ano . " AND dia > 15 AND RFC = '" . $id."'";
        $resultado = mysqli_query($conexion, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $x = 1;
            while($res = mysqli_fetch_array($resultado)){
                if($res['dia'] == 33)
                    $segunda = "Extraordinario";
                else
                    $segunda = "Segunda";
            echo '<tr>
                    <td> '.$segunda.'</td>
                    <td>' . $res[8] . '</td>
                    <td class="col-puesto"> ' . $res[13] . '</td>
                            <td class="col-depa"> ' . $res[14] . '</td>
                    <td> <a class="material-icons btn1" target="_blank" href="' . $res[4] . '">visibility</a></td>
                    <td> <a class="material-icons btn1" href="' . $res[4] . '" download>cloud_download</a></td>
                </tr>';
            }
        }
        echo '</tbody>
                    </table>
                </div>
            </div>
        </div>';
    }
}
if ($x == 0) {
    echo '<div class="vacia">
            <i class="material-icons btn2">sms_failed</i>
            <h1>Sin archivos</h1>
        </div>';
}

mysqli_close($conexion);
