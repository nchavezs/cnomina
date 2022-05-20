<?php
session_start();
setlocale(LC_ALL, "spanish");
include "conexion.php";
$conexion = conexion();
$texto = trim($_POST['texto']);
$myid = $_SESSION['usuario'];

$sql = "SELECT Usuario.*,
    (SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto, 
    (SELECT elaboracion FROM Mensaje WHERE receptor = '" . $myid . "' AND emisor = Usuario.RFC ORDER BY Mensaje.elaboracion DESC LIMIT 1) AS ultimo 
    FROM Usuario LEFT JOIN Empleado ON Usuario.RFC = Empleado.RFC WHERE 
    nombre LIKE '%" . $texto . "%' AND 
    Usuario.RFC <> '".$myid."' 
    ORDER BY ultimo DESC, RFC
";

$datos["total"] = 0;
$datos["html"] = "";

$consulta = $conexion->query($sql);
$total = mysqli_num_rows($consulta);

if ($consulta && $total > 0) {
    while ($usuario = mysqli_fetch_array($consulta)) {
        $sql = "SELECT * FROM Mensaje WHERE
            receptor = '" . $myid . "' AND
            emisor = '" . $usuario["RFC"] . "' AND
            estado = 0
        ";
        $consulta2 = $conexion->query($sql);
        $numero = mysqli_num_rows($consulta2);
        $datos["total"] = $datos["total"] + $numero;

        $sql = "SELECT * FROM Mensaje WHERE
            receptor = '" . $myid . "' AND
            emisor = '" . $usuario["RFC"] . "'
            ORDER BY elaboracion DESC
        ";
        $consulta3 = $conexion->query($sql);
        $fecha = "";
        if ($consulta3 && mysqli_num_rows($consulta3) > 0) {
            $mensaje = mysqli_fetch_array($consulta3);
            $fecha = strftime("%d %b", strtotime($mensaje["elaboracion"]));
        }

        $funcion_chat = "mostrar_chat('" . $usuario["RFC"] . "', '" . $usuario["nombre"] . "')";
        $datos["html"] = $datos["html"] . '<div id= "'.$usuario["RFC"].'" class="mensajeria_contacto" onclick="' . $funcion_chat . '">
                <img src="assets/img/user.png" alt="">
                <div>
                <p>' . $usuario["nombre"] . '</p>
                <small class="text-muted">' . $usuario["puesto"] . '</small>
                </div>
                <span class="mensajeria_fecha text-muted">' . $fecha . '</span>';
        if ($numero > 0) {
            $datos["html"] = $datos["html"] . '<span class="mensajeria_noti">' . $numero . '</span>';
        }

        $datos["html"] = $datos["html"] . '</div>';
    }
} else {
    $datos["html"] = '<div class="mensajeria_resultados">No se encontró ningún contacto</div> ';
}

$conexion->close();

echo json_encode($datos);