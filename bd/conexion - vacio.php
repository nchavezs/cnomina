<?php 
    function conexion(){
        $servidor="sql213.epizy.com";
        $usuario="epiz_25855028";
        $password="libreta000";
        $bd="epiz_25855028_nomina";
        $conexion=mysqli_connect($servidor,$usuario,$password,$bd);
        return $conexion;
    }
 ?>