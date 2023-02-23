<?php
include "conexion.php";
$conexion = conexion();
$id = $_POST['id'];

session_start();
include "rol.php";

$ano = $_SESSION["ano"];
$sql = "SELECT id_plaza FROM Plaza WHERE RFC = '".$id."' AND YEAR(elaboracion) = ".$ano." ORDER BY elaboracion DESC LIMIT 1";
$query = $conexion->query($sql);
$plaza = "Sin plaza";
if($query && mysqli_num_rows($query) > 0){
    $plaza = mysqli_fetch_row($query);
    $plaza = $plaza[0];
}   


$sql = "SELECT *,
(SELECT nombre FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto) AS puesto,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT Puesto.id_departamento FROM Puesto WHERE Puesto.id_puesto = Empleado.id_puesto)) AS departamento,
(SELECT nombre FROM Trabajador WHERE id_trabajador = (SELECT id_trabajador FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS tipoTrabajador
FROM Usuario LEFT JOIN Empleado ON Usuario.RFC = Empleado.RFC WHERE Usuario.RFC = '" . $id . "' ";
$consulta = $conexion->query($sql);
$usuario = mysqli_fetch_array($consulta);

if($usuario["urlFoto"] == null)
$usuario["urlFoto"] = "assets/img/user.svg";

if ($usuario["email"] == null) {
    $usuario["email"] = "No configurado";
}

if ($usuario["telefono"] == null) {
    $usuario["telefono"] = "No configurado";
}

if ($usuario["afiliacion"] == null) {
    $usuario["afiliacion"] = "No configurado";
}

if ($usuario["banca"] == null) {
    $usuario["banca"] = "No configurado";
}

if ($usuario["domicilio"] == null) {
    $usuario["domicilio"] = "No configurado";
}

if ($usuario["postal"] == null) {
    $usuario["postal"] = "No configurado";
}


echo '<div class="p-2">
        <h4 class="negrita text-primary">Perfil de usuario</h4>
        <small class="text-muted">Datos generales del empleado.</small>
    </div>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="profile">
                        <div>
                            <img class="foto" src="' . $usuario["urlFoto"] . '" alt="">
                            <div class="px-3">
                                <p class="text-primary">' . $usuario["nombre"] . '</p>
                                <small class="text-muted">' . $usuario["puesto"] . '</small>

                                <div class="centrado profile_caja">
                                    <p># Empleado '.str_pad($usuario["id_empleado"], 5, '0', STR_PAD_LEFT).'</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-block centrado">';

                        if(in_array(6 , rol())){
                            echo '<button class="btn btn3 btn-primary foto_usuario"><i class="material-icons">wallpaper</i> Cambiar foto</button>';
                        }else{
                            echo '<button onclick="bloqueo();" class="btn btn3 btn-primary"><i class="material-icons">wallpaper</i> Cambiar foto</button>';
                        }

                        if(in_array(8, rol())){
                            echo '<button onclick="password(\''.$usuario["RFC"].'\');" class="btn btn3 btn-secondary"><i class="material-icons">vpn_key</i> Restablecer contraseña</button>';
                        }else{
                            echo '<button onclick="bloqueo();" class="btn btn3 btn-secondary"><i class="material-icons">vpn_key</i> Restablecer contraseña</button>';
                        }
                           
                        
                        echo '
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="profile">

                        <div class="d-block">
                            <small class="text-muted">Departamento</small>
                            <p class="text-primary">' . $usuario["departamento"] . '</p>
                        </div>

                        <div class="d-block">
                            <small class="text-muted">Categoría</small>
                            <p class="text-primary">' . $usuario["tipoTrabajador"] . '</p>
                        </div>

                        <div class="d-block">
                            <small class="text-muted">Estado</small>
                            <p class="text-primary">' . $usuario["estado"] . '</p>
                        </div>

                        <div class="d-block">
                            <small class="text-muted">Fecha de alta</small>
                            <p class="text-primary">' . $usuario["fechaRelLab"] . '</p>
                        </div>

                        <div class="d-block">
                            <small class="text-muted">ID de plaza</small>
                            <p class="text-primary">' . $plaza . '</p>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="profile">

                        <div class="d-block">
                            <small class="text-muted">RFC</small>
                            <p class="text-primary">' . $usuario["RFC"] . '</p>
                        </div>

                        <div class="d-block">
                            <small class="text-muted">CURP</small>
                            <p class="text-primary">' . $usuario["CURP"] . '</p>
                        </div>

                        <div class="d-block">
                            <small class="text-muted">Email</small>
                            <p class="text-primary">' . $usuario["email"] . '</p>
                        </div>

                        <div class="d-block">
                            <small class="text-muted">Teléfono</small>
                            <p class="text-primary">' . $usuario["telefono"] . '</p>
                        </div>

                        <div class="d-block">
                            <small class="text-muted">Domicilio</small>
                            <p class="text-primary">' . $usuario["domicilio"] . '</p>
                        </div>

                        <div class="d-block">
                            <small class="text-muted">Código Postal</small>
                            <p class="text-primary">' . $usuario["postal"] . '</p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card mt-4">
            <div class="card-body">
                <div class="profile">

                    <div class="d-block">
                        <small class="text-muted">Cuenta bancaria</small>
                        <p class="text-primary">' . $usuario["banca"] . '</p>
                    </div>

                    <div class="d-block">
                        <small class="text-muted">Número de afiliación</small>
                        <p class="text-primary">' . $usuario["afiliacion"] . '</p>
                    </div>
                   
                </div>
            </div>
        </div>


        </div>
    </div>';
