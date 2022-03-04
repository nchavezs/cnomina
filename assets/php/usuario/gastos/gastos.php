<?php
session_start();
include "../../conexion.php";
$conexion = conexion();
$id = $_SESSION["usuario"];

$sql = "SELECT * FROM Gastos WHERE RFC = '" . $id."'";
$consulta = $conexion->query($sql);

// $ano = date("Y");
// $ano1 = 2018;
// $ano2 = 2019;
// $ano3 = 2020;
// $ano4 = 2021;

// if ($ano1 == $ano) {
//     $ano1 = 'selected="true"';
// } else {
//     $ano1 = '';
// }

// if ($ano2 == $ano) {
//     $ano2 = 'selected="true"';
// } else {
//     $ano2 = '';
// }

// if ($ano3 == $ano) {
//     $ano3 = 'selected="true"';
// } else {
//     $ano3 = '';
// }

// if ($ano4 == $ano) {
//     $ano4 = 'selected="true"';
// } else {
//     $ano4 = '';
// }

$mes = date("m");
$mes1 = 1;
$mes2 = 2;
$mes3 = 3;
$mes4 = 4;
$mes5 = 5;
$mes6 = 6;
$mes7 = 7;
$mes8 = 8;
$mes9 = 9;
$mes10 = 10;
$mes11 = 11;
$mes12 = 12;

if ($mes1 == $mes) {
    $mes1 = 'selected="true"';
} else {
    $mes1 = '';
}

if ($mes2 == $mes) {
    $mes2 = 'selected="true"';
} else {
    $mes2 = '';
}

if ($mes3 == $mes) {
    $mes3 = 'selected="true"';
} else {
    $mes3 = '';
}

if ($mes4 == $mes) {
    $mes4 = 'selected="true"';
} else {
    $mes4 = '';
}

if ($mes5 == $mes) {
    $mes5 = 'selected="true"';
} else {
    $mes5 = '';
}

if ($mes6 == $mes) {
    $mes6 = 'selected="true"';
} else {
    $mes6 = '';
}

if ($mes7 == $mes) {
    $mes7 = 'selected="true"';
} else {
    $mes7 = '';
}

if ($mes8 == $mes) {
    $mes8 = 'selected="true"';
} else {
    $mes8 = '';
}

if ($mes9 == $mes) {
    $mes9 = 'selected="true"';
} else {
    $mes9 = '';
}

if ($mes10 == $mes) {
    $mes10 = 'selected="true"';
} else {
    $mes10 = '';
}

if ($mes11 == $mes) {
    $mes11 = 'selected="true"';
} else {
    $mes11 = '';
}

if ($mes12 == $mes) {
    $mes12 = 'selected="true"';
} else {
    $mes12 = '';
}

echo '<div class="container-fluid">
        <select name="sources" id="ano" class="custom-select sources">';
        $ano = date("Y");
        for($i=2022;$i<=2025;$i++){
            if($ano == $i)
                $select_ano = "selected";
            else
                $select_ano = "";
            echo '<option '.$select_ano.' value="'.$i.'">'.$i.'</option>';
        }
        echo '</select>

		<select id="mes" class="custom-select sources">
			<option ' . $mes1 . ' value="1">Enero</option>
			<option ' . $mes2 . ' value="2">Febrero</option>
			<option ' . $mes3 . ' value="3">Marzo</option>
			<option ' . $mes4 . ' value="4">Abril</option>
			<option ' . $mes5 . ' value="5">Mayo</option>
			<option ' . $mes6 . ' value="6">Junio</option>
			<option ' . $mes7 . ' value="7">Julio</option>
			<option ' . $mes8 . ' value="8">Agosto</option>
			<option ' . $mes9 . ' value="9">Septiembre</option>
			<option ' . $mes10 . ' value="10">Octubre</option>
			<option ' . $mes11 . ' value="11">Noviembre</option>
			<option ' . $mes12 . ' value="12">Diciembre</option>
		</select>
	</div>
	<div class="card card-profile ">
		<div class="card-header card-header-primary">
			<h4 class="card-title" id="titulo-gastos">GASTOS MÉDICOS</h4>
			<p class="card-category" id="detalle-gastos"></p>
		</div>
		<div class="card-body">
			<div class="caja-gastos"></div>
		</div>
	</div>';

$conexion->close();