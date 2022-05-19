<?php
$id = $_POST["id"];
$archivo = $_FILES['file']['name'];

$ruta = './../mensajes/'.$id;
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$ext = pathinfo($archivo, PATHINFO_EXTENSION);
$nombre = time() . '.' . $ext;
$path = $ruta . "/" . $nombre;
move_uploaded_file($_FILES['file']['tmp_name'], $path);
echo $nombre;
