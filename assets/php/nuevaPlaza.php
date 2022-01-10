<?php
include "conexion.php";
$conexion = conexion();

echo '<form id="form-plaza">
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">Nueva Plaza</h4>
					<p class="card-category"> - - - </span></p>
				</div>
				<div class="card-body">
					<div class="row">

						<div class="col-md-6">
							<div class="form-group">
							<div class="select-etiqueta">Días presupuestados</div>
							  <input id="dias" type="text" maxlength="3" min="1" class="form-control" required onkeydown="return isNumberKey(event)"/>
							</div>
						</div>
                        <div class="col-md-6">
							<div class="form-group">
							<div class="select-etiqueta">Fecha</div>
							  <input id="fecha" type="text" class="form-control datepicker-here" readonly value="01/01/'.date("Y").'"/>
							</div>
						</div>

                        <div class="col-md-12">
							<div class="form-group">
							<div class="select-etiqueta">Cantidad de plazas</div>
							  <input id="cantidad" step="1" type="number" min="1" max="999" class="form-control" value="1" required/>
							</div>
						</div>

                        <div class="col-md-12">
                            <div class="select">
                                <div class="select-etiqueta">Departamento</div>
                                <select id="departamento"">';
                                $sql = "SELECT * FROM Departamento ORDER BY nombre ASC";
                                $consulta = mysqli_query($conexion, $sql);
                                if ($consulta && (mysqli_num_rows($consulta)) > 0) {
                                    while ($res = mysqli_fetch_row($consulta)) {
                                        echo '<option value="' . $res[0] . '">' . $res[1] . '</option>';
                                    }
                                } else {
                                    echo '<option selected="true" value="">NO HAY OPCIONES DISPONIBLES</option>';
                                }

                            echo '</select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="select">
                                <div class="select-etiqueta">Puesto</div>
                                <select id="puesto">
                                </select>
                            </div>
                        </div>
					</div>
				</div>
			</div>';

echo '<div class="row">
        <div class="col-6">
            <div id="salir" class="btn btn-primary regresar"><i class="material-icons">arrow_back</i> Regresar </div>
            </div>
        <div class="col-6">
            <button type="submit" class="btn btn-primary regresar" ><i class="material-icons">thumb_down_alt</i> Guardar </button>
        </div>
    </div>
</form>';

mysqli_close($conexion);