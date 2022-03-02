<?php
session_start();
setlocale(LC_ALL, "spanish");
include "assets/php/conexion.php";
// --------------------------------------------------------------------------
$varUser = $_SESSION['usuario'];
$varCateg = $_SESSION['categoria'];
$varName = $_SESSION['nombre'];
$varEmail = $_SESSION['email'];
$varFoto = $_SESSION['foto'];

if ($varUser == null || $varUser == '' || $varCateg == "user") {
    header("location: /");
}

// --------------------------------------------------------------------------
include "assets/php/rol.php";
include "assets/php/municipio.php";

function nombre_periodo()
{
    $conexion = conexion();
    $ano = $_SESSION["ano"];
    $id_periodo = $_SESSION["id_periodo"];
    $id_prenomina = $_SESSION["id_prenomina"];

    $sql = "SELECT * FROM Prenomina WHERE id_prenomina = " . $id_prenomina;
    $consulta = $conexion->query($sql);
    $prenomina = mysqli_fetch_array($consulta);
    $nombre_periodo = strftime("del %e de %B", strtotime($prenomina["del"])) .
    strftime(" al %e de %B", strtotime($prenomina["al"])) .
        " del " . $ano;

    $sql = "SELECT * FROM Prenomina WHERE id_periodo = " . $id_periodo . " AND YEAR(del) = " . $ano . " AND id_prenomina <= " . $prenomina["id_prenomina"];
    $consulta = $conexion->query($sql);
    $numero_prenomina = mysqli_num_rows($consulta);
    $conexion->close();

    if ($prenomina["estado"] == 1) {
        return '<a class="nav-link cambiar_periodo cerrado" href="./periodo">' . "Periodo " . $numero_prenomina . ", " . $nombre_periodo . '</a>';
    } else {
        return '<a class="nav-link cambiar_periodo" href="./periodo">' . "Periodo " . $numero_prenomina . ", " . $nombre_periodo . '</a>';
    }

}
