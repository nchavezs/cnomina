<?php
include "../../conexion.php";
include "./funciones.php";
$conexion = conexion();

$id = $_POST['id'];
$sql = "SELECT * FROM Permiso WHERE url IS NOT NULL AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
?>

<div class="p-2">
    <h4 class="negrita text-primary">Archivos</h4>
    <small class="text-muted">Historial de archivos en licencias.</small>
</div>

<table class="adp-hide" style="width:100%" id="tb">
    <thead class="text-primary">
        <tr>
            <th>Elaboración</th>
            <th>Tipo de permiso</th>
            <th>Periodo</th>
            <th>Descripción</th>
            <th>Archivo</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = mysqli_fetch_array($consulta)) {
            $tipo = $row['categoria'] == 0 ? "CON GOCE DE SUELDO" : "SIN GOCE DE SUELDO";
            echo "<tr>";
            echo "<td>" . date("d/m/Y", strtotime($row['elaboracion'])) . "</td>";
            echo "<td>" . $tipo . "</td>";
            echo "<td>" .date("d/m/Y", strtotime($row['del']))." al ".date("d/m/Y", strtotime($row['al'])) . "</td>";
            echo "<td>" . $row['descripcion'] . "</td>";
            echo "<td><a class='material-icons btn1' href='" . $row['url'] . "' target='_blank'>download</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<div class="pie">
    <div class="btn btn-secondary btn-sm" onclick="verExpediente('<?php echo $id ?>');">
        <i class="material-icons">keyboard_backspace</i> Regresar
    </div>
</div>