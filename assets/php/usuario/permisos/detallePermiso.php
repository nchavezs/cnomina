<?php
$id = $_POST['id'];
include "../../conexion.php";
$conexion = conexion();
setlocale(LC_ALL, "spanish");
$sql = "SELECT * FROM Permiso WHERE id_permiso = " . $id;
$consulta = $conexion->query($sql);
$permiso = mysqli_fetch_array($consulta);

if ($permiso["categoria"] == 0) {
    $tipo = "con";
} else {
    $tipo = "sin";
}

if ($permiso["descripcion"] == "") {
    $desc = "Sin descripción";
} else {
    $desc = $permiso["descripcion"];
}

if ($permiso["materno"] == 1) {
    $materno = "active";
} else {
    $materno = "";
}

$del = strftime('Del %d de %B de %Y', strtotime($permiso['del']));
$al = strftime('Al %d de %B de %Y', strtotime($permiso['al']));

$html = '<div class="p-2">
			<h4 class="negrita text-primary">Detalle de permiso</h4>
			
		</div>
		
		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body centrado">
						<div id="fecha" class="datepicker-here"></div>
					</div>
				</div>';


$html = $html . '</div>
					<div class="col-md-6">
						<div class="card" id="fecha-contenido">
							<div class="card-body text-left">
								<p class="card-category">Fecha de elaboración: <span>' . date("d/m/Y", strtotime($permiso["elaboracion"])) . '</span></p>
								<p class="card-category">Días de permiso: <span>' . $permiso["dias"] . '</span></p>
								
								<p class="card-category pt-2">' .$del. '</p>
								<p class="card-category pb-2">' .$al . '</p>
								
								<p class="card-category">Descripción:</p>
								<h5>' . $desc . '</h5>
							</div>
						</div>';
						if ($permiso["materno"] == 1) {
							$html = $html . '<div class="card">
												<div class="card-body centrado">
													<i class="material-icons text-success mr-3">task_alt</i>
													Permiso materno
												</div>
											</div>';
						}
						$html = $html . '</div>
				</div>';

$datos["html"] = $html;
$datos["del"] = date("d/m/Y", strtotime($permiso["del"]));
$datos["al"] = date("d/m/Y", strtotime($permiso["al"]));

$conexion->close();

echo json_encode($datos);

