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

$sql = "SELECT *,
	Usuario.RFC AS RFC,
   (SELECT nombre FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto) AS puesto,
   (SELECT nombre FROM Departamento WHERE id_departamento = (SELECT Puesto.id_departamento FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto)) AS departamento,
   (SELECT nombre FROM Trabajador WHERE Trabajador.id_trabajador = Empleado.id_trabajador) AS tipoTrabajador  
   FROM Usuario LEFT JOIN Empleado ON Usuario.RFC = Empleado.RFC WHERE Empleado.RFC = '" . $id . "'";

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

    echo '<div class="ver_caja">
			<div class="ver_panel">
				<div class="foto-caja">
					<img class="foto-empleado foto" src="' . $imagen . '" />
					<input class="hidden" type="file" id="input-foto" accept="image/jpg, image/png, iamge/jpeg" >
				</div>

				<p onclick="verPerfil(\''.$res["RFC"].'\')"class="activo">Perfil</p>
				<p onclick="verNominas(\''.$res["RFC"].'\')">CFDI</p>
				<p onclick="verBeneficiarios(\''.$res["RFC"].'\')">Beneficiarios</p>
				<p onclick="verMovimientos(\''.$res["RFC"].'\')">Movimientos</p>
				<p onclick="verPases(\''.$res["RFC"].'\')">Pases</p>
				<p onclick="verVacaciones(\''.$res["RFC"].'\')">Vacaciones</p>
				<p onclick="verPermisos(\''.$res["RFC"].'\')">Licencias</p>
                <p onclick="verDescuentos(\''.$res["RFC"].'\')">Descuentos</p>
				<p onclick="verGastos(\''.$res["RFC"].'\')">Gastos médicos</p>
				<p onclick="verExpediente(\''.$res["RFC"].'\')">Expediente</p>
				<p onclick="verHistorial(\''.$res["RFC"].'\')">Altas y Bajas</p>

			</div>


            <div class="ver_boton"><i onclick="ver_panel();" class="material-icons regresar">keyboard_backspace</i></div>
			<div class="ver_contenedor"></div>
		</div>';
} else {
    echo 0;
}

mysqli_close($conexion);


