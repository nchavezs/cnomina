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

$archivo = $_FILES['file']['name'];

require_once "../../vendor/autoload.php";
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($archivo);

$worksheet = $spreadsheet->getActiveSheet();
$highestRow = $worksheet->getHighestRow();
$highestColumn = "C";
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

for ($row = 2; $row <= $highestRow; ++$row) {
    $datos = [];
    for ($col = 1; $col <= $highestColumnIndex; ++$col) {
        $valor = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
        array_push($datos, $valor);
    }

    $puesto = eliminar_simbolos(mb_strtoupper($datos[0]));
    $departamento = eliminar_simbolos(mb_strtoupper($datos[1]));
    $cantidad = eliminar_simbolos(mb_strtoupper($datos[2]));

    $sql = "SELECT RFC FROM Usuario WHERE RFC = '" . $rfc . "'";
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
        WHERE RFC = '" . $rfc . "'";

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
echo "<h5> " . $total . " <span>de un total de</span> " . $highestRow . "</h5>";
echo "<h5>La siguiente lista muesta los RFC no actualizados. </h5>";
echo "</div>";
echo "<div class='log-contenido'>";
foreach ($errores as $error) {
    echo "<h5>" . $error . "<h5>";
}
echo "</div>";

mysqli_close($conexion);
