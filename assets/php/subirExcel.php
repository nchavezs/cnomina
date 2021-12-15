<?php
$ruta = '../archivos/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$archivo = $_FILES['file']['name'];
$ext = pathinfo($archivo, PATHINFO_EXTENSION);
if ($ext === 'xlsx') {
    $ruta = $ruta . '/empleados.xlsx';
    move_uploaded_file($_FILES['file']['tmp_name'], $ruta);
    echo 1;
} else {
    echo 0;
}
