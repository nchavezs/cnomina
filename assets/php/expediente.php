<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

$sql = "SELECT * FROM Expediente WHERE RFC = '" . $id . "'";
$consulta = mysqli_query($conexion, $sql);
$expediente = mysqli_fetch_array($consulta);
if (mysqli_num_rows($consulta) == 0) {
    $sql = "INSERT INTO Expediente(RFC) VALUES('" . $id . "')";
    $consulta = mysqli_query($conexion, $sql);
} 

$sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = mysqli_query($conexion, $sql);
$nombre = mysqli_fetch_row($consulta);

echo '<div class="card">
        <input class="hidden" type="file" id="expediente_file" accept=".pdf"/>
		<div class="card-header card-header-primary">
            <h4 class="card-title ">Expediente</h4>
            <p class="card-category">' . $nombre[0] . '</p>
		</div>
        <div class="card-body">';

echo '<div class="row">';

if (isset($expediente['acta'])) {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button name="acta" class="descargar" data-hover="Ver archivo">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Acta de nacimiento</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono acta"><i class="material-icons">cloud_upload</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'acta\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button class="acta" data-hover="Cargar archivo">
                    <div><i class="material-icons">find_in_page</i></div>
                </button>
                <p>Acta de nacimiento</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio acta"><i class="material-icons">cloud_upload</i>Seleccionar archivo ... </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['curp'])) {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button name="curp" class="descargar" data-hover="Ver archivo">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>CURP</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono curp"><i class="material-icons">cloud_upload</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'curp\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button class="curp" data-hover="Cargar archivo">
                    <div><i class="material-icons">find_in_page</i></div>
                </button>
                <p>CURP</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio curp"><i class="material-icons">cloud_upload</i>Seleccionar archivo ... </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['curriculum'])) {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button name="curriculum" class="descargar" data-hover="Ver archivo">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Currículum</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono curriculum"><i class="material-icons">cloud_upload</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'curriculum\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button class="curriculum" data-hover="Cargar archivo">
                    <div><i class="material-icons">find_in_page</i></div>
                </button>
                <p>Currículum</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio curriculum"><i class="material-icons">cloud_upload</i>Seleccionar archivo ... </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['antecedentes'] )) {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button name="antecedentes" class="descargar" data-hover="Ver archivo">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Carta de no antecedentes penales</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono antecedentes"><i class="material-icons">cloud_upload</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'antecedentes\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button class="antecedentes" data-hover="Cargar archivo">
                    <div><i class="material-icons">find_in_page</i></div>
                </button>
                <p>Carta de no antecedentes penales</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio antecedentes"><i class="material-icons">cloud_upload</i>Seleccionar archivo ... </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['disciplinarios'] )) {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button name="disciplinarios" class="descargar" data-hover="Ver archivo">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Carta de no antecedentes disciplinarios</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono disciplinarios"><i class="material-icons">cloud_upload</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'disciplinarios\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button class="disciplinarios" data-hover="Cargar archivo">
                    <div><i class="material-icons">find_in_page</i></div>
                </button>
                <p>Carta de no antecedentes disciplinarios</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio disciplinarios"><i class="material-icons">cloud_upload</i>Seleccionar archivo ... </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['identificacion'])) {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button name="identificacion" class="descargar" data-hover="Ver archivo">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Identificación oficial</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono identificacion"><i class="material-icons">cloud_upload</i>Cargar</div>
                    <div onclick="eliminar_expediente(\''.$id.'\', \'identificacion\');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button class="identificacion" data-hover="Cargar archivo">
                    <div><i class="material-icons">find_in_page</i></div>
                </button>
                <p>Identificación oficial</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio identificacion"><i class="material-icons">cloud_upload</i>Seleccionar archivo ... </div>
                </div>
            </div>
        </div>';
}

echo '</div>
    </div>
</div>';

echo '<div class="row">
        <div class="col-12">
            <div class="btn btn-primary regresar" id="' . $id . '" onclick="ver(this.id, 1)"><i class="material-icons">arrow_back</i> Regresar </div>
        </div>
    </div>';

mysqli_close($conexion);
