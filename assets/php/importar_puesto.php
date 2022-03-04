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

    return $string;
}

$total_departamentos = 0;
$total_puestos = 0;
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

    $puesto = eliminar_simbolos($datos[0]);
    $departamento = eliminar_simbolos($datos[1]);
    $trabajador = eliminar_simbolos($datos[2]);

    $sql = "SELECT * FROM Departamento WHERE nombre = '" . $departamento . "'";
    $consulta = $conexion->query($sql);

    if ($consulta && mysqli_num_rows($consulta) == 0) {
        $sql = "INSERT INTO Departamento(nombre) VALUES(NULLIF('" . $departamento . "', ''))";
        if ($conexion->query($sql)) {
            $id_depa = mysqli_insert_id($conexion);
            $total_departamentos++;
        }
    } elseif($consulta && mysqli_num_rows($consulta) > 0){
        $res = mysqli_fetch_row($consulta);
        $id_depa = $res[0];
    }
    
    $sql = "SELECT * FROM Puesto WHERE nombre = '" . $puesto . "' AND id_departamento = ".$id_depa;
    $consulta = $conexion->query($sql);

    if ($consulta && mysqli_num_rows($consulta) == 0) {
        $sql = "SELECT id_trabajador FROM Trabajador WHERE nombre = '".$trabajador."'";
        $conexion->query($sql);
        if ($consulta && mysqli_num_rows($consulta) > 0) {
            $id_trabajador = mysqli_fetch_array($consulta);
            
            $sql = "INSERT INTO Puesto(nombre, id_departamento, id_trabajador) VALUES(
                NULLIF('" . $puesto . "', ''),
                " . $id_depa . ",
                ".$id_trabajador."
            )";
    
            if ($conexion->query($sql)) {
                $total_puestos++;
            } else {
                array_push($errores, "Fila ".$row." : error al importar.");
            }
        } else {
            array_push($errores, "Fila ".$row." : error al importar.");
        }
    }
}

$highestRow--;
echo "<div class='log'>";
echo "<div class='log_titulo'>REGISTRO DE IMPORTACIÓN</div>";
echo "<div class='log_cuerpo'>";
echo "<h5> " . $total_puestos . " <span> puestos importados</span></h5>";
echo "<h5> " . $total_departamentos . " <span>departamentos importados</span></h5>";

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
