<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$ano = $_POST["ano"];
$categoria = $_POST["categoria"];

$sql = "SELECT * FROM Permiso WHERE YEAR(del) = " . $ano . " AND categoria = " . $categoria . " AND RFC = '" . $id . "'";
$resultado = mysqli_query($conexion, $sql);

if ($categoria == 0) {
    $permiso = "con";
} else {
    $permiso = "sin";
}

if (($total = mysqli_num_rows($resultado)) > 0) {
    echo '<div class="card">
			<div class="card-header card-header-primary">
					<h4 class="card-title ">Permisos ' . $permiso . ' goce de sueldo</h4>
					<p class="card-category"> Total de permisos: ' . $total . ' </p>
			</div>
			<div class="card-body">
					<div class="table-responsive">
						<table class="table">
							<thead class=" text-primary">
									<th class="titulo">Fecha del</th>
									<th class="titulo">Fecha al</th>
									<th class="col-puesto">Dias</th>
									<th class="col-puesto">Archivo</th>
									<th class="titulo">Detalle</th>
									<th class="titulo">Eliminar</th>
							</thead>
							<tbody>';
    while ($res = mysqli_fetch_row($resultado)) {
        echo '<tr>
				<td>' . date("d/m/Y", strtotime($res[4])) . '</td>
				<td>' . date("d/m/Y", strtotime($res[5])) . '</td>
				<td class="col-puesto">' . $res[3] . '</td>
				<td class="col-puesto"> <a class="material-icons btn1" onclick="archivo(' . $res[0] . ',\'' . $res[8] . '\',\'' . $res[1] . '\',\'Permiso\',0)">attachment</a></td>
				<td> <a class="material-icons btn1" id="' . $res[0] . '-" onclick="detalle(this.id)" >visibility</a></td>
				<td> <a class="material-icons btn1" id="' . $res[0] . '" onclick="borrar(this.id)">delete</a></td>
		</tr>';
    }
    echo '</tbody>
			</table>
		</div>
	</div>
</div>';
} else {
    echo '<div class="card">
			<div class="card-header card-header-primary">
					<h4 class="card-title ">Permisos ' . $permiso . ' goce de sueldo</h4>
					<p class="card-category"> Total de permisos: 0 </p>
			</div>
			<div class="card-body">
					<div class="table-responsive">
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
					</div>
				</div>
			</div>';
}

mysqli_close($conexion);
