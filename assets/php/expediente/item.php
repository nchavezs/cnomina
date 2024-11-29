<?php

if ($fichero['url']) {
    echo '<div class="col-md-4">
            <div class="expediente_caja" data-id="' . $fichero["id_fichero"] . '">
                <a target="_blank" href="/assets/' . $fichero["url"] .'">
                    <button class="descargar" data-hover="descargar">
                        <div><i class="material-icons done">cloud_done</i></div>
                    </button>
                </a>
                <p>' . $fichero["documento"] . '</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono" onclick="subir_fichero(' . $fichero["id_fichero"] . ')"><i class="material-icons">upload</i>Sobreescribir</div>';
                    if(id_rol()){
                        echo '<div onclick="eliminar_fichero(' . $fichero["id_fichero"] . ', true);" class="opciones_expediente_icono"><i class="material-icons">backspace</i>Eliminar</div>';
                    }
            echo '</div>
            </div>
        </div>';
} else {
    echo '<div class="col-md-4">
            <div class="expediente_caja" data-id="' . $fichero["id_fichero"] . '">
                <button onclick="subir_fichero(' . $fichero["id_fichero"] . ')" data-hover="Subir">
                    <div><i class="material-icons">newspaper</i></div>
                </button>
                <p>' . $fichero["documento"] . '</p>
                <div class="opciones_expediente_caja">
                    <div class="opciones_expediente_icono" onclick="subir_fichero(' . $fichero["id_fichero"] . ')"><i class="material-icons">upload</i>Cargar</div>';
                if(id_rol()){
                   echo '<div onclick="eliminar_fichero(' . $fichero["id_fichero"] . ');" class="opciones_expediente_icono"><i class="material-icons">delete_sweep</i>Eliminar</div>';
                }
           echo '</div>
            </div>
        </div>';
}
