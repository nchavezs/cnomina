<?php
   include("conexion.php");
   $conexion = conexion();

   $sql = "SELECT nombre FROM Usuario WHERE RFC = '".$_POST['id']."'";
   $resultado = mysqli_query($conexion, $sql);
	$empleado = mysqli_fetch_array($resultado);

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
	 

	echo '<div class="row">
				<div class="col-3">
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
			</div>

			<div class="card card-profile">
				<div class="card-header card-header-primary">
					 <h4 class="card-title ">Lista de nóminas</h4>
					 <p class="card-category">'.$empleado[0].'</p>
				</div>
				<div class="card-body">
					<div class="caja-recibos"></div>
				</div>
			</div>';

	echo '<div class="btn btn-secondary btn-sm regresar " id="'.$_POST['id'].'" onclick="ver(this.id, 1);"><i class="material-icons">arrow_back</i>Regresar</div>';
      
   mysqli_close($conexion);
?>
