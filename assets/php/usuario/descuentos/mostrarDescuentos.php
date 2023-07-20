<?php
include "../../conexion.php";
$conexion = conexion();
session_start();
$id = $_SESSION["usuario"];
$ano = $_POST["ano"];
$mes = $_POST["mes"];
setlocale(LC_ALL, "spanish");

$sql = "SELECT * FROM Descuento WHERE fechas LIKE '%" . $mes . "/" . $ano . "%' AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);

if (mysqli_num_rows($consulta) > 0) {
    echo '<div class="table-responsive">
			<table class="table">
				<thead class=" text-primary">
					<th class="oculto">Dias</th>
					<th >Motivo</th>
					<th >Archivo</th>
					<th >Detalle</th>
				</thead>
				<tbody>';
    while ($res = mysqli_fetch_array($consulta)) {
        $dates = explode(",", $res["fechas"]);
        $fechas = [];
        foreach ($dates as $date) {
            $fecha1 = explode("/", $date);
            $fecha2 = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];
            array_push($fechas, $fecha2);
        }
        $max = date_format(date_create(max($fechas)), "d/m/Y");
        $min = date_format(date_create(min($fechas)), "d/m/Y");
        echo '<tr>
				<td class="oculto">' . $res["dias"] . '</td>
				<td>' . $res["motivo"] . '</td>
				<td> <a class="material-icons btn1" onclick="show_archivo(\''.$res["url"].'\')">attachment</a></td>
				<td> <a class="material-icons btn1" onclick="detalle_descuento('.$res["id_descuento"].')" >visibility</a></td>
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
