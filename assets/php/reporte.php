<?php
include "conexion.php";
$conexion = conexion();
require_once "../../vendor/autoload.php";
$css = file_get_contents("../css/reporte.css");
date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME, 'es_CO.UTF-8');
$hoy = date('d/m/Y', time());

$fecha1 = $_POST["fecha1"];
$fecha2 = $_POST["fecha2"];
$fecha3 = $_POST["fecha3"];
$fecha4 = $_POST["fecha4"];
$fecha5 = $_POST["fecha5"];
$fecha6 = $_POST["fecha6"];
$fecha7 = $_POST["fecha7"];
$fecha8 = $_POST["fecha8"];
$fecha9 = $_POST["fecha9"];
$fecha10 = $_POST["fecha10"];
$fecha17 = $_POST["fecha17"];
$fecha18 = $_POST["fecha18"];
$empleados = $_POST["empleados"];
$beneficiarios = $_POST["beneficiarios"];

$bandera = false;

if ($fecha1 == 0 && $fecha3 == 0 && $fecha5 == 0 && $fecha7 == 0 && $fecha9 == 0 && $fecha17 == 0 && $beneficiarios == 0) {
    echo 0;
} else {
    if ($empleados !== "") {
        
        foreach($empleados as $key => $item) {
            $empleados[$key] = "'".$item."'";
        }
        $array = implode(',', $empleados);
        $empleados = " AND RFC IN (" . $array . ") ";

    } else {
        $empleados = " AND RFC = -1 ";
    }

    $sql = "SELECT * FROM Usuario WHERE categoria = 'user' " . $empleados;
    if (($consulta = mysqli_query($conexion, $sql)) && (($total = mysqli_num_rows($consulta)) > 0)) {
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->setHTMLFooter('<div class="footer"><p>Página {PAGENO} de {nb}</p></div>');
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        while ($resultado = mysqli_fetch_row($consulta)) {
            $sql0 = "SELECT * FROM Usuario WHERE RFC = '" . $resultado[8]."'";
            $consulta0 = mysqli_query($conexion, $sql0);
            $resultado0 = mysqli_fetch_row($consulta0);

            $html = '<body>
						<header class="clearfix">
							<div class="logo"></div>
							<div id="company" class="clearfix">
								<h2>MUNICIPIO DE YURIRIA, GTO</h2>
								<div><span>FECHA DE ELABORACIÓN</span> ' . $hoy . '</div>
							</div>
							<div id="project">
								<div><span>NOMBRE</span> ' . $resultado[6] . '</div>
								<div><span>NO. DE EMPLEADO</span> ' . str_pad($resultado[0], 5, '0', STR_PAD_LEFT) . '</div>
								<div><span>FECHA DE INICIO</span> ' . $resultado[10] . '</div>
								<div><span>PUESTO</span> ' . ucwords(mb_strtolower($resultado[11])) . '</div>
								<div><span>DEPARTAMENTO</span> ' . ucwords(mb_strtolower($resultado[12])) . '</div>
								<div><span>CURP</span> ' . $resultado[9] . '</div>
								<div><span>RFC</span> ' . $resultado[8] . '</div>
								<div><span>ESTADO DEL EMPLEADO</span> ' . ucfirst($resultado0[7]) . '</div>
							</div>
						</header>
						<main>';
            if ($beneficiarios == 1) {
                $sql1 = "SELECT * FROM Beneficiario WHERE RFC = '" . $resultado[8]."'";
                if (($consulta1 = mysqli_query($conexion, $sql1)) && mysqli_num_rows($consulta1) > 0) {
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
                    while ($resultado1 = mysqli_fetch_row($consulta1)) {
                        $contador = $contador + 1;
                        $html = $html . '<tr>
											<td class="desc">' . $contador . '</td>
											<td class="desc">' . ucwords(mb_strtolower($resultado1[2])) . '</td>
											<td class="desc">' . ucfirst(mb_strtolower($resultado1[3])) . '</td>
										</tr>';
                    }
                    $html = $html . '</tbody>
									</table>';
                } else {
                    $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">BENEFICIARIOS</div></div>';
                }

            }

            if ($fecha1 != 0) {
                $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha1)));
                $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha2)));
                $sql2 = "SELECT * FROM Movimiento WHERE RFC = '" . $resultado[8] . "' AND fecha >= '" . $date1 . "' AND fecha <= '" . $date2 . "'";
                if (($consulta2 = mysqli_query($conexion, $sql2)) && mysqli_num_rows($consulta2) > 0) {
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
                    while ($resultado2 = mysqli_fetch_row($consulta2)) {
                        $contador = $contador + 1;
                        $html = $html . '<tr>
											<td class="desc">' . $contador . '</td>
											<td class="desc">' . date("d/m/Y", strtotime($resultado2[2])) . '</td>
											<td class="desc">' . ucwords(mb_strtolower($resultado2[3])) . '</td>
											<td class="desc">' . ucwords(mb_strtolower($resultado2[4])) . '</td>
											<td class="desc">' . ucwords(mb_strtolower($resultado2[6])) . '</td>
											<td class="desc">' . ucwords(mb_strtolower($resultado2[7])) . '</td>
										</tr>';
                    }
                    $html = $html . '</tbody>
									</table>';
                } else {
                    $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">MOVIMIENTOS</div></div>';
                }

            }

            if ($fecha3 != 0) {
                $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha3)));
                $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha4)));
                $sql3 = "SELECT * FROM Descuento WHERE RFC = '" . $resultado[8]."'";
                if (($consulta3 = mysqli_query($conexion, $sql3)) && (mysqli_num_rows($consulta3) > 0) && ($date2 > $date1)) {
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
                    while ($resultado3 = mysqli_fetch_row($consulta3)) {
                        $fechas = explode(",", $resultado3[4]);
                        foreach ($fechas as $fecha) {
                            $date = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));
                            if ($date >= $date1 && $date <= $date2) {
                                $contador = $contador + 1;
                                $html = $html . '<tr>
													<td class="desc">' . $contador . '</td>
													<td class="desc">' . strftime("%A, %d de %B de %G", strtotime($date)) . '</td>
													<td class="desc">' . ucfirst($resultado3[5]) . '</td>
												</tr>';
                            }
                        }
                    }
                    $html = $html . '</tbody>
									</table>';
                } else {
                    $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">DESCUENTOS</div></div>';
                }

            }

            if ($fecha5 != 0) {
                $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha5)));
                $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha6)));
                $sql4 = "SELECT * FROM Vacacion WHERE RFC = '" . $resultado[8] . "' AND al >= '" . $date1 . "' AND al <= '" . $date2 . "'";
                if (($consulta4 = mysqli_query($conexion, $sql4)) && mysqli_num_rows($consulta4) > 0) {
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
                    while ($resultado4 = mysqli_fetch_row($consulta4)) {
                        $contador = $contador + 1;
                        if (is_null($resultado4[6]) || trim($resultado4[6]) === "") {
                            $descripcion = "Sin descripción";
                        } else {
                            $descripcion = $resultado4[6];
                        }

                        $html = $html . '<tr>
											<td class="desc">' . $contador . '</td>
											<td class="desc">' . $resultado4[3] . '</td>
											<td class="desc">' . strftime("%d de %B de %G", strtotime($resultado4[4])) . strftime(" al %d de %B de %G", strtotime($resultado4[5])) . '</td>
											<td class="desc">' . ucfirst($descripcion) . '</td>
										</tr>';
                    }
                    $html = $html . '</tbody>
									</table>';
                } else {
                    $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">VACACIONES</div></div>';
                }

            }

            if ($fecha7 != 0) {
                $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha7)));
                $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha8)));
                $sql5 = "SELECT * FROM Permiso WHERE RFC = '" . $resultado[8] . "' AND al >= '" . $date1 . "' AND al <= '" . $date2 . "' AND categoria = 0";
                if (($consulta5 = mysqli_query($conexion, $sql5)) && mysqli_num_rows($consulta5) > 0) {
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
                    while ($resultado5 = mysqli_fetch_row($consulta5)) {
                        $contador = $contador + 1;
                        if (is_null($resultado5[7]) || trim($resultado5[7]) === "") {
                            $descripcion = "Sin descripción";
                        } else {
                            $descripcion = $resultado5[7];
                        }

                        $html = $html . '<tr>
											<td class="desc">' . $contador . '</td>
											<td class="desc">' . $resultado5[3] . '</td>
											<td class="desc">' . strftime("%d de %B de %G", strtotime($resultado5[4])) . strftime(" al %d de %B de %G", strtotime($resultado5[5])) . '</td>
											<td class="desc">' . ucfirst($descripcion) . '</td>
										</tr>';
                    }
                    $html = $html . '</tbody>
									</table>';
                } else {
                    $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">PERMISOS CON GOCE DE SUELDO</div></div>';
                }

            }

            if ($fecha9 != 0) {
                $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha9)));
                $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha10)));
                $sql6 = "SELECT * FROM Permiso WHERE RFC = '" . $resultado[8] . "' AND al >= '" . $date1 . "' AND al <= '" . $date2 . "' AND categoria = 1";
                if (($consulta6 = mysqli_query($conexion, $sql6)) && mysqli_num_rows($consulta6) > 0) {
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
                    while ($resultado6 = mysqli_fetch_row($consulta6)) {
                        $contador = $contador + 1;
                        if (is_null($resultado6[7]) || trim($resultado6[7]) === "") {
                            $descripcion = "Sin descripción";
                        } else {
                            $descripcion = $resultado6[7];
                        }

                        $html = $html . '<tr>
											<td class="desc">' . $contador . '</td>
											<td class="desc">' . $resultado6[3] . '</td>
											<td class="desc">' . strftime("%d de %B de %G", strtotime($resultado6[4])) . strftime(" al %d de %B de %G", strtotime($resultado6[5])) . '</td>
											<td class="desc">' . ucfirst($descripcion) . '</td>
										</tr>';
                    }
                    $html = $html . '</tbody>
									</table>';
                } else {
                    $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">PERMISOS SIN GOCE DE SUELDO</div></div>';
                }

            }

            if ($fecha17 != 0) {
                $date1 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha17)));
                $date2 = date("Y-m-d", strtotime(str_replace('/', '-', $fecha18)));
                $sql8 = "SELECT * FROM Pase WHERE RFC = '" . $resultado[8] . "' AND fecha >= '" . $date1 . "' AND fecha <= '" . $date2 . "'";
                if (($consulta8 = mysqli_query($conexion, $sql8)) && mysqli_num_rows($consulta8) > 0) {
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
                    while ($resultado8 = mysqli_fetch_row($consulta8)) {
						$contador = $contador + 1;
						$categoria = "ENTRADA";
						if($resultado8[3] == 1)
							$categoria = "SALIDA";

						$observacion = "Sin descripción";
						if(trim($resultado8[5]) !== "")
							$observacion = ucfirst(mb_strtolower($resultado8[5]));

                        $html = $html . '<tr>
											<td class="desc">' . $contador . '</td>
											<td class="desc">' . date("d/m/Y", strtotime($resultado8[2])) . '</td>
											<td class="desc">' . $resultado8[3] . '</td>
											<td class="desc">' . $categoria . '</td>
											<td class="desc">' . $observacion . '</td>
										</tr>';
                    }
                    $html = $html . '</tbody>
									</table>';
                } else {
                    $html = $html . '<div class="error"><div class="icono"></div><div class="titulo">PASES</div></div>';
                }

            }

            $sql7 = "SELECT * FROM Baja WHERE RFC = '" . $resultado[8]."'";
            if (($consulta7 = mysqli_query($conexion, $sql7)) && (mysqli_num_rows($consulta7) > 0)) {
                $html = $html . '<div class="ok"><div class="icono2"></div><div class="titulo">BAJA</div></div>
											<table>
												<thead>
													<tr>
														<th class="desc">Fecha de baja</th>
														<th class="desc">Descripción</th>
													</tr>
												</thead>
												<tbody>';
                while ($resultado7 = mysqli_fetch_row($consulta7)) {
                    $html = $html . '<tr>
												<td class="desc">' . strftime("%A, %d de %B de %G", strtotime($resultado7[2])) . '</td>
												<td class="desc">' . ucfirst($resultado7[3]) . '</td>
											</tr>';

                }
                $html = $html . '</tbody>
								</table>';
            }

            $html = $html . '</main></body>';
            $html = utf8_decode($html);
            $mpdf->WriteHTML(utf8_encode($html), \Mpdf\HTMLParserMode::HTML_BODY);
            $total = $total - 1;
            if ($total > 0) {
                $mpdf->AddPage();
            }

        }
        if ($bandera) {
            $mpdf->Output('../archivos/reporte.pdf', 'F');
            echo 'assets/archivos/reporte.pdf';
        } else {
            echo 0;
        }

    } else {
        echo 0;
    }
    mysqli_close($conexion);
}
