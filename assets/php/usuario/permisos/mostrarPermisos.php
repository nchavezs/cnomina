<?php
	session_start();
	include("../../conexion.php");
   $conexion = conexion();
	$id = $_SESSION['usuario'];
   $ano = $_POST["ano"];
	$categoria = $_POST["categoria"];

	$sql = "SELECT * FROM Permiso WHERE YEAR(del) = ".$ano." AND categoria = ".$categoria." AND RFC = '".$id."'";
	$resultado = mysqli_query($conexion, $sql);

	if($categoria == 0)
		$permiso = "con";
	else
		$permiso = "sin";

	if(($total = mysqli_num_rows($resultado)) > 0){
		 $datos["html"] = '<div class="table-responsive">
		 			<table class="table">
						<thead class=" text-primary">
						 	<th class="col-puesto">Dias</th>
							<th class="titulo">Fecha del</th>
							<th class="titulo">Fecha al</th>
							<th class="titulo">Archivo</th>
							<th class="titulo">Detalle</th>
						</thead>
					<tbody>
				</div>';
			while($res = mysqli_fetch_row($resultado)){
				$datos["html"] = $datos["html"].'<tr>
							 <td class="col-puesto">'.$res[3].'</td>
							 <td>'.date("d/m/Y",strtotime($res[4])).'</td>
							 <td>'.date("d/m/Y",strtotime($res[5])).'</td>
							 <td> <a class="material-icons btn1" id="'.$res[8].'" onclick="archivo(this.id)">attachment</a></td>
							 <td> <a class="material-icons btn1" id="'.$res[0].'-" onclick="detalle(this.id)" >visibility</a></td>
						</tr>';
			}
			$datos["html"] = $datos["html"].'</tbody>
				</table>';
	}else{
		$datos["html"] = '<div class="table-responsive">
				  <table class="table">
						<thead class=" text-primary">
						</thead>
						<tbody>
							<div class="chat-nuevo">
								<i id="chat-icono" class="material-icons">error_outline</i>
								<p>Sin elementos</p>
							</div>
						</tbody>
					</table>
				</div>';
		$total = 0;
	}

	$datos["total"] = $total;
	echo json_encode($datos);
	mysqli_close($conexion);
?>
