<?php
include "conexion.php";
include "municipio.php";
require_once "../../vendor/autoload.php";
$css = file_get_contents("../css/reporte.css");
setlocale(LC_ALL, "spanish");

$conexion = conexion();
$hoy = date('d/m/Y');
$bandera = true;
$del = $_POST["del"];
$al = $_POST["al"];
$usuarios = $_POST["usuarios"];

$date1 = date("Y-m-d", strtotime(str_replace('/', '-', $del)));
$date2 = date("Y-m-d", strtotime(str_replace('/', '-', $al)));

foreach ($usuarios as $key => $item) {
    $usuarios[$key] = "'" . $item . "'";
}
$usuarios = implode(',', $usuarios);

$sql = "SELECT *,
(SELECT estado FROM Usuario WHERE RFC = Empleado.RFC) AS estado,
(SELECT nombre FROM Usuario WHERE RFC = Empleado.RFC) AS nombre,
(SELECT nombre FROM Puesto WHERE id_puesto = Empleado.id_puesto) AS puesto,
(SELECT nombre FROM Departamento WHERE id_departamento = (SELECT id_departamento FROM Puesto WHERE id_puesto = Empleado.id_puesto)) AS departamento,
(SELECT nombre FROM Trabajador WHERE id_trabajador = (SELECT id_trabajador FROM Puesto WHERE id_puesto = Empleado.id_puesto )) AS trabajador
FROM Empleado WHERE RFC IN (" . $usuarios . ")";

$query = $conexion->query($sql);
$total = mysqli_num_rows($query);

