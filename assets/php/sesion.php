<?php
if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
    include 'conexion.php';
    $conexion = conexion();

    $user = mysqli_real_escape_string($conexion, trim($_POST['usuario']));
    $password = mysqli_real_escape_string($conexion, $_POST['contrasenia']);
    $sql = "SELECT * FROM Usuario WHERE RFC = '" . $user . "' AND contrasenia = '" . $password . "' AND estado ='alta'";
    $consulta = $conexion->query($sql);

    if ($consulta && mysqli_num_rows($consulta) == 1) {
        $res = mysqli_fetch_array($consulta);
        
        session_start();
        $_SESSION['usuario'] = $res["RFC"];
        $_SESSION['categoria'] = $res["categoria"];
        $_SESSION['nombre'] = $res["nombre"];
        $_SESSION['email'] = $res["email"];
        $_SESSION['foto'] = $res["urlFoto"];
        $_SESSION['telefono'] = $res["telefono"];

        if ($res["categoria"] === 'user') {
            echo './tablas';
        } else if ($res["categoria"] === 'admin') {
            echo './periodo';
        } else {
            echo 0;
        }
    } else {
        echo 0;
    }
    $conexion->close();
} else {
    echo 0;
}
