<?php
	session_start();
   include("../../conexion.php");
   $conexion = conexion();
	$id = $_SESSION["usuario"];

	// $ano = date("Y");
	// $ano1 = 2018;
	// $ano2 = 2019;
	// $ano3 = 2020;
	// $ano4 = 2021;

	// if($ano1 == $ano)
	// 	$ano1 = 'selected="true"';
	// else
	// 	$ano1 = '';
	// if($ano2 == $ano)
	// 	$ano2 = 'selected="true"';
	// else
	// 	$ano2 = '';
	// if($ano3 == $ano)
	// 	$ano3 = 'selected="true"';
	// else
	// 	$ano3 = '';
	// if($ano4 == $ano)
	// 	$ano4 = 'selected="true"';
	// else
	// 	$ano4 = '';

	 
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
			</div>
			<div class="card card-profile">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">VACACIONES</h4>
					<p id="sub_vaca" class="card-category"></p>
				</div>
				<div class="card-body">
					<div class="caja-vacaciones"></div>
				</div>
			</div>';
		
?>


