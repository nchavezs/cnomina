<div class="p-2">
    <h4 class="negrita text-primary">Expediente de usuario</h4>
    <small class="text-muted">Expediente de <?php echo $usuario["nombre"] ?>.</small>
</div>

<div class="row">
    <div class="col-md-4">
        <?php
            $sql = "SELECT * FROM Movimiento WHERE url IS NOT NULL AND RFC = '".$id."'";
            $consulta = $conexion->query($sql);
            $total = mysqli_num_rows($consulta);
        ?>
        <div class="card animacion" onclick="expediente_archivos('movimiento', '<?php echo $id ?>')">
            <div class="card-body d-flex align-items-center justify-content-between negrita">
                <div class="d-flex align-items-center">
                    <i class="material-icons mr-2 text-info display-5">topic</i>
                    <span>1. Movimientos</span>
                </div>
                <span class="text-warning"><?php echo $total ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php
            $sql = "SELECT * FROM Pase WHERE url IS NOT NULL AND RFC = '".$id."'";
            $consulta = $conexion->query($sql);
            $total = mysqli_num_rows($consulta);
        ?>
        <div class="card animacion" onclick="expediente_archivos('pase', '<?php echo $id ?>')">
            <div class="card-body d-flex align-items-center justify-content-between negrita">
                <div class="d-flex align-items-center">
                    <i class="material-icons mr-2 text-info display-5">topic</i>
                    <span>2. Pases</span>
                </div>
                <span class="text-warning"><?php echo $total ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php
            $sql = "SELECT * FROM Vacacion WHERE url IS NOT NULL AND RFC = '".$id."'";
            $consulta = $conexion->query($sql);
            $total = mysqli_num_rows($consulta);
        ?>
        <div class="card animacion" onclick="expediente_archivos('vacacion', '<?php echo $id ?>')">
            <div class="card-body d-flex align-items-center justify-content-between negrita">
                <div class="d-flex align-items-center">
                    <i class="material-icons mr-2 text-info display-5">topic</i>
                    <span>3. Vacaciones</span>
                </div>
                <span class="text-warning"><?php echo $total ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php
            $sql = "SELECT * FROM Permiso WHERE url IS NOT NULL AND RFC = '".$id."'";
            $consulta = $conexion->query($sql);
            $total = mysqli_num_rows($consulta);
        ?>
        <div class="card animacion" onclick="expediente_archivos('permiso', '<?php echo $id ?>')">
            <div class="card-body d-flex align-items-center justify-content-between negrita">
                <div class="d-flex align-items-center">
                    <i class="material-icons mr-2 text-info display-5">topic</i>
                    <span>4. Licencias</span>
                </div>
                <span class="text-warning"><?php echo $total ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php
            $sql = "SELECT * FROM Descuento WHERE url IS NOT NULL AND RFC = '".$id."'";
            $consulta = $conexion->query($sql);
            $total = mysqli_num_rows($consulta);
        ?>
        <div class="card animacion" onclick="expediente_archivos('descuento', '<?php echo $id ?>')">
            <div class="card-body d-flex align-items-center justify-content-between negrita">
                <div class="d-flex align-items-center">
                    <i class="material-icons mr-2 text-info display-5">topic</i>
                    <span>5. Descuentos</span>
                </div>
                <span class="text-warning"><?php echo $total ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php
            $sql = "SELECT * FROM Gastos WHERE url IS NOT NULL AND RFC = '".$id."'";
            $consulta = $conexion->query($sql);
            $total = mysqli_num_rows($consulta);
        ?>
        <div class="card animacion" onclick="expediente_archivos('gastos', '<?php echo $id ?>')">
            <div class="card-body d-flex align-items-center justify-content-between negrita">
                <div class="d-flex align-items-center">
                    <i class="material-icons mr-2 text-info display-5">topic</i>
                    <span>6. Gastos médicos</span>
                </div>
                <span class="text-warning"><?php echo $total ?></span>
            </div>
        </div>
    </div>
</div>

<script>
    function expediente_archivos(tabla, id) {
        $.post("assets/php/expediente/archivos/"+tabla, {
            id
        }, function(data) {
            $(".ver_contenedor").html(data);
        });
    }
</script>