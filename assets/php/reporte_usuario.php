<?php
include "conexion.php";
include "municipio.php";
require_once "../../vendor/autoload.php";
setlocale(LC_ALL, "spanish");
use Knp\Snappy\Pdf;

// $path = "http://localhost/cnomina/";
$path = "https://consultanominacomonfort.com/";

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
    $snappy = new Pdf($path.'assets/php/wkhtmltopdf-amd64');
    $snappy->setOptions([
        "enable-local-file-access" => true,
        // "disable-smart-shrinking" => true,
        'footer-center' => 'Página [page]',
        'footer-font-size' => 6,
    ]);

    $html = '<!DOCTYPE HTML><html lang="en">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <link href="' . $path . 'assets/css/reporte.css" rel="stylesheet" />
                </head>
                
                <body>';

    while ($usuario = mysqli_fetch_array($query)) {
        $RFC = $usuario["RFC"];
        
                   $html = $html.'<div class="page">
                   <div class="cabecera">
                        <div class="logo"></div>
                        <div class="titulos">
                            <h2>MUNICIPIO DE ' . get_municipio() . ', GTO</h2>
                            <div><span>FECHA DE ELABORACIÓN</span> ' . $hoy . '</div>
                        </div>
                        <table class="datos">
                            <tr>
                                <td class="negrita">Nombre</td>
                                <td> ' . $usuario["nombre"] . '</td>
                            </tr>
                            <tr>
                                <td class="negrita">No. de empleado</td>
                                <td>' . str_pad($usuario["id_empleado"], 5, '0', STR_PAD_LEFT) . '</td>
                            </tr>
                            <tr>
                                <td class="negrita">Categoría</td>
                                <td>' .  ucfirst(mb_strtolower($usuario["trabajador"])) . '</td>
                            </tr>
                            <tr>
                                <td class="negrita">Puesto</td>
                                <td> ' . ucwords(mb_strtolower($usuario["puesto"])) . '</td>
                            </tr>
                            <tr>
                                <td class="negrita">Departamento</td>
                                <td> ' . ucwords(mb_strtolower($usuario["departamento"])) . '</td>
                            </tr>
                            <tr>
                                <td class="negrita">CURP</td>
                                <td> ' . $usuario["CURP"] . '</td>
                            </tr>
                            <tr>
                                <td class="negrita">RFC</td>
                                <td>' . $RFC . '</td>
                            </tr>
                            <tr>
                                <td class="negrita">Estado</td>
                                <td>' . ucfirst($usuario["estado"]) . '</td>
                            </tr>
                        </table>
                    </div>
                    <div class="cuerpo">';
        // --------------------------------------------------------------------------------------------------------------------------
        $sql0 = "SELECT * FROM Historial WHERE RFC = '" . $RFC . "'";
        $consulta = $conexion->query($sql0);
        if ($consulta && (mysqli_num_rows($consulta) > 0)) {
            $html = $html . '<div class="titulo ok">HISTORIAL</div>
                                <table class="tabla">
                                <tr>
                                    <th class="desc">Tipo</th>
                                    <th class="desc">Fecha</th>
                                    <th class="desc">Descripción</th>
                                </tr>';
            while ($historial = mysqli_fetch_array($consulta)) {
                $html = $html . '<tr>
                                    <td class="desc">' . ($historial["tipo"]) . '</td>
                                    <td class="desc">' . date("d/m/Y", strtotime($historial["fecha"])) . '</td>
                                    <td class="desc">' . ucfirst(mb_strtolower($historial["descripcion"])) . '</td>
                                </tr>';
            }
            $html = $html . '</table>';
        }
        // --------------------------------------------------------------------------------------------------------------------------

        $sql = "SELECT * FROM Beneficiario WHERE RFC = '" . $RFC . "'";
        $consulta = $conexion->query($sql);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="titulo ok">BENEFICIARIOS</div>
                                <table class="tabla">
                                    <tr>
                                        <th class="desc">#</th>
                                        <th class="desc">Nombre de beneficiario</th>
                                        <th class="desc">Parentesco</th>
                                    </tr>
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
            $html = $html . '<div class="titulo error">BENEFICIARIOS</div>';
        }
        // --------------------------------------------------------------------------------------------------------------------------

        $sql2 = "SELECT * FROM Movimiento WHERE RFC = '" . $RFC . "' AND fecha BETWEEN '" . $date1 . "' AND '" . $date2 . "'";
        $consulta = $conexion->query($sql2);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="titulo ok">MOVIMIENTOS</div>
                                <table class="tabla">
                                    <tr>
                                        <th class="desc">#</th>
                                        <th class="desc">Fecha</th>
                                        <th class="desc">Puesto actual</th>
                                        <th class="desc">Departamento actual</th>
                                        <th class="desc">Puesto anterior</th>
                                        <th class="desc">Departamento anterior</th>
                                    </tr>
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
            $html = $html . '<div class="titulo error">MOVIMIENTOS</div>';
        }
        // --------------------------------------------------------------------------------------------------------------------------
        $sql3 = "SELECT * FROM Descuento WHERE RFC = '" . $RFC . "'";
        $consulta = $conexion->query($sql3);

        $x = true;
        $descuentos = 0;
        $contador = 0;
        
        if ($consulta && mysqli_num_rows($consulta) > 0 ) {
            while ($descuento = mysqli_fetch_array($consulta)) {
                $fechas = explode(",", $descuento["fechas"]);
                foreach ($fechas as $fecha) {
                    $date = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));
                    if ($date >= $date1 && $date <= $date2) {
                        $descuentos++;
                        $bandera = true;
                       
                        if($x){
                            $x = false;
                            $html = $html . '<div class="titulo ok">DESCUENTOS</div>
                                        <table class="tabla">
                                            <tr>
                                                <th class="desc">#</th>
                                                <th class="desc">Fecha de descuento</th>
                                                <th class="desc">Motivo</th>
                                            </tr>
                                            <tbody>';
                        }
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
        } 
        
        if($descuentos == 0){
            $html = $html . '<div class="titulo error">DESCUENTOS</div>';
        }
        
        // --------------------------------------------------------------------------------------------------------------------------
        $sql4 = "SELECT * FROM Vacacion WHERE 
        RFC = '" . $RFC . "' AND 
        (del BETWEEN '".$date1."' AND '".$date2."' OR al BETWEEN '".$date1."' AND '".$date2."')";

        $consulta = $conexion->query($sql4);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="titulo ok">VACACIONES</div>
                        <table class="tabla">
                            <tr>
                                <th class="desc">#</th>
                                <th class="desc">Días de vacaciones</th>
                                <th class="desc">Período de vacaciones</th>
                                <th class="desc">Descripción</th>
                            </tr>
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
            $html = $html . '<div class="titulo error">VACACIONES</div>';
        }
        // --------------------------------------------------------------------------------------------------------------------------
        $sql5 = "SELECT * FROM Permiso WHERE 
        RFC = '" . $RFC . "' AND 
        (del BETWEEN '".$date1."' AND '".$date2."' OR al BETWEEN '".$date1."' AND '".$date2."') AND
        categoria = 0";

        $consulta = $conexion->query($sql5);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="titulo ok">PERMISOS CON GOCE DE SUELDO</div>
                        <table class="tabla">
                            <tr>
                                <th class="desc">#</th>
                                <th class="desc">Días de permiso</th>
                                <th class="desc">Período de permiso</th>
                                <th class="desc">Descripción</th>
                            </tr>
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
            $html = $html . '<div class="titulo error">PERMISOS CON GOCE DE SUELDO</div>';
        }
        // --------------------------------------------------------------------------------------------------------------------------
        $sql6 = "SELECT * FROM Permiso WHERE 
        RFC = '" . $RFC . "' AND 
        (del BETWEEN '".$date1."' AND '".$date2."' OR al BETWEEN '".$date1."' AND '".$date2."') AND
        categoria = 1";

        $consulta = $conexion->query($sql6);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="titulo ok">PERMISOS SIN GOCE DE SUELDO</div>
                        <table class="tabla">
                            <tr>
                                <th class="desc">#</th>
                                <th class="desc">Días de permiso</th>
                                <th class="desc">Período de permiso</th>
                                <th class="desc">Descripción</th>
                            </tr>
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
            $html = $html . '<div class="titulo error">PERMISOS SIN GOCE DE SUELDO</div>';
        }
        // --------------------------------------------------------------------------------------------------------------------------
        $sql7 = "SELECT * FROM Pase WHERE RFC = '" . $RFC . "' AND fecha BETWEEN '".$date1."' AND '".$date2."'";

        $consulta = $conexion->query($sql7);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $bandera = true;
            $contador = 0;
            $html = $html . '<div class="titulo ok">PASES</div>
                        <table class="tabla">
                            <tr>
                                <th class="desc">#</th>
                                <th class="desc">Fecha</th>
                                <th class="desc">Hora</th>
                                <th class="desc">Categoría</th>
                                <th class="desc">Descripción</th>
                            </tr>
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
            $html = $html . '<div class="titulo error">PASES</div>';
        }

         // --------------------------------------------------------------------------------------------------------------------------
         $sql8 = "SELECT * FROM Gastos WHERE RFC = '" . $RFC . "' AND fecha BETWEEN '".$date1."' AND '".$date2."'";

         $consulta = $conexion->query($sql8);
         if ($consulta && mysqli_num_rows($consulta) > 0) {
             $bandera = true;
             $contador = 0;
             $html = $html . '<div class="titulo ok">GASTOS MÉDICOS</div>
                         <table class="tabla">
                            <tr>
                                <th class="desc">#</th>
                                <th class="desc">Fecha</th>
                                <th class="desc">Concepto</th>
                                <th class="desc">Nombre quién otorga el apoyo</th>
                                <th class="desc">Monto ($)</th>
                            </tr>
                             <tbody>';
             while ($gasto = mysqli_fetch_array($consulta)) {
 
                 $html = $html . '<tr>
                            <td class="desc">' . ++$contador . '</td>
                             <td class="desc">' . date("d/m/Y", strtotime($gasto["fecha"])) . '</td>
                             <td class="desc">' . $gasto["concepto"] . '</td>
                             <td class="desc">' . $gasto["nombre"] . '</td>
                             <td class="desc">' . $gasto["monto"] . '</td>
                         </tr>';
             }
             $html = $html . '</tbody>
                     </table>';
         } else {
             $html = $html . '<div class="titulo error">GASTOS MÉDICOS</div>';
         }

        // --------------------------------------------------------------------------------------------------------------------------

        $html = $html . '</div></div>';

    }

    $html = $html.'</body></html>';
    if ($bandera) {
        $nombre = "reporte_" . time() . ".pdf";
        $snappy->generateFromHtml($html, '../archivos/' . $nombre);

        echo 'assets/archivos/' . $nombre;
    } else {
        echo 0;
    }
} else {
    echo 0;
}

$conexion->close();
