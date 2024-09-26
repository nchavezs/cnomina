<?php
$id = $_POST['id'];

$html = '
<form id="form-documento" autocomplete="off">
    <input type="hidden" name="id" value="' . $id . '">
    <div class="row p-4">
        <div class="col-md-12">
            <div>Nombre del documento</div>
            <input type="text" name="nombre" class="campo" required />
        </div>
    </div>
</form>
';

echo $html;
