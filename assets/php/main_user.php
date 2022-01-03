<?php
session_start();
include "assets/php/conexion.php";
// --------------------------------------------------------------------------
$varUser = $_SESSION['usuario'];
$varCateg = $_SESSION['categoria'];
$varName = $_SESSION['nombre'];
$varEmail = $_SESSION['email'];
$varFoto = $_SESSION['foto'];

if ($varUser == null || $varUser == '' || $varCateg == "admin") {
	header("location: /");
}

// --------------------------------------------------------------------------
include "./assets/php/municipio.php";