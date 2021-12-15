<?php

$ruta = '../archivos/';
if (!file_exists($ruta)) {
    mkdir($ruta, 0777, true);
}

$files = glob('./../archivos/*'); 
foreach($files as $file){ 
  if(is_file($file))
    unlink($file); 
}