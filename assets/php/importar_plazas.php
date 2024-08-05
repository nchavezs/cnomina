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
        array(
            "\\", "¨", "º", "-", "~",
            "#", "@", "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "<code>", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            ".", " ",
        ),
        ' ',
        $string
    );

    $string = str_replace('  ', ' ', $string);

    return $string;
}

$total_departamentos = 0;
$total_puestos = 0;
$total_plazas = 0;
$errores = [];

$archivo = $_FILES['file']['tmp_name'];

require_once "../../vendor/autoload.php";
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($archivo);

$worksheet = $spreadsheet->getActiveSheet();
$highestRow = $worksheet->getHighestRow();
$highestColumn = "E";
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

$datos = [];
for ($col = 1; $col <= $highestColumnIndex; ++$col) {
    $valor = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
    array_push($datos, $valor);
}
if ($datos[0] != "PUESTO" && $datos[1] != "DEPARTAMENTO" && $datos[2] != "DIAS PRESUPUESTADOS" && $datos[3] != "CANTIDAD" && $datos[4] != "CATEGORIA") {
    echo 0;
} else {
    for ($row = 2; $row <= $highestRow; ++$row) {
        $datos = [];

        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
            $valor = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            array_push($datos, $valor);
        }

        $puesto = eliminar_simbolos($datos[0]);
        $departamento = eliminar_simbolos($datos[1]);
        $dias = eliminar_simbolos($datos[2]);
        $cantidad = eliminar_simbolos($datos[3]);
        $trabajador = eliminar_simbolos($datos[4]);

        if ($dias > 365) {
            $dias = 365;
        } else if ($dias < 1) {
            $dias = 1;
        }

        $sql = "SELECT * FROM Departamento WHERE nombre = '" . $departamento . "'";
        $consulta = $conexion->query($sql);

        if ($consulta && mysqli_num_rows($consulta) == 0) {
            $sql = "INSERT INTO Departamento(nombre) VALUES(NULLIF('" . $departamento . "', ''))";
            if ($conexion->query($sql)) {
                $id_depa = mysqli_insert_id($conexion);
                $total_departamentos++;
            }
        } elseif ($consulta && mysqli_num_rows($consulta) > 0) {
            $res = mysqli_fetch_row($consulta);
            $id_depa = $res[0];
        }

        $sql = "SELECT * FROM Puesto WHERE nombre = '" . $puesto . "' AND id_departamento = " . $id_depa;
        $consulta = $conexion->query($sql);
        $total = mysqli_num_rows($consulta);

        if ($consulta && $total > 0) {
            $id_puesto = mysqli_fetch_row($consulta);
            $id_puesto = $id_puesto[0];

            $sql = "INSERT INTO Plaza(id_puesto, dias) VALUES(" . $id_puesto . ", " . $dias . ")";
            for ($i = 0; $i < $cantidad; $i++) {
                $conexion->query($sql);
                $total_plazas++;
            }
        } else if ($consulta && $total == 0) {
            $sql = "SELECT * FROM Trabajador WHERE nombre = '" . $trabajador . "'";
            $consulta = $conexion->query($sql);
            $total = mysqli_num_rows($consulta);

            if ($consulta && $total > 0) {
                $trabajador = mysqli_fetch_array($consulta);
                $id_trabajador = $trabajador[0];

                $sql = "INSERT INTO Puesto(nombre, id_departamento, id_trabajador) VALUES(NULLIF('" . $puesto . "', ''), " . $id_depa . ", " . $id_trabajador . ")";
                if ($conexion->query($sql)) {
                    $id_puesto = mysqli_insert_id($conexion);
                    $sql = "INSERT INTO Plaza(id_puesto, dias) VALUES(" . $id_puesto . ", " . $dias . ")";
                    for ($i = 0; $i < $cantidad; $i++) {
                        $conexion->query($sql);
                        $total_plazas++;
                    }
                    $total_puestos++;
                } else {
                    array_push($errores, "Fila " . $row . " : error al importar.");
                }
            } else {
                array_push($errores, "Fila " . $row . " : error al importar.");
            }
        } else {
            array_push($errores, "Fila " . $row . " : error al importar.");
        }
    }

    $highestRow--;
    echo "<div class='log'>";
    echo "<div class='log_titulo'>REGISTRO DE IMPORTACIÓN</div>";
    echo "<div class='log_cuerpo'>";
    echo "<h5> Se han agregado " . $total_plazas . " <span>plazas en total.</span></h5>";
    echo "<h5> Se encontraron " . $total_puestos . " <span> puestos nuevos.</span></h5>";
    echo "<h5> Se encontraron " . $total_departamentos . " <span> departamentos nuevos.</span></h5>";

    if (sizeof($errores) > 0) {
        echo "<h5>La siguiente lista muesta las filas no importadas. </h5>";
        echo "</div>";
        echo "<div class='log-contenido'>";
        foreach ($errores as $error) {
            echo "<h5>" . $error . "<h5>";
        }
    }

    echo "</div>";

    $conexion->close();

}
