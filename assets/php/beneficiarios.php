<?php
include "conexion.php";
$conexion = conexion();
session_start();
$id = $_SESSION['usuario'];

$sql = "SELECT * FROM Beneficiario WHERE RFC = '" . $id."'";
$total = mysqli_num_rows($conexion->query($sql));

$comprobar_baja = "SELECT * FROM Usuario WHERE RFC = '" . $id."' and estado = 'baja'";
$baja = mysqli_num_rows(mysqli_query($conexion, $comprobar_baja));

if ($total == 0) {
    echo '<div class="chat-nuevo">';
				if($baja == 0){
					echo '<i id="chat-icono" class="material-icons">add</i>';
					echo '<p id="bene-nuevo">Agregar nuevo beneficiario</p>';
				}else{
					echo '<i id="chat-icono" class="material-icons">error_outline</i>';
					echo '<p>No se encontraron resultados</p>';
				}
			echo '</div>
		</div>';

    echo '<script>
			$(".bene-nuevo").html("Agrega tus beneficiarios");
		</script>';
} else {
	if($baja == 0){
    echo '<script>
		$(".bene-nuevo").html("<button class=\'btn-bene\'>Agregar nuevo beneficiario</button>");
		</script>';
	}
    echo '<div class="card-body table-responsive">
			<table class="table table-hover">
			<thead class="text-primary">
				<th class="oculto">#</th>
				<th>Nombre</th>
				<th class="oculto">Parentesco</th>
				<th>Archivo</th>';
				if($baja == 0){
					echo '<th>Editar</th>
					<th>Eliminar</th>';
				}
				echo '</thead>
				<tbody>';
    $c = 1;
    $sql2 = "SELECT * FROM Beneficiario WHERE RFC = '" . $id."'";
    $resultado = mysqli_query($conexion, $sql2);
    while ($res = mysqli_fetch_row($resultado)) {
        echo '<tr>
				<td class="oculto">' . $c . '</td>
				<td class="bene-nombre">' . mb_strtoupper($res[2]) . '</td>
				<td class="oculto">' . mb_strtoupper($res[3]) . '</td>
				<td> <a class="material-icons btn1" id="'.$res[5].'" onclick="archivo(this.id)">attachment</a></td>';
				if($baja == 0){
					echo '<td><a class="material-icons btn1 editar" name="' . $res[2] . '-' . $res[3] . '-' . $res[5] . '" id="' . $res[0] . '">edit</a></td>
				 	<td><a class="material-icons btn1 eliminar" id="' . $res[0] . '">delete</a></td>';
				}
			echo '</tr>';
        $c = $c + 1;
    }

    echo '</tbody>
		</table>
	</div>';
}
$conexion->close();
