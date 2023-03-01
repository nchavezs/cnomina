<?php

echo '
<div class="p-5">
    <div class="pb-5 text-left">
        <h4 class="negrita text-primary">Carga masiva de archivos</h4>
        <p><small class="text-muted">Puedes cargar de forma masiva las <span class="text-warning">constancias de situación fiscal</span> siguiendo los siguientes puntos:</small></p>
        <small class="text-muted"><i class="material-icons leyenda">info</i> En caso de que tus archivos sean de tipo imagen deberás renombrar tus archivos al RFC del empleado.</small>
        <br/>
        <small class="text-muted"><i class="material-icons leyenda">info</i> Si tu archivo está en formato PDF no es necesario renombrarlo ya que tomará el RFC directo del documento.</small>
        <br/>
        <small class="text-muted"><i class="material-icons leyenda">info</i> Los archivos corretamente cargados se eliminarán de la caja de carga, los que contengan errores se mantendrán.</small>
    </div>

    <div>
        <form class="dropzone caja_scroll" id="dropzone" enctype="multipart/form-data">
            <div class="dz-message">
                <div class="row">
                    <div class="col-md-4"><img src="assets/img/upload.svg" alt=""></div>
                    <div class="col-md-8">
                        <h2 class="negrita mt-3">Selecciona tus archivos</h2>
                        <div> <p>Carga tus archivos en esta sección.</p></div>
                        <button type="button" class="btn btn-sm btn-primary mt-4"> <i class="material-icons">search</i> Buscar archivos</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <button class="btn btn-success rounded btn_subir btn-sm mt-4" disabled><i class="material-icons">upload</i> Cargar archivos</button>

</div>
';