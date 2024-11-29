<?php
// PRIMEROS 9 ARCHIVOS
for ($i = 1;$i<=9;$i++) {
    $sql = "SELECT * FROM Fichero WHERE id_documento = $i AND RFC = '$id'";
    $consulta = $conexion->query($sql);
    if (mysqli_num_rows($consulta) == 0) {
        $sql = "INSERT INTO Fichero (id_documento, RFC) VALUES ($i, '$id')";
        $conexion->query($sql);
    }
}
// ---------------------------------------------------------------------------------------
$sql = "SELECT Fichero.*,(SELECT nombre FROM Documento WHERE id_documento = Fichero.id_documento) as documento FROM Fichero WHERE RFC = '" . $id . "' ORDER BY id_fichero DESC ";
$consulta = $conexion->query($sql);

if (mysqli_num_rows($consulta) == 0) {
    $sql = "INSERT INTO Fichero(RFC) VALUES('" . $id . "')";
    $consulta = $conexion->query($sql);
}
// ---------------------------------------------------------------------------------------

echo '
<hr>
<input class="ninja" type="file" id="expediente_file" accept=".pdf,.png,.jpg,.jpeg,.zip,.rar"/>

<div class="expediente p-1">
    <div class="row">';

if( id_rol()){
    echo '<div class="col-md-4">
    <div class="expediente_caja puntero" onclick="nuevo_documento(\'' . $id . '\')">
        <div class="card-body p-4">
            <i class="material-icons">add</i>
            <div class="opciones_expediente_vacio p-1">Agregar nuevo documento</div>
        </div>
    </div>
</div>';
}
// ---------------------------------------------------------------------------------------
while ($fichero = mysqli_fetch_array($consulta)) {
    include 'item.php';
}
// ---------------------------------------------------------------------------------------
echo '</div>
</div>';
