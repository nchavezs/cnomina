<?php 
    function conexion(){
        $servidor="localhost";
        $usuario="root";
        $password="";
        $bd="consultanomina";
        $conexion=mysqli_connect($servidor,$usuario,$password,$bd);
        $conexion->set_charset("utf8");
        return $conexion;
    }
 ?>