<?php 
    function conexion(){
        $servidor="localhost";
        $usuario="u612058578_admin";
        $password="Warfram3";
        $bd="u612058578_nomin";
        $conexion=mysqli_connect($servidor,$usuario,$password,$bd);
        return $conexion;
    }
 ?>