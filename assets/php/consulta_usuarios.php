<?php
session_start();
include 'conexion.php';
include 'rol.php';
$conexion = conexion();

$html = '';
$sql = "SELECT *,
(SELECT nombre FROM Rol WHERE id_rol = (SELECT id_rol FROM Rol_Usuario WHERE RFC = Usuario.RFC)) AS rol 
FROM Usuario WHERE categoria = 'admin' AND RFC <> 'nomina'";
$consulta = $conexion->query($sql);



while($usuario = mysqli_fetch_array($consulta)){
    
    if($usuario["urlFoto"] == null){
        $usuario["urlFoto"] = "assets/img/user.png";
    }
    
    $estado = '<div class="estado_usuario centrado"><i class="material-icons centrado">done</i> </div>';
    if($usuario["estado"] == "baja"){
        $estado = '<div class="estado_usuario centrado baja_usuario"><i class="material-icons">close</i> </div>';
    }

    $bloqueo = "bloqueo()";
    if(in_array( 15, rol())){
        $bloqueo = 'editar_usuario(\''.$usuario["RFC"].'\')';
    }   

    $html = $html. '
    <div class="col-xl-4 col-md-6">
        <div class="card caja_usuario" onclick="'.$bloqueo.'">
            <div class="card-body">
                <div class="d-flex">
                    <div class="p-1 mr-4 foto_usuario">
                        <img src="'.$usuario["urlFoto"].'" alt="">
                    </div>
                    <div class="contenido_usuario">
                        <p class="negrita mb-1">'.$usuario["nombre"].'</p>
                        <p class="text-secondary m-0">'.$usuario["email"].'</p>
                        <small class="text-secondary negrita">'.$usuario["rol"].'</small>'.
                        $estado.   
                    '</div>
                </div>
            </div>
        </div>
    </div>
    ';
}

$conexion->close();
echo $html;

