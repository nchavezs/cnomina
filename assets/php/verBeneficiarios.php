<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];
$sql = "SELECT * FROM Beneficiario WHERE RFC = '" . $id."'";
$sql2 = "SELECT nombre FROM Usuario WHERE RFC = '" . $id."'";
$resultado = mysqli_query($conexion, $sql);
$resultado2 = mysqli_query($conexion, $sql2);
$nombre = mysqli_fetch_array($resultado2);

echo '<div class="card">
		<div class="card-header card-header-primary">
				<h4 class="card-title ">Lista de beneficiarios</h4>
				<p class="card-category">' . $nombre[0] . '</p>
		</div>
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

echo '<div class="btn btn-secondary btn-sm regresar " id="' . $id . '" onclick="ver(this.id, 1);"><i class="material-icons">arrow_back</i>Regresar</div>';

mysqli_close($conexion);
