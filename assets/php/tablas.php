<?php
session_start();
include "conexion.php";
$conexion = conexion();
setlocale(LC_ALL, "spanish");
$id = $_SESSION['usuario'];
$ano = $_POST['ano'];
$mes = $_POST['mes'];

$sql = "SELECT
    Archivo.*,
    (SELECT nombre FROM periodo WHERE id_periodo = Archivo.id_periodo) AS periodo
    FROM Archivo WHERE
    YEAR(del) = " . $ano . " AND
    MONTH(del) = " . $mes . " AND
    RFC = '" . $id . "'";

$date = DateTime::createFromFormat('!m', $mes);
$m = strftime('%B', $date->getTimestamp());

$consulta = $conexion->query($sql);
if ($consulta && mysqli_num_rows($consulta) > 0) {
    echo '<div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title ">' . mb_strtoupper($m) . '</h4>
                    <p class="card-category"> Nóminas para el mes de ' . $m . '</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead class=" text-primary">
                                <th>Periodo</th>
                                <th class="oculto">Días de pago</th>
                                <th class="oculto">Puesto</th>
                                <th class="oculto">Departamento</th>
                                <th>Descargar</th>
                            </thead>
                            <tbody>';

    while ($archivo = mysqli_fetch_array($consulta)) {
        echo '<tr>
                    <td>' . $archivo["periodo"] . '</td>
                    <td class="oculto">' . $archivo["dias_pago"] . '</td>
                    <td class="oculto">' . $archivo["puesto"] . '</td>
                    <td class="oculto">' . $archivo["departamento"] . '</td>
                    <td> <a class="material-icons btn1" target="_blank" href="assets/nominas/' . $archivo["url"] . '">cloud_download</a></td>
                </tr>';
    }

    echo '</tbody>
            </table>
        </div>
    </div>
</div>';
} else {
    echo '<div class="vacia">
    <i class="material-icons btn2">sms_failed</i>
    <h1>Sin archivos</h1>
</div>';
}

$conexion->close();
