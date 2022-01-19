<?php
include "conexion.php";
$conexion = conexion();
$texto = $_POST['texto'];

$sql = "SELECT * FROM Usuario WHERE
categoria = 'user' AND
nombre LIKE '%" . $texto . "%'";

$consulta = mysqli_query($conexion, $sql);
if ($consulta) {
    while ($usuario = mysqli_fetch_array($consulta)) {
        $funcion_chat = "chat('" . $usuario["RFC"] . "', '".$usuario["nombre"]."')";
        echo '<div class="mensajeria_contacto" onclick="' . $funcion_chat . '">
                <img src="assets/img/user.png" alt="">
                <p>' . $usuario["nombre"] . '</p>
                <div class="mensajeria_fecha">2 Feb</div>
                <div class="mensajeria_noti">21</div>
            </div>';
    }
} else {
    echo 'SIN RESULTADOS';
}

mysqli_close($conexion);
