<?php 
    function conexion(){
        $servidor="localhost";
        $usuario="u214291592_admin";
        $password="W4rfr4m3";
        $bd="u214291592_consultanomina";
        $conexion=mysqli_connect($servidor,$usuario,$password,$bd);
        return $conexion;
    }
 ?>