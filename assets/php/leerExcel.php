<?php
include "conexion.php";
$conexion = conexion();

function eliminar_simbolos($string)
{

    $string = trim($string);

    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ç', 'Ç'),
        array('c', 'C'),
        $string
    );

    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
            "#", "@", "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "<code>", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            ".", " "),
        ' ',
        $string
    );
    return $string;
}

$total = 0;
$errores = [];

require_once "../../vendor/autoload.php";
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load("../archivos/empleados.xlsx");

$worksheet = $spreadsheet->getActiveSheet();
$highestRow = $worksheet->getHighestRow();
$highestColumn = "L";
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

for ($row = 2; $row <= $highestRow; ++$row) {
    $datos = [];
    for ($col = 1; $col <= $highestColumnIndex; ++$col) {
        $valor = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
        array_push($datos, $valor);

    }

    $fecha = trim($datos[4]);

    $array = explode("/", $fecha);
    if (sizeof($array) != 3) {
        if (is_numeric($fecha)) {
            $fecha = date("d/m/Y", \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($datos[4]));
        }else{
            $fecha = "00000000000";
        }
    }

    $nombres = trim(ucwords(mb_strtolower($datos[1])));
    $apellidom = trim(ucfirst(mb_strtolower($datos[3])));
    $apellidop = trim(ucfirst(mb_strtolower($datos[2])));
    $nombreEmpleado = $apellidop . " " . $apellidom . " " . $nombres;

    $trabajador = eliminar_simbolos(mb_strtoupper($datos[11]));
    $puesto = eliminar_simbolos(mb_strtoupper($datos[7]));
    $departamento = eliminar_simbolos(mb_strtoupper($datos[8]));
    $rfc = eliminar_simbolos(mb_strtoupper($datos[6]));
    $curp = eliminar_simbolos(mb_strtoupper($datos[5]));
    $afiliacion = eliminar_simbolos(mb_strtoupper($datos[10]));
    $banca = eliminar_simbolos(mb_strtoupper($datos[9]));
    $password = str_pad($datos[0], 5, '0', STR_PAD_LEFT);

    $sql = "SELECT RFC FROM Usuario WHERE RFC = '" . $rfc."'";
    $consulta = mysqli_query($conexion, $sql);
    if ($consulta && mysqli_num_rows($consulta) == 0) {

        $sql = "INSERT INTO Usuario(id_usuario, categoria, contrasenia, nombres, apellidop, apellidom, RFC,
		CURP, fechaRelLab, puesto, departamento, banca, afiliacion, tipoTrabajador, nombre)
		VALUES(" . $datos[0] . ", 'user', '" . $password . "', '" . $nombres . "' , '" . $apellidop . "' , '" . $apellidom . "', '" . $rfc . "',
		'" . $curp . "', '" . $fecha . "', '" . $puesto . "', '" . $departamento . "', NULLIF('" . $banca . "', ''),
		NULLIF('" . $afiliacion . "',''), NULLIF('" . $trabajador . "', ''), '" . $nombreEmpleado . "')";
        if (mysqli_query($conexion, $sql)) {
            $total++;
        } else {
            array_push($errores, $rfc);
        }

    } elseif (mysqli_num_rows($consulta) == 1) {
        $sql = "UPDATE Usuario SET nombre = '" . $nombreEmpleado . "', RFC = '" . $rfc . "', CURP = '" . $curp . "',
        puesto = '" . $puesto . "', departamento = '" . $departamento . "', banca = NULLIF('" . $banca . "', ''),
        afiliacion = NULLIF('" . $afiliacion . "',''), nombres = '" . $nombres . "', apellidop = '" . $apellidop . "',
        apellidom = '" . $apellidom . "',
        fechaRelLab = '" . $fecha . "',
        tipoTrabajador = NULLIF('" . $trabajador . "', '')
        WHERE RFC = '" . $rfc."'";

        if (mysqli_query($conexion, $sql)) {
            $total++;
        } else {
            array_push($errores, $rfc);
        }
    }

}
$highestRow--;
echo "<div class='log'>";
echo "<h4>Elementos actualizados: </h4>";
echo "<h5> ". $total . " <span>de un total de</span> " . $highestRow . "</h5>";
echo "<h5>La siguiente lista muesta los RFC no actualizados. </h5>";
echo "</div>";
echo "<div class='log-contenido'>";
foreach ($errores as $error) {
    echo "<h5>".$error . "<h5>";
}
echo "</div>";

mysqli_close($conexion);
