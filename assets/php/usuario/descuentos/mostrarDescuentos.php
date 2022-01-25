<?php
include "../../conexion.php";
$conexion = conexion();
session_start();
$id = $_SESSION["usuario"];
$ano = $_POST["ano"];
$mes = $_POST["mes"];
setlocale(LC_ALL, "spanish");

$sql = "SELECT * FROM Descuento WHERE fechas LIKE '%" . $mes . "/" . $ano . "%' AND RFC = '" . $id . "'";
$consulta = mysqli_query($conexion, $sql);

if (mysqli_num_rows($consulta) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
					<th class="col-puesto">Dias</th>
					<th class="titulo">Fecha del</th>
					<th class="titulo">Fecha al</th>
					<th class="titulo">Archivo</th>
					<th class="titulo">Detalle</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_row($consulta)) {
        $dates = explode(",", $res[4]);
        $fechas = [];
        foreach ($dates as $date) {
            $fecha1 = explode("/", $date);
            $fecha2 = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];
            array_push($fechas, $fecha2);
        }
        $max = date_format(date_create(max($fechas)), "d/m/Y");
        $min = date_format(date_create(min($fechas)), "d/m/Y");
        echo '<tr>
				<td class="col-puesto">' . $res[2] . '</td>
				<td>' . $min . '</td>
				<td>' . $max . '</td>
				<td> <a class="material-icons btn1" id="' . $res[6] . '" onclick="archivo(this.id)">attachment</a></td>
				<td> <a class="material-icons btn1" id="' . $res[0] . '-" onclick="detalle_descuento(this.id)" >visibility</a></td>
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

mysqli_close($conexion);
