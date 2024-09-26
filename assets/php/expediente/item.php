<?php

if ($fichero['url']) {
    echo '<div class="col-md-4">
            <div class="expediente_caja" data-id="'.$fichero["id_fichero"].'">
                <button class="descargar" data-hover="descargar">
                    <div><i class="material-icons done">cloud_done</i></div>
                </button>
                <p>'.$fichero["documento"].'</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono subir_documento"><i class="material-icons">upload</i>Cargar</div>
                    <div onclick="eliminar_fichero('.$fichero["id_fichero"].');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>
                </div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja" data-id="'.$fichero["id_fichero"].'">
                <button class="subir_documento" data-hover="Subir">
                    <div><i class="material-icons">search</i></div>
                </button>
                <p>'.$fichero["documento"].'</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_vacio subir_documento"><i class="material-icons">search</i>Seleccionar archivo  </div>
                </div>
            </div>
        </div>';
}