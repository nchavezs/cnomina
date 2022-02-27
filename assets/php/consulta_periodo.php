<?php
include "conexion.php";
setlocale(LC_ALL, "spanish");
session_start();
$ano = $_POST["ano"];
$id_periodo = $_POST["id_periodo"];
$conexion = conexion();

$_SESSION["ano"] = $ano;
$_SESSION["id_periodo"] = $id_periodo;

$sql = "SELECT * FROM Periodo WHERE id_periodo =" . $id_periodo;
$consulta = $conexion->query($sql);
$periodo = mysqli_fetch_array($consulta);

$sql = "SELECT * FROM Prenomina WHERE id_periodo = " . $id_periodo . " AND YEAR(del) = " . $ano." ORDER BY id_prenomina DESC";
$consulta = $conexion->query($sql);

if ($consulta && mysqli_num_rows($consulta) > 0) {
    while ($prenomina = mysqli_fetch_array($consulta)) {

        $nombre = strftime("%e de %B", strtotime($prenomina["del"]))." al ".strftime("%e de %B", strtotime($prenomina["al"]));
        if($prenomina["estado"] == 0){
            $estado = "<div class='btn btn-success btn3'>en curso</div>";
        }else{
            $estado = "<div class='btn btn-danger btn3'>cerrado</div>";
        }
        echo '<div class="col-xl-2 col-6">
                <div onclick="finalizar(' . $prenomina[0] . ');" class="card periodo_elemento">
                    <div class="card-body text-center">
                        <p>' . $nombre . '</p>
                        '.$estado.'
                    </div>
                </div>
            </div>';
    }
} else {
    $del = $ano . "-01-01";
    $al = date("Y-m-d", strtotime($del . "+ " . ($periodo["dias"] - 1). " days"));

    $sql = "INSERT INTO Prenomina(del, al, id_periodo) VALUES(
        '" . $del . "',
        '" . $al . "',
        " . $id_periodo . "
    )";

    $conexion->query($sql);
    $id = mysqli_insert_id($conexion);
    $nombre = strftime("%e de %B", strtotime($del))." al ".strftime("%e de %B", strtotime($al));

    echo '<div class="col-xl-3 col-4">
            <div onclick="finalizar(' . $id . ');" class="card periodo_elemento">
                <div class="card-body text-center">
                    <p>' .$nombre.'</p>
                    <div class="btn btn-success btn3">en curso</div>
                </div>
            </div>
        </div>';
}

$conexion->close();
