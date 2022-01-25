<?php
include "conexion.php";
$conexion = conexion();

echo '<div class="formulario_caja">
        <form class="formulario" id="form-plaza">
            <div class="p-2">
                <h4 class="font-weight-bold text-primary">Registrar nueva plaza</h4>
                <small class="text-muted">Completa el siguiente formulario.</small>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="select-etiqueta">Días presupuestados</div>
                            <input id="dias" type="text" maxlength="3" min="1" class="campo" required onkeydown="return isNumberKey(event)"/>
                        </div>
                        <div class="col-md-6">
                            <div class="select-etiqueta">Fecha aprobación presupuesto</div>
                            <input id="fecha" type="text" class="campo" disabled value="01/01/'.date("Y").'"/>
                        </div>

                        <div class="col-md-12">
                            <div class="select-etiqueta">Cantidad de plazas</div>
                            <input id="cantidad" step="1" type="number" min="1" max="999" class="campo" value="1" required/>
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
            </div>
            <div class="pie">
                <div id="salir" class="btn btn-secondary btn-sm">Regresar </div>
                <button type="submit" class="btn btn-success btn-sm"><i class="material-icons">save</i> Guardar </button>
            </div>
        </form>
    </div>';  

mysqli_close($conexion);