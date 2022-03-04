<?php
session_start();
// $id_prenomina = $_SESSION["id_prenomina"];
$id_periodo = $_SESSION["id_periodo"];
$ano = date("Y");


setlocale(LC_ALL, "spanish");
include "conexion.php";
$conexion = conexion();

// $hoy = date("d/m/Y");

$sql= "SELECT *, 
(SELECT nombre FROM Periodo WHERE id_periodo = Prenomina.id_periodo) AS periodo 
FROM Prenomina WHERE 
YEAR(del) = ".$ano." AND 
id_periodo = ".$id_periodo." 
ORDER BY id_prenomina DESC LIMIT 1";

$consulta = $conexion->query($sql);
$prenomina = mysqli_fetch_array($consulta);

$del = date("d/m/Y", strtotime($prenomina["del"]));
$al = date("d/m/Y", strtotime($prenomina["al"]));

echo '<div class="formulario_caja">
<div class="row">
		<div class="col-md-4">
			<div class="wallpaper">
				<img src="assets/img/form.svg" alt="">
			</div>
		</div>

		<div class="col-md-8">
			<div class="formulario">
				<form id="form-prenomina">
					<div class="text-left p-2">
						<h4 class="negrita text-primary">Generar prenómina</h4>
						<small class="text-muted">Genera esta prenómina las veces que quieras antes de autorizar el periodo.</small>
					</div>
					<div class="card">
						<div class="row card-body">
							<div class="col-md-12">
								<div class="">
									<div class="select-etiqueta">Periodo</div>
									<input class="campo" value="'.$prenomina["periodo"].'" type="text" disabled>
								</div>
							</div>
							<div class="col-md-6">
								<div class="">
									<div class="select-etiqueta">Del</div>
									<input type="text" disabled class="campo" value="'.$del.'" required/>
								</div>
							</div>

							<div class="col-md-6">
								<div class="">
									<div class="select-etiqueta ">Al</div>
									<input type="text" disabled class="campo" value="'.$al.'" required/>
								</div>
							</div>
							<div class="col-md-12">
								<div class="">
									<div class="select-etiqueta">Observaciones <cite class="text-danger">opcional</cite></div>
									<textarea id="observacion" class="campo"></textarea>
								</div>
							</div>
						</div>
					</div>

					

					<div class="text-right p-3 pagina_2_opciones">
						<div id="salir" class="btn btn-secondary btn-sm">Cancelar </div>
						<button type="submit" class="btn btn-success btn-sm"><i class="material-icons">receipt_long</i> Generar prenomina</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div> ';

$conexion->close();



// <div class="card">
// 						<div class="card-body text-left">
// 							<div class="row">
// 								<div class="col-2">
// 									<div class="toggle-btn active">
// 										<input id="check1" type="checkbox" class="cb-value" checked />
// 										<span class="round-btn"></span>
// 									</div>
// 								</div>
// 								<div class="col-10">
// 									<p class="text-muted">¿Actualizar fechas a partir de la última prenómina registrada?</p>
// 								</div>
// 							</div>
// 						</div>
// 					</div>