<?php
session_start();
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