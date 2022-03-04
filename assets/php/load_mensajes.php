<?php
include "conexion.php";
$conexion = conexion();

$sql = "SELECT * FROM Correos";
$resultado1 = $conexion->query($sql);
$html = "";
$total = mysqli_num_rows($resultado1);

if ($total > 0) {
    $anterior = null;
    $key = 0;
    while ($correo = mysqli_fetch_array($resultado1)) {
        $sql = "SELECT nombre FROM Usuario WHERE RFC ='" . $correo['RFC'] . "'";
        $nombre = mysqli_fetch_row($conexion->query($sql));

        if ($key == 0) {
            $html .= '<div class="fade_rule"><span>' . strftime("%e de %B de %Y", strtotime($correo["elaboracion"])) . '</span></div>';
        }
        if ($key > 0) {
            if (date('d/m/Y', strtotime($correo["elaboracion"])) != date('d/m/Y', strtotime($anterior["elaboracion"])))
                $html .= '<div class="fade_rule"><span>' . strftime("%e de %B de %Y", strtotime($correo["elaboracion"])) . '</span></div>';
        }


        $html .= '<div class="chat_msg">
                                <img class="chat_img" src="assets/img/user.png" alt="">
                                <div class="chat_text">
                                    <h5>' . $nombre[0] . ' <span class="text-muted">' . date('h:i A', strtotime($correo['elaboracion'])) . '</span></h5>
                                    <small>' . $correo['mensaje'] . '</small>
                                </div>
                            </div>';
        $anterior = $correo;
        $key++;
    }
} else {
    $html = '<div class="chat_vacio text-left">
                            <h3>Envíanos un mensaje</h3>
                            <small class="muted">¡Estamos aquí para ayudarte!, cualquier duda o sugerencia envíanos tus comentarios.</small>
                            <div class="chat_contacto">
                                <i class="material-icons">message</i>
                                <small class="muted">O escríbenos a <a href = "mailto: contacto@consultanomina.com">contacto@consultanomina.com</a></small>
                            </div>
                        </div>';
}

$datos["total"] = $total;
$datos["html"] = $html;

echo json_encode($datos);
$conexion->close();
