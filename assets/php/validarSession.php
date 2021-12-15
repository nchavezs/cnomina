<?php
if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
    include 'conexion.php';
    $conexion = conexion();

    $user = mysqli_real_escape_string($conexion, trim($_POST['usuario']));
    $password = mysqli_real_escape_string($conexion, $_POST['contrasenia']);
    $sql = "SELECT * FROM Usuario WHERE RFC = '" . $user . "' AND contrasenia = '" . $password . "'";
    $consulta = mysqli_query($conexion, $sql);

    if ($consulta && mysqli_num_rows($consulta) == 1) {
        $categoria = mysqli_fetch_array($consulta);
        session_start();
        $_SESSION['usuario'] = $categoria[8];
        $_SESSION['categoria'] = $categoria[1];
        $_SESSION['nombre'] = $categoria[6];

        if ($categoria[1] === 'user') {
            header('location:../../tablas');
        } else if ($categoria[1] === 'admin') {
            header('location:../../registrar');
        } else {
            echo 0;
        }
    } else {
        header("location: /");
    }
    mysqli_close($conexion);
} else {
    header("location: /");
}
