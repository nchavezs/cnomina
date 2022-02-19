<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];


$sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $id."'";
$resultado = $conexion->query($sql);
$usuario = mysqli_fetch_array($resultado);

$sql = "SELECT * FROM Beneficiario WHERE RFC = '" . $id."'";
$resultado = $conexion->query($sql);

echo '<div class="p-2">
				<h4 class="font-weight-bold text-primary">Lista de beneficiarios</h4>
				<small class="text-muted">Beneficiarios de '.$usuario["nombre"].'.</small>
			</div>
	<div class="card">
		<div class="card-body">
				<div class="table-responsive">
					<table class="table">';
if (mysqli_num_rows($resultado) == 0) {
    echo '<tbody>
			<div class="chat-nuevo">
				<i id="chat-icono" class="material-icons">error_outline</i>
				<p>Sin elementos</p>
			</div>';
} else {
    echo '<thead class=" text-primary">
				<th>#</th>
				<th>Nombre</th>
				<th>Parentesco</th>
				<th>Archivo</th>
		</thead>
		<tbody>';
    $i = 0;
    while ($res = mysqli_fetch_array($resultado)) {
        $i = $i + 1;
        echo '<tr>
				<td>' . $i . '</td>
				<td>' . ucwords($res[2]) . '</td>
				<td>' . ucfirst(mb_strtolower($res[3])) . '</td>
				<td> <a class="material-icons btn1" onclick="archivo2(\'' . $res[5] . '\',\'' . $res[1] . '\')">attachment</a></td>
			</tr>';
    }
}

echo '</tbody>
			</table>
		</div>
	</div>
</div>';

mysqli_close($conexion);
