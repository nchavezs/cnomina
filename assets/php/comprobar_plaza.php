<?php
$success = true;

$html = '
<div class="p-2">
        <h4 class="negrita text-primary">Detalle de gastos económicos</h4>
        <small class="text-muted">Detalle de gastos económicos de </small>
    </div>
    <div class="card">
        <div class="card-body">
        </div>
    </div>
</div>    
';

$datos["html"] = $html;
$datos["success"] = $success;

echo json_encode($datos);