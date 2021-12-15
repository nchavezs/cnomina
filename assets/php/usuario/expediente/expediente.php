<?php
session_start();
include "../../conexion.php";
$conexion = conexion();
$id = $_SESSION["usuario"];

$sql = "SELECT * FROM Expediente WHERE RFC = '" . $id . "'";
$consulta = mysqli_query($conexion, $sql);
$expediente = mysqli_fetch_array($consulta);

$sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $id . "'";
$consulta = mysqli_query($conexion, $sql);
$nombre = mysqli_fetch_row($consulta);

echo '<div class="card">
        <input type="hidden" id="id" value="'.$id.'">
		<div class="card-header card-header-primary">
            <h4 class="card-title ">Expediente</h4>
            <p class="card-category">' . $nombre[0] . '</p>
		</div>
        <div class="card-body">';

echo '<div class="row">';

if (isset($expediente['acta'] )) {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button name="acta" class="descargar" data-hover="Descargar archivo">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Acta de nacimiento</p>
                <div class="opciones_expediente_caja">
                    <a href="'.$expediente['acta'].'" target="_blank" class="opciones_expediente_vacio"><i class="material-icons">open_in_browser</i>Ver archivo  </a>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button class="acta" data-hover="Sin archivo">
                    <div><i class="material-icons">cloud_off</i></div>
                </button>
                <p>Acta de nacimiento</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio"><i class="material-icons">info</i>No se encontró archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['curp'] )) {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button name="curp" class="descargar" data-hover="Descargar archivo">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>CURP</p>
                <div class="opciones_expediente_caja">
                    <a href="'.$expediente['curp'].'" target="_blank" class="opciones_expediente_vacio"><i class="material-icons">open_in_browser</i>Ver archivo  </a>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button class="curp" data-hover="Sin archivo">
                    <div><i class="material-icons">cloud_off</i></div>
                </button>
                <p>CURP</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio curp"><i class="material-icons">info</i>No se encontró archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['curriculum'] )) {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button name="curriculum" class="descargar" data-hover="Descargar archivo">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Currículum</p>
                <div class="opciones_expediente_caja">
                    <a href="'.$expediente['curriculum'].'" target="_blank" class="opciones_expediente_vacio"><i class="material-icons">open_in_browser</i>Ver archivo  </a>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button class="curriculum" data-hover="Sin archivo">
                    <div><i class="material-icons">cloud_off</i></div>
                </button>
                <p>Currículum</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio curriculum"><i class="material-icons">info</i>No se encontró archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['antecedentes'] )) {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button name="antecedentes" class="descargar" data-hover="Descargar archivo">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Carta de no antecedentes penales</p>
                <div class="opciones_expediente_caja">
                    <a href="'.$expediente['antecedentes'].'" target="_blank" class="opciones_expediente_vacio"><i class="material-icons">open_in_browser</i>Ver archivo  </a>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button class="antecedentes" data-hover="Sin archivo">
                    <div><i class="material-icons">cloud_off</i></div>
                </button>
                <p>Carta de no antecedentes penales</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio antecedentes"><i class="material-icons">info</i>No se encontró archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['disciplinarios'] )) {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button name="disciplinarios" class="descargar" data-hover="Descargar archivo">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Carta de no antecedentes disciplinarios</p>
                <div class="opciones_expediente_caja">
                    <a href="'.$expediente['disciplinarios'].'" target="_blank" class="opciones_expediente_vacio"><i class="material-icons">open_in_browser</i>Ver archivo  </a>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button class="disciplinarios" data-hover="Sin archivo">
                    <div><i class="material-icons">cloud_off</i></div>
                </button>
                <p>Carta de no antecedentes disciplinarios</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio disciplinarios"><i class="material-icons">info</i>No se encontró archivo  </div>
                </div>
            </div>
        </div>';
}

if (isset($expediente['identificacion'] )) {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button name="identificacion" class="descargar" data-hover="Descargar archivo">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>Identificación oficial</p>
                <div class="opciones_expediente_caja">
                    <a href="'.$expediente['identificacion'].'" target="_blank" class="opciones_expediente_vacio"><i class="material-icons">open_in_browser</i>Ver archivo  </a>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja">
                <button class="identificacion" data-hover="Sin archivo">
                    <div><i class="material-icons">cloud_off</i></div>
                </button>
                <p>Identificación oficial</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio identificacion"><i class="material-icons">info</i>No se encontró archivo  </div>
                </div>
            </div>
        </div>';
}

echo '</div>
    </div>
</div>';


mysqli_close($conexion);
