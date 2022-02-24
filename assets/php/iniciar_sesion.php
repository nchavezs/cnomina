<?php
session_start();
$id_prenomina = $_POST["id_prenomina"];
$_SESSION["id_prenomina"] = $id_prenomina;
echo "registrar";