<?php
session_start();
include 'conexion.php';
$conexion = conexion();

$html = '';
$sql = "SELECT * FROM Usuario WHERE categoria = 'admin'";
$consulta = $conexion->query($sql);

while($usuario = mysqli_fetch_array($consulta)){
    $html = $html. '
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="d-flex align-items-center mr-4">
                        <img src="assets/img/user.png" class="rounded-circle" width="60px" height="60px" alt="">
                    </div>
                    <div class="w-100">
                        <p class="negrita mb-1">'.$usuario["nombre"].'</p>
                        <p class="text-secondary m-0">'.$usuario["email"].'</p>
                        <p class="">Administrador</p>
                       
                        <div class="text-right w-100">
                            <button class="btn btn-sm btn-primary">Editar</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    ';
}

$conexion->close();
echo $html;

