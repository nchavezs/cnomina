<?php
include "conexion.php";
$conexion = conexion();

function eliminar_simbolos($string)
{

    $string = mb_strtoupper(trim($string));

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

    $string = str_replace('  ', ' ', $string);

    return $string;
}
$total = 0;
$total_departamentos = 0;
$errores = [];

$archivo = $_FILES['file']['tmp_name'];

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

    $departamento = eliminar_simbolos($datos[0]);

    $sql = "SELECT * FROM Departamento WHERE nombre = '" . $departamento . "'";
    $consulta = $conexion->query($sql);

    if ($consulta && mysqli_num_rows($consulta) == 0 ) {
        $sql = "INSERT INTO Departamento(nombre) VALUES(NULLIF('" . $departamento . "', ''))";
        if ($conexion->query($sql)) {
            $total_departamentos++;
        }else{
            array_push($errores, "Fila ".$row." : valor no válido.");
        }
    } else{
        array_push($errores, "Fila ".$row." : el departamento ya existe.");
    }

}

$highestRow--;
echo "<div class='log'>";
echo "<div class='log_titulo'>REGISTRO DE IMPORTACIÓN</div>";
echo "<div class='log_cuerpo'>";
echo "<h5> " . $total_departamentos . " <span>departamentos importados de ".$highestRow."</span></h5>";

if(sizeof($errores)>0){
    echo "<h5>La siguiente lista muesta las filas no importadas. </h5>";
    echo "</div>";
    echo "<div class='log-contenido'>";
    foreach ($errores as $error) {
        echo "<h5>" . $error . "<h5>";
    }
}

echo "</div>";

$conexion->close();
