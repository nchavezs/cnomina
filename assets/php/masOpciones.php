<?php
session_start();
include "conexion.php";
include "rol.php";
$rol = rol();
$conexion = conexion();
$id = $_POST['id'];

$consulta = "SELECT * FROM Usuario WHERE RFC = '" . $id . "'";

if ($resultado = mysqli_query($conexion, $consulta)) {
    while ($res = mysqli_fetch_array($resultado)) {
        if (is_null($res['urlFoto'])) {
            $imagen = "assets/img/user.svg";
        } else {
            $imagen = $res['urlFoto'];
        }

        echo '<div class="contenedor">
				<div class="row">
					<div class="col-md-12 perfil-caja">
						<div class="card-profile">
							<div class="fondo">';

        if($rol == 1){
			if ($res['estado'] === 'baja') {
				echo '<div id="boton-reingreso" class="boton-flotante">
									<p>Reingreso</p><i class="material-icons">thumb_up_alt</i>
								</div>';
			} else {
				echo '<div id="boton-baja" class="boton-flotante">
									<p>Dar de baja</p><i class="material-icons">thumb_down_alt</i>
								</div>';
			}
	
			echo '<div id="boton-password" class="boton-flotante">
									<p>Reestablecer contraseña</p><i class="material-icons">vpn_key</i>
								</div>';
		}

        echo '</div>
							<div class="foto-caja">
								<img id = "foto-empleado" class="foto-empleado" src="' . $imagen . '" />
								<input type="file" id="input-foto" accept=".jpg, .png, .jpeg" style="display:none">
							</div>
						</div>
						<div class="perfil-contenido">
							<div class="perfil-contenido1">
								<h3>' . $res['nombre'] . '</h3>
								<h5>' . $res['puesto'] . '</h5>
								<h6>' . $res['departamento'] . '</h6>
							</div>
							<div class="perfil-contenido2">
								<div class="row perfil-contenido3">
									<div class="col-md-4 col-6">';

									if($rol != 2){
										echo '<div id="recibos-link" class="icono-caja2">
										<i class="material-icons">text_snippet</i>
										<p>Nóminas</p>
									</div>';
									}else{
										echo '<div onclick="no_pasar();" class="icono-caja2">
										<i class="material-icons">text_snippet</i>
										<p>Nóminas</p>
									</div>';
									}
										
									echo '</div>
									<div class="col-md-4 col-6">';
									if($rol == 1){
										echo '<div id="beneficiarios-link" class="icono-caja2">
											<i class="material-icons">people</i>
											<p>Beneficiarios</p>
										</div>';
									}else{
										echo '<div onclick="no_pasar();" class="icono-caja2">
											<i class="material-icons">people</i>
											<p>Beneficiarios</p>
										</div>';
									}
										
									echo '</div>
									<div class="col-md-4 col-6">';
									if($rol == 1){
										echo '<div id="pases" class="icono-caja2">
											<i class="material-icons">watch_later</i>
											<p>Pases</p>
										</div>';
									}else{
										echo '<div onclick="no_pasar();" class="icono-caja2">
											<i class="material-icons">watch_later</i>
											<p>Pases</p>
										</div>';
									}
										
									echo '</div>
									<div class="col-md-4 col-6">';
									if($rol == 1){
										echo '<div id="fecha-link" class="icono-caja2">
											<i class="material-icons">description</i>
											<p>Licencias</p>
										</div>';
									}else{
										echo '<div onclick="no_pasar();" class="icono-caja2">
											<i class="material-icons">description</i>
											<p>Licencias</p>
										</div>';
									}

									echo '</div>
									<div class="col-md-4 col-6">';
									if($rol == 1){
										echo '<div id="movimientos" class="icono-caja2">
											<i class="material-icons">transfer_within_a_station</i>
											<p>Movimientos</p>
										</div>';
									}else{
										echo '<div onclick="no_pasar();" class="icono-caja2">
											<i class="material-icons">transfer_within_a_station</i>
											<p>Movimientos</p>
										</div>';
									}

									echo '</div>
									<div class="col-md-4 col-6">';
									if($rol == 1){
										echo '<div id="vacaciones" class="icono-caja2">
											<i class="material-icons">flight</i>
											<p>Vacaciones</p>
										</div>';
									}else{
										echo '<div onclick="no_pasar();" class="icono-caja2">
											<i class="material-icons">flight</i>
											<p>Vacaciones</p>
										</div>';
									}


									echo '</div>
									<div class="col-md-4 col-6">';
									if($rol == 1){
										echo '<div id="descuentos" class="icono-caja2">
											<i class="material-icons">trending_down</i>
											<p>Descuentos</p>
										</div>';
									}else{
										echo '<div onclick="no_pasar();" class="icono-caja2">
											<i class="material-icons">trending_down</i>
											<p>Descuentos</p>
										</div>';
									}

									echo '</div>
									<div class="col-md-4 col-6">';
									if($rol != 3){
										echo '<div id="expediente" class="icono-caja2">
											<i class="material-icons">folder_shared</i>
											<p>Expediente</p>
										</div>';
									}else{
										echo '<div onclick="no_pasar();" class="icono-caja2">
											<i class="material-icons">folder_shared</i>
											<p>Expediente</p>
										</div>';
									}

									echo '</div>
									<div class="col-md-4">';
									if($rol == 1){
										echo '<div id="gastos" class="icono-caja2">
											<i class="material-icons">healing</i>
											<p>Gastos médicos</p>
										</div>';
									}else{
										echo '<div onclick="no_pasar();" class="icono-caja2">
											<i class="material-icons">healing</i>
											<p>Gastos médicos</p>
										</div>';
									}

									echo '</div>
									<div class="col-md-12 flecha">
										<button id="anterior" class="learn-more">
											<span class="circle" aria-hidden="true"><span class="icon arrow"></span></span>
											<span class="button-text">Inicio</span>
										</button>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>';
    }
} else {
    echo 0;
}

mysqli_close($conexion);
