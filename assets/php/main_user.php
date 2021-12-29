<?php
session_start();
include "assets/php/conexion.php";
// --------------------------------------------------------------------------
$varUser = $_SESSION['usuario'];
$varCateg = $_SESSION['categoria'];
$varName = $_SESSION['nombre'];

if ($varUser == null || $varUser == '' || $varCateg == "admin") {
	header("location: /");
}

// --------------------------------------------------------------------------
include "./assets/php/municipio.php";