<?php
session_start();
include "conexion.php";
$conexion = conexion();
$id = $_SESSION['usuario'];

$sql1 = "SELECT * FROM Chat ORDER BY id_chat DESC";
$resultado1 = mysqli_query($conexion, $sql1);

if (mysqli_num_rows($resultado1) == 0) {
    echo '<div class="vacia">
				<i class="material-icons btn2">sms_failed</i>
				<h1>Sin mensajes</h1>
			</div>';
} else {
    while ($res1 = mysqli_fetch_row($resultado1)) {
        $sql = "SELECT nombre FROM Usuario WHERE RFC = '" . $res1[1] . "'";
        $nombre = mysqli_fetch_row(mysqli_query($conexion, $sql));

        $sql3 = "SELECT urlFoto FROM Usuario WHERE RFC = '" . $res1[1] . "'";
        $dir = mysqli_fetch_row(mysqli_query($conexion, $sql3));

        if (is_null($dir[0])) {
            $dir[0] = "assets/img/user.svg";
        }

        echo '<div class="card">
				<div class="msn-titulo">
					<div class="row">
						<div class="col-2 foto-caja">
							<img class="chat-foto" src="' . $dir[0] . '">
						</div>
						<div class="col-6">
							<h5>' . ucfirst(strtolower($res1[2])) . '</h5>
							<h7><i class="material-icons">account_box</i>' . $nombre[0] . '</h7>
							<h7><i class="material-icons">date_range</i>' . $res1[3] . '</h7>
						</div>
						<div class="col-4 msn-mostrar">
							<button id="' . $res1[0] . 'x' . '" class="btn-mostrar mostrar-chat">Mostrar</button>
						</div>
					</div>
				</div>
				<div id="' . $res1[0] . '" class="msn-chat">
			<div id="' . $res1[0] . 'z' . '" class="msn-contenido">';
        $sql2 = "SELECT * FROM Mensaje WHERE id_chat = " . $res1[0];
        $resultado2 = mysqli_query($conexion, $sql2);
        while ($res2 = mysqli_fetch_row($resultado2)) {
            $sql5 = "SELECT nombre FROM Usuario WHERE RFC = '" . $res2[4] . "'";
            $name = mysqli_fetch_row(mysqli_query($conexion, $sql5));
            $name = $name[0];

            echo '<div class="msn-mensaje ';
            if ($id == $res2[4]) {
                echo 'msn-derecha">';
            } else {
                echo 'msn-izquierda">
                <h6 class="text-warning">' . $name . '</h6>';
            }

            echo '<h7>' . $res2[2] . '</h7>
					  <h5 class="msn-fecha">' . substr($res2[3], 10) . '</h5>
				  </div>';
            if (!is_null($res2[7])) {
                echo '<div class="msn-mensaje ';
                if ($id == $res2[4]) {
                    $clase = 'msn-derecha';
                    echo 'msn-derecha';
                } else {
                    $clase = 'msn-izquierda';
                    echo 'msn-izquierda';
                }
                $descarga = explode("/", $res2[7]);
                echo '"><a class="' . $clase . '"href="' . $res2[7] . '" download><i class="material-icons">attach_file</i> ' . $descarga[3] . ' </a>
							<h5 class="msn-fecha">' . substr($res2[3], 10) . '</h5>
						</div>';
            }
        }
        echo '</div>
					<form id="' . $res1[0] . 'y' . '" class="msn-responder">
						<input type="text" name="mensaje" class="msn-input" placeholder="Escribe tu mensaje ..." autocomplete="off">
						<input type="hidden" name="receptor" value="' . $res1[1] . '">
						<input type="hidden" name="chat" value="' . $res1[0] . '">
						<button type="submit" class="msn-enviar" ><i class="material-icons enviar-icono">send</i></button>
					</form>
				</div>
			</div>';
    }
}

mysqli_close($conexion);