if ($query && $total > 0) {
    $mpdf = new \Mpdf\Mpdf();
    $mpdf->setHTMLFooter('<div class="footer"><p>Página {PAGENO} de {nb}</p></div>');
    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
    
    while ($usuario = mysqli_fetch_array($query)) {
        $RFC = $usuario["RFC"];
        $html = '<!DOCTYPE HTML>
        <html lang="en"><head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>
                    <header class="clearfix">
                        <div class="logo"></div>
                        <div id="company" class="clearfix">
                            <h2>MUNICIPIO DE ' . get_municipio() . ', GTO</h2>
                            <div><span>FECHA DE ELABORACIÓN</span> ' . $hoy . '</div>
                        </div>
                        <div id="project">
                            <div><span>NOMBRE</span> ' . $usuario["nombre"] . '</div>
                            <div><span>NO. DE EMPLEADO</span> ' . str_pad($usuario["id_empleado"], 5, '0', STR_PAD_LEFT) . '</div>
                            <div><span>FECHA DE INICIO</span> ' . $usuario["fechaRelLab"] . '</div>
                            <div><span>PUESTO</span> ' . ucwords(mb_strtolower($usuario["puesto"])) . '</div>
                            <div><span>DEPARTAMENTO</span> ' . ucwords(mb_strtolower($usuario["departamento"])) . '</div>
                            <div><span>CURP</span> ' . $usuario["CURP"] . '</div>
                            <div><span>RFC</span> ' . $RFC . '</div>
                            <div><span>ESTADO DEL EMPLEADO</span> ' . ucfirst($usuario["estado"]) . '</div>
                        </div>
                    </header>
                    <main>';
        // --------------------------------------------------------------------------------------------------------------------------

        $sql = "SELECT * FROM Beneficiario WHERE RFC = '" . $RFC . "'";
        $consulta = $conexion->query($sql);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="ok"><div class="icono2"></div><div class="titulo">BENEFICIARIOS</div></div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="desc">#</th>
                                            <th class="desc">Nombre de beneficiario</th>
                                            <th class="desc">Parentesco</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
            while ($beneficiario = mysqli_fetch_array($consulta)) {
                $html = $html . '<tr>
                                    <td class="desc">' . ++$contador . '</td>
                                    <td class="desc">' . ucwords(mb_strtolower($beneficiario["beneficiario"])) . '</td>
                                    <td class="desc">' . ucfirst(mb_strtolower($beneficiario["parentesco"])) . '</td>
                                </tr>';
            }
            $html = $html . '</tbody>
                        </table>';
        } else {
            $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">BENEFICIARIOS</div></div>';
        }
        // --------------------------------------------------------------------------------------------------------------------------

        $sql2 = "SELECT * FROM Movimiento WHERE RFC = '" . $RFC . "' AND fecha BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
        $consulta = $conexion->query($sql2);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="ok"><div class="icono2"></div><div class="titulo">MOVIMIENTOS</div></div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="desc">#</th>
                                            <th class="desc">Fecha</th>
                                            <th class="desc">Puesto actual</th>
                                            <th class="desc">Departamento actual</th>
                                            <th class="desc">Puesto anterior</th>
                                            <th class="desc">Departamento anterior</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
            while ($movimiento = mysqli_fetch_array($consulta)) {
                $html = $html . '<tr>
                                    <td class="desc">' . ++$contador . '</td>
                                    <td class="desc">' . date("d/m/Y", strtotime($movimiento['fecha'])) . '</td>
                                    <td class="desc">' . ucwords(mb_strtolower($movimiento["puesto"])) . '</td>
                                    <td class="desc">' . ucwords(mb_strtolower($movimiento["departamento"])) . '</td>
                                    <td class="desc">' . ucwords(mb_strtolower($movimiento["puestoAnterior"])) . '</td>
                                    <td class="desc">' . ucwords(mb_strtolower($movimiento["departamentoAnterior"])) . '</td>
                                </tr>';
            }
            $html = $html . '</tbody>
                        </table>';
        } else {
            $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">MOVIMIENTOS</div></div>';
        }
        // --------------------------------------------------------------------------------------------------------------------------
        $sql3 = "SELECT * FROM Descuento WHERE RFC = '" . $RFC . "'";
        $consulta = $conexion->query($sql3);

        if ($consulta && mysqli_num_rows($consulta) > 0 && ($date2 > $date1)) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="ok"><div class="icono2"></div><div class="titulo">DESCUENTOS</div></div>
                        <table>
                            <thead>
                                <tr>
                                    <th class="desc">#</th>
                                    <th class="desc">Fecha de descuento</th>
                                    <th class="desc">Motivo</th>
                                </tr>
                            </thead>
                            <tbody>';
            while ($descuento = mysqli_fetch_array($consulta)) {
                $fechas = explode(",", $descuento["fechas"]);
                foreach ($fechas as $fecha) {
                    $date = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));
                    if ($date >= $date1 && $date <= $date2) {
                        $html = $html . '<tr>
                                    <td class="desc">' . ++$contador . '</td>
                                    <td class="desc">' . strftime("%d de %B de %G", strtotime($date)) . '</td>
                                    <td class="desc">' . ucfirst($descuento["motivo"]) . '</td>
                                </tr>';
                    }
                }
            }
            $html = $html . '</tbody>
                    </table>';
        } else {
            $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">DESCUENTOS</div></div>';
        }
        // --------------------------------------------------------------------------------------------------------------------------
        $sql4 = "SELECT * FROM Vacacion WHERE RFC = '" . $RFC . "' AND al >= '" . $date1 . "' AND al <= '" . $date2 . "'";
        $consulta = $conexion->query($sql4);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="ok"><div class="icono2"></div><div class="titulo">VACACIONES</div></div>
                        <table>
                            <thead>
                                <tr>
                                    <th class="desc">#</th>
                                    <th class="desc">Días de vacaciones</th>
                                    <th class="desc">Período de vacaciones</th>
                                    <th class="desc">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>';
            while ($vacacion = mysqli_fetch_array($consulta)) {
                if (is_null($vacacion["descripcion"]) || trim($vacacion["descripcion"]) === "") {
                    $descripcion = "Sin descripción";
                } else {
                    $descripcion = $vacacion["descripcion"];
                }

                $html = $html . '<tr>
                            <td class="desc">' . ++$contador . '</td>
                            <td class="desc">' . $vacacion["dias"] . '</td>
                            <td class="desc">' . strftime("%d de %B de %G", strtotime($vacacion["del"])) . strftime(" al %d de %B de %G", strtotime($vacacion["al"])) . '</td>
                            <td class="desc">' . ucfirst($descripcion) . '</td>
                        </tr>';
            }
            $html = $html . '</tbody>
                    </table>';
        } else {
            $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">VACACIONES</div></div>';
        }
        // --------------------------------------------------------------------------------------------------------------------------
        $sql5 = "SELECT * FROM Permiso WHERE RFC = '" . $RFC . "' AND al >= '" . $date1 . "' AND al <= '" . $date2 . "' AND categoria = 0";
        $consulta = $conexion->query($sql5);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="ok"><div class="icono2"></div><div class="titulo">PERMISOS CON GOCE DE SUELDO</div></div>
                        <table>
                            <thead>
                                <tr>
                                    <th class="desc">#</th>
                                    <th class="desc">Días de permiso</th>
                                    <th class="desc">Período de permiso</th>
                                    <th class="desc">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>';
            while ($con_goce = mysqli_fetch_array($consulta)) {
                if (is_null($con_goce["descripcion"]) || trim($con_goce["descripcion"]) === "") {
                    $descripcion = "Sin descripción";
                } else {
                    $descripcion = $con_goce["descripcion"];
                }

                $html = $html . '<tr>
                            <td class="desc">' . ++$contador . '</td>
                            <td class="desc">' . $con_goce["dias"] . '</td>
                            <td class="desc">' . strftime("%d de %B de %G", strtotime($con_goce["del"])) . strftime(" al %d de %B de %G", strtotime($con_goce["al"])) . '</td>
                            <td class="desc">' . ucfirst($descripcion) . '</td>
                        </tr>';
            }
            $html = $html . '</tbody>
                    </table>';
        } else {
            $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">PERMISOS CON GOCE DE SUELDO</div></div>';
        }
        // --------------------------------------------------------------------------------------------------------------------------
        $sql6 = "SELECT * FROM Permiso WHERE RFC = '" . $RFC . "' AND al >= '" . $date1 . "' AND al <= '" . $date2 . "' AND categoria = 1";
        
        $consulta = $conexion->query($sql6);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="ok"><div class="icono2"></div><div class="titulo">PERMISOS SIN GOCE DE SUELDO</div></div>
                        <table>
                            <thead>
                                <tr>
                                    <th class="desc">#</th>
                                    <th class="desc">Días de permiso</th>
                                    <th class="desc">Período de permiso</th>
                                    <th class="desc">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>';
            while ($sin_goce = mysqli_fetch_array($consulta)) {
                if (is_null($sin_goce["descripcion"]) || trim($sin_goce["descripcion"]) === "") {
                    $descripcion = "Sin descripción";
                } else {
                    $descripcion = $sin_goce["descripcion"];
                }

                $html = $html . '<tr>
                            <td class="desc">' . ++$contador . '</td>
                            <td class="desc">' . $sin_goce["dias"] . '</td>
                            <td class="desc">' . strftime("%d de %B de %G", strtotime($sin_goce["del"])) . strftime(" al %d de %B de %G", strtotime($sin_goce["al"])) . '</td>
                            <td class="desc">' . ucfirst($descripcion) . '</td>
                        </tr>';
            }
            $html = $html . '</tbody>
                    </table>';
        } else {
            $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">PERMISOS SIN GOCE DE SUELDO</div></div>';
        }
        // --------------------------------------------------------------------------------------------------------------------------
        $sql8 = "SELECT * FROM Pase WHERE RFC = '" . $RFC . "' AND fecha >= '" . $date1 . "' AND fecha <= '" . $date2 . "'";
        
        $consulta = $conexion->query($sql8);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="ok"><div class="icono2"></div><div class="titulo">PASES</div></div>
                        <table>
                            <thead>
                                <tr>
                                    <th class="desc">#</th>
                                    <th class="desc">Fecha</th>
                                    <th class="desc">Hora</th>
                                    <th class="desc">Categoría</th>
                                    <th class="desc">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>';
            while ($pase = mysqli_fetch_array($consulta)) {
                $categoria = "ENTRADA";
                if ($pase["categoria"] == 1) {
                    $categoria = "SALIDA";
                }

                $observacion = "Sin descripción";
                if (trim($pase["observacion"]) !== "") {
                    $observacion = ucfirst(mb_strtolower($pase["observacion"]));
                }

                $html = $html . '<tr>
                            <td class="desc">' . ++$contador . '</td>
                            <td class="desc">' . date("d/m/Y", strtotime($pase["fecha"])) . '</td>
                            <td class="desc">' . $pase["hora"] . '</td>
                            <td class="desc">' . $categoria . '</td>
                            <td class="desc">' . $observacion . '</td>
                        </tr>';
            }
            $html = $html . '</tbody>
                    </table>';
        } else {
            $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">PASES</div></div>';
        }
        // --------------------------------------------------------------------------------------------------------------------------

        $sql7 = "SELECT * FROM Baja WHERE RFC = '" . $RFC . "'";
        $consulta = $conexion->query($sql7);
        if ($consulta && (mysqli_num_rows($consulta) > 0)) {
            $html = $html . '<div class="ok"><div class="icono2"></div><div class="titulo">BAJA</div></div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="desc">Fecha de baja</th>
                                            <th class="desc">Descripción</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
            while ($baja = mysqli_fetch_array($consulta)) {
                $html = $html . '<tr>
                                    <td class="desc">' . strftime("%d de %B de %G", strtotime($baja["fecha"])) . '</td>
                                    <td class="desc">' . ucfirst($baja["razon"]) . '</td>
                                </tr>';
            }
            $html = $html . '</tbody>
                            </table>';
        }
        // --------------------------------------------------------------------------------------------------------------------------

        $html = $html . '</main></body></html>';
        $html = utf8_decode($html);
        $mpdf->WriteHTML(utf8_encode($html), \Mpdf\HTMLParserMode::HTML_BODY);

        if (--$total > 0) {
            $mpdf->AddPage();
        }
    }
    if ($bandera) {
        $nombre = "reporte_".time().".pdf";
        $mpdf->Output('../archivos/'.$nombre, 'F');
        echo 'assets/archivos/'.$nombre;
    } else {
        echo 0;
    }
} else {
    echo 0;
}

$conexion->close();
