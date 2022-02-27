<?php
$id_prenomina = $_SESSION['id_prenomina'];

if ($id_prenomina == null) { 
	header("location: ./periodo");
}