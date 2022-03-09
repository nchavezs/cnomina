<?php
	session_start();
	include("../../conexion.php");
   $conexion = conexion();
	$id = $_SESSION['usuario'];
   $ano = $_POST["ano"];
	$categoria = $_POST["categoria"];

	$sql = "SELECT * FROM Permiso WHERE YEAR(del) = ".$ano." AND categoria = ".$categoria." AND RFC = '".$id."'";
	$resultado = $conexion->query($sql);

	if($categoria == 0)
		$permiso = "con";
	else
		$permiso = "sin";

	if(($total = mysqli_num_rows($resultado)) > 0){
		 $html = '<div class="table-responsive">
		 			<table class="table">
						<thead class=" text-primary">
						 	<th class="oculto">Dias</th>
							<th class="titulo">Fecha del</th>
							<th class="titulo">Fecha al</th>
							<th class="titulo">Archivo</th>
							<th class="titulo">Detalle</th>
						</thead>
					<tbody>
				</div>';
			while($res = mysqli_fetch_array($resultado)){
				$html = $html.'<tr>
							 <td class="oculto">'.$res["dias"].'</td>
							 <td>'.date("d/m/Y",strtotime($res["del"])).'</td>
							 <td>'.date("d/m/Y",strtotime($res["al"])).'</td>
							 <td> <a class="material-icons btn1" onclick="archivo(\''.$res["url"].'\')">attachment</a></td>
							 <td> <a class="material-icons btn1" onclick="detalle('.$res[0].')" >visibility</a></td>
						</tr>';
			}
			$html = $html.'</tbody>
				</table>';
	}else{
		$html = '<div class="table-responsive">
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
	$datos["html"] = $html;
	$datos["total"] = $total;
	echo json_encode($datos);
	$conexion->close();
?>
