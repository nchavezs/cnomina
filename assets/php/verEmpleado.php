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

$consulta = "SELECT id_usuario, nombre, RFC, CURP, fechaRelLab, puesto, departamento, email, telefono, urlFoto, estado, banca, afiliacion, tipoTrabajador FROM Usuario WHERE RFC = '" . $id . "'";

if ($resultado = mysqli_query($conexion, $consulta)) {
    $res = mysqli_fetch_array($resultado);
    if (is_null($res[9])) {
        $imagen = "assets/img/user.svg";
    } else {
        $imagen = $res[9];
    }

    if (is_null($res[8])) {
        $tel = "Sin número";
    } else {
        $tel = $res[8];
    }

    if (is_null($res[7])) {
        $correo = "Sin correo electrónico";
    } else {
        $correo = $res[7];
    }

    if (is_null($res[11]) || trim($res[11]) === "") {
        $banca = "N/A";
    } else {
        $banca = $res[11];
    }

    if (is_null($res[12]) || trim($res[12]) === "") {
        $afiliacion = "N/A";
    } else {
        $afiliacion = $res[12];
    }

    if (is_null($res[4]) || trim($res[4]) === "") {
        $inicio = "-";
    } else {
        $inicio = $res[4];
    }

    if (is_null($res[0]) || trim($res[0]) === "") {
        $numero = "-";
    } else {
        $numero = str_pad($res[0], 5, '0', STR_PAD_LEFT);
    }

    if (is_null($res[3]) || trim($res[3]) === "") {
        $curp = "-";
    } else {
        $curp = $res[3];
    }

    if (is_null($res[13]) || trim($res[13]) === "") {
        $trabajador = "N/A";
    } else {
        $trabajador = $res[13];
    }

    if ($res[10] === 'baja') {
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
								<h3>' . $res[1] . '</h3>
								<h5>' . $res[5] . '</h5>
								<h6>' . $res[6] . '</h6>
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
											<h5>' . ucfirst($res[10]) . '</h5>
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
