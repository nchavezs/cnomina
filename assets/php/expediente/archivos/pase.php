<?php
include "../../conexion.php";
$conexion = conexion();

$id = $_POST['id'];
$sql = "SELECT * FROM Pase WHERE url IS NOT NULL AND RFC = '" . $id . "'";
$consulta = $conexion->query($sql);
?>

<div class="p-2">
    <h4 class="negrita text-primary">Archivos</h4>
    <small class="text-muted">Historial de archivos en pases.</small>
</div>

<table style="width:100%" id="tb">
    <thead class="text-primary">
        <tr>
            <th>Elaboración</th>
            <th>Tipo de pase</th>
            <th>Fecha</th>
            <th>Observación</th>
            <th>Archivo</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = mysqli_fetch_array($consulta)) {
            $tipo = $row['categoria'] == 0 ? "PASE DE ENTRADA" : "PASE DE SALIDA";
            echo "<tr>";
            echo "<td>" . date("d/m/Y", strtotime($row['elaboracion'])) . "</td>";
            echo "<td>" . $tipo . "</td>";
            echo "<td>" . str_replace("-", "/", $row['fecha']) ." ". $row['hora'] . "</td>";
            echo "<td>" . $row['observacion'] . "</td>";
            echo "<td><a class='material-icons btn1' href='" . $row['url'] . "' target='_blank'>attachment</a></td>";
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

<script>
    $("#tb").DataTable();
</script>