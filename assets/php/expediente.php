<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Expediente WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
$expediente = mysqli_fetch_array($consulta);
if (mysqli_num_rows($consulta) == 0) {
    $sql = "INSERT INTO Expediente(RFC) VALUES('" . $id . "')";
    $consulta = $conexion->query($sql);
} 

$sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

echo '
<div class="p-2">
    <h4 class="negrita text-primary">Expediente de usuario</h4>
    <small class="text-muted">Expediente de '.$usuario["nombre"].'.</small>
</div>

<div class="">
        <input class="hidden" type="file" id="expediente_file" accept=".pdf,.png,.jpg,.jpeg,.zip,.rar"/>
        <div class="card-body p-0">';

echo '<div class="row">';

echo '<div class="col-md-4">
    <div class="expediente_caja puntero" onclick="nuevo_doc(\''.$id.'\')">
        <div class="card-body p-4">
            <i class="material-icons">add</i>
            <div class="opciones_expediente_vacio p-1">Agregar nuevo documento</div>
        </div>
    </div>
</div>';

// agregar aqui ficheros

if (isset($expediente['acta'])) {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="acta">
                <button name="acta" class="descargar" data-hover="descargar">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Acta de nacimiento</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono subir_documento"><i class="material-icons">upload_file</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'acta\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="acta">
                <button class="subir_documento" data-hover="Subir">
                    <div><i class="material-icons">upload_file</i></div>
                </button>
                <p>Acta de nacimiento</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio subir_documento"><i class="material-icons">upload_file</i>Seleccionar archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['curp'])) {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="curp">
                <button name="curp" class="descargar" data-hover="descargar">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>CURP</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono subir_documento"><i class="material-icons">upload_file</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'curp\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="curp">
                <button class="subir_documento" data-hover="Subir">
                    <div><i class="material-icons">upload_file</i></div>
                </button>
                <p>CURP</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio subir_documento"><i class="material-icons">upload_file</i>Seleccionar archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['curriculum'])) {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="curriculum">
                <button name="curriculum" class="descargar" data-hover="descargar">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Currículum</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono subir_documento"><i class="material-icons">upload_file</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'curriculum\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="curriculum">
                <button class="subir_documento" data-hover="Subir">
                    <div><i class="material-icons">upload_file</i></div>
                </button>
                <p>Currículum</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio subir_documento"><i class="material-icons">upload_file</i>Seleccionar archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['antecedentes'] )) {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="antecedentes">
                <button name="antecedentes" class="descargar" data-hover="descargar">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Carta de no antecedentes penales</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono subir_documento"><i class="material-icons">upload_file</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'antecedentes\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="antecedentes">
                <button class="subir_documento" data-hover="Subir">
                    <div><i class="material-icons">upload_file</i></div>
                </button>
                <p>Carta de no antecedentes penales</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio subir_documento"><i class="material-icons">upload_file</i>Seleccionar archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['disciplinarios'] )) {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="disciplinarios">
                <button name="disciplinarios" class="descargar" data-hover="descargar">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Carta de no antecedentes disciplinarios</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono subir_documento"><i class="material-icons">upload_file</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'disciplinarios\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="disciplinarios">
                <button class="subir_documento" data-hover="Subir">
                    <div><i class="material-icons">upload_file</i></div>
                </button>
                <p>Carta de no antecedentes disciplinarios</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio subir_documento"><i class="material-icons">upload_file</i>Seleccionar archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['identificacion'])) {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="identificacion">
                <button name="identificacion" class="descargar" data-hover="descargar">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Identificación oficial</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono subir_documento"><i class="material-icons">upload_file</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'identificacion\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="identificacion">
                <button class="subir_documento" data-hover="Subir">
                    <div><i class="material-icons">upload_file</i></div>
                </button>
                <p>Identificación oficial</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio subir_documento"><i class="material-icons">upload_file</i>Seleccionar archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['constancia'])) {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="constancia">
                <button name="constancia" class="descargar" data-hover="descargar">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Constancia de situación fiscal</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono subir_documento"><i class="material-icons">upload_file</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'constancia\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="constancia">
                <button class="subir_documento" data-hover="Subir">
                    <div><i class="material-icons">upload_file</i></div>
                </button>
                <p>Constancia de situación fiscal</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio subir_documento"><i class="material-icons">upload_file</i>Seleccionar archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['recomendacion'])) {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="recomendacion">
                <button name="recomendacion" class="descargar" data-hover="descargar">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Recomendación</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono subir_documento"><i class="material-icons">upload_file</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'recomendacion\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="recomendacion">
                <button class="subir_documento" data-hover="Subir">
                    <div><i class="material-icons">upload_file</i></div>
                </button>
                <p>Recomendación</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio subir_documento"><i class="material-icons">upload_file</i>Seleccionar archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['estudios'])) {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="estudios">
                <button name="estudios" class="descargar" data-hover="descargar">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Últimos estudios</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono subir_documento"><i class="material-icons">upload_file</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'estudios\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-3">
            <div class="expediente_caja" data-nombre="estudios">
                <button class="subir_documento" data-hover="Subir">
                    <div><i class="material-icons">upload_file</i></div>
                </button>
                <p>Últimos estudios</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio subir_documento"><i class="material-icons">upload_file</i>Seleccionar archivo  </div>
                </div>
            </div>
        </div>';
}

echo '</div>
    </div>
</div>';

$conexion->close();
