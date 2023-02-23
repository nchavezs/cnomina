<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
// $ano = $_POST["ano"];

$sql = "SELECT * FROM Historial WHERE RFC = '".$id."' ORDER BY elaboracion DESC";

$consulta = $conexion->query($sql);

if (mysqli_num_rows($consulta) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class="text-primary">
					<th class="text-left">Tipo</th>
					<th class="oculto text-left">Puesto</th>
					<th class="">Fecha</th>
					<th class="">Archivo</th>
					<th class="oculto">Descripción</th>
					<th class="">Formato</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_array($consulta)) {
		$puesto = " - "; 
		if($res["tipo"] == "baja"){
			$sql = "SELECT id_plaza FROM Baja WHERE RFC = '".$res["RFC"]."'";
			$query = $conexion->query($sql);
		}else if($res["tipo"] == "alta"){
			$sql = "SELECT id_plaza FROM Historial_Plaza WHERE RFC = '".$res["RFC"]."' ORDER BY fecha_inicio ASC LIMIT 1";
			$query = $conexion->query($sql);

			$sql = "SELECT nombre FROM Puesto WHERE id_puesto = (SELECT id_puesto FROM Empleado WHERE RFC = '".$res["RFC"]."')";
			$query1 = $conexion->query($sql);
			if($query1 && mysqli_num_rows($query1) > 0){
				$puesto = mysqli_fetch_row($query1);
				$puesto = $puesto[0];
			}
		}else if($res["tipo"] == "reingreso"){
			$sql = "SELECT id_plaza FROM Reingreso WHERE RFC = '".$res["RFC"]."' AND fecha = '".$res["fecha"]."' LIMIT 1";
			$query = $conexion->query($sql);
		}

		if($query && mysqli_num_rows($query) > 0){
			$plaza = mysqli_fetch_row($query);
			$plaza = $plaza[0];

			$sql = "SELECT nombre FROM Puesto WHERE id_puesto = (SELECT id_puesto FROM Plaza WHERE id_plaza = ".$plaza.")";
			$query = $conexion->query($sql);
			if($query && mysqli_num_rows($query) > 0){
				$puesto = mysqli_fetch_row($query);
				$puesto = $puesto[0];
			}
		}


		if($res["descripcion"] == null)
		$res["descripcion"] = "<span class='tipo'>SIN DESCRIPCION</span>";
        echo '<tr>
				<td class="text-left">'.$res["tipo"].'</td>
				<td class="oculto text-left">'.$puesto.'</td>
				<td>' . date("d/m/Y", strtotime($res["fecha"])) . '</td>
				<td class=""> <a class="material-icons btn1" onclick="archivo(' . $res[0] . ',\'' . $res["url"] . '\',\'' . $id . '\',\'Historial\',0)">attachment</a></td>
				<td class="oculto text-left">' . $res["descripcion"] . '</td>
				<td class=""> <i onclick="formato_historial('.$res["id_historial"].');" class="material-icons btn1">play_for_work</i></td>
			</tr>';
    }
    echo '</tbody>
			</table>
		</div>
	</div>
</div>';
} else {
    echo '<div class="table-responsive">
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
}

$conexion->close();
