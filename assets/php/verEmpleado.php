<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$inicio_label = "<h5>Fecha de inicio</h5>";
$reingreso_label = "";
$reingreso = "";
$baja_label = "";
$baja = "";

$sql = "SELECT * FROM Reingreso WHERE RFC = '" . $id . "' ORDER BY id_reingreso ASC LIMIT 1";
$consulta = mysqli_query($conexion, $sql);
if ($consulta && mysqli_num_rows($consulta) == 1) {
    $resultado = mysqli_fetch_array($consulta);
    $inicio_label = "<h5>Fecha de reingreso</h5>";
    $reingreso_label = "<h5>Fecha de inicio</h5>";
    $reingreso = '<h5>' . date("d/m/Y", strtotime($resultado['inicio'])) . '</h5>';
}

$sql = "SELECT
   Empleado.*,
   nombre,
   urlFoto,
   email,
   telefono,
   estado,
   (SELECT nombre FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto) AS puesto,
   (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT Puesto.id_departamento FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto)) AS departamento,
   (SELECT nombre FROM Trabajador WHERE Trabajador.id_trabajador = Empleado.id_trabajador) AS tipoTrabajador  
   FROM Empleado LEFT JOIN Usuario ON Empleado.RFC = Usuario.RFC WHERE Empleado.RFC = '" . $id . "'";

if ($resultado = mysqli_query($conexion, $sql)) {
    $res = mysqli_fetch_array($resultado);
    if (is_null($res["urlFoto"])) {
        $imagen = "assets/img/user.svg";
    } else {
        $imagen = $res["urlFoto"];
    }

    if (is_null($res["telefono"])) {
        $tel = "Sin número";
    } else {
        $tel = $res["telefono"];
    }

    if (is_null($res["email"])) {
        $correo = "Sin correo electrónico";
    } else {
        $correo = $res["email"];
    }

    if (is_null($res["banca"]) || trim($res["banca"]) === "") {
        $banca = "N/A";
    } else {
        $banca = $res["banca"];
    }

    if (is_null($res["afiliacion"]) || trim($res["afiliacion"]) === "") {
        $afiliacion = "N/A";
    } else {
        $afiliacion = $res["afiliacion"];
    }

    if (is_null($res["fechaRelLab"]) || trim($res["fechaRelLab"]) === "") {
        $inicio = "-";
    } else {
        $inicio = $res["fechaRelLab"];
    }

    if (is_null($res["id_empleado"]) || trim($res["id_empleado"]) === "") {
        $numero = "-";
    } else {
        $numero = str_pad($res["id_empleado"], 5, '0', STR_PAD_LEFT);
    }

    if (is_null($res["CURP"]) || trim($res["CURP"]) === "") {
        $curp = "-";
    } else {
        $curp = $res["CURP"];
    }

    if (is_null($res["tipoTrabajador"]) || trim($res["tipoTrabajador"]) === "") {
        $trabajador = "N/A";
    } else {
        $trabajador = $res["tipoTrabajador"];
    }

    if ($res["estado"] === 'baja') {
        $sql1 = "SELECT * FROM Baja WHERE RFC = '" . $id . "' ORDER BY id_baja DESC LIMIT 1";
        $consulta1 = mysqli_query($conexion, $sql1);
        if ($consulta1 && mysqli_num_rows($consulta1) == 1) {
            $resultado1 = mysqli_fetch_array($consulta1);
            $baja_label = "<h5>Fecha de baja</h5>";
            $baja = '<h5>' . date("d/m/Y", strtotime($resultado1['fecha'])) . '</h5>';
        }
    }

    echo '<div class="contenedor">
				<div class="row">
					<div class="col-md-12 perfil-caja">
						<div class="card-profile">
							<div class="fondo">
							</div>
							<div class="foto-caja">
								<img id="foto-empleado" class="foto-empleado" src="' . $imagen . '" />
								<input type="file" id="input-foto" accept=".jpg, .png, .jpeg" style="display:none">
							</div>
							<div class="boton-flotante boton-info">
								<p><span class="oculto"># Empleado</span> <i class="negrita">'.$numero.'</i></p>
							</div>
						</div>
						<div class="perfil-contenido">
							<div class="perfil-contenido1">
								<h3>' . $res["nombre"] . '</h3>
								<h5>' . $res["puesto"] . '</h5>
								<h6>' . $res["departamento"] . '</h6>
							</div>


							<div class="perfil-contenido2">
								<div class="row">
									<div class="col-5 espacio">
										
										<h5>RFC</h5>
										<h5>CURP</h5>
										<h5>Trabajador</h5>
										' . $inicio_label . '
										' . $reingreso_label . '
										' . $baja_label . '
										<h5>Cuenta bancaria</h5>
										<h5>Afiliación</h5>

									</div>
									<div class="col-7">
										
										<h5>' . $res[2] . '</h5>
										<h5>' . $curp . '</h5>
										<h5>' . $trabajador . '</h5>
										<h5>' . $inicio . '</h5>
										' . $reingreso . '
										' . $baja . '

										<h5>' . $banca . '</h5>
										<h5>' . $afiliacion . '</h5>
									</div>
									<div class="col-md-12 flecha">
										<button id="siguiente" class="learn-more">
											<span class="circle" aria-hidden="true"><span class="icon arrow"></span></span>
											<span class="button-text">Ver más</span>
										</button>
									</div>
								</div>
							</div>

							<div class="perfil-iconos">
								<div class="row">
									<div class="col-3">
										<div class="icono-caja">
											<i class="material-icons">phone</i>
											<h5>' . $tel . '</h5>
										</div>
									</div>
									<div class="col-6">
										<div class="icono-caja">
											<i class="material-icons">email</i>
											<h5>' . $correo . '</h5>
										</div>
									</div>
									<div class="col-3">
										<div class="icono-caja">
											<i class="material-icons">assignment_ind</i>
											<h5>' . ucfirst($res["estado"]) . '</h5>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>';
} else {
    echo 0;
}

mysqli_close($conexion);
