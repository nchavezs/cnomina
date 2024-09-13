<?php
setlocale(LC_ALL, "spanish");
require_once "../../vendor/autoload.php";

use Carbon\Carbon;
use Luecano\NumeroALetras\NumeroALetras;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;

// Variables
$archivo = $_FILES['file']['tmp_name'];
$templateFile = '../docs/convenio.docx';
$tempDir = '../temp/' . uniqid() . '/';
$zipFile = '../temp/' . uniqid() . '.zip';

// Crear la carpeta temporal si no existe
mkdir($tempDir, 0777, true);

// Leer y procesar el archivo Excel
$spreadsheet = IOFactory::load($archivo);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

// Obtener el encabezado (la primera fila) con los nombres de las columnas
$header = array_shift($rows); // Extraer los nombres de las columnas

$zip = new ZipArchive();
$zip->open($zipFile, ZipArchive::CREATE);

// Iterar sobre las filas del Excel (después del encabezado)
foreach ($rows as $index => $row) {
    // Asociar los valores con los nombres de las columnas
    $rowData = array_combine($header, $row);

    // Asignar los valores basados en los nombres de columna
    $nombre = fnombre($rowData["NOMBRE"] ?? "________________");
    $ine = limpiar($rowData["INE"] ?? "________________");
    $rfc = limpiar($rowData["RFC"] ?? "________________");
    $puesto = limpiar($rowData["PUESTO"] ?? "________________");
    $departamento = limpiar($rowData["DEPARTAMENTO"] ?? "________________");
    $inicio = ffecha($rowData["FECHA INGRESO"] ?? "________________");
    $fin = ffecha($rowData["FECHA SEPARACION"] ?? "________________");
    $direccion = fdireccion($rowData["CALLE"], $rowData["NO"], $rowData["COLONIA"], $rowData["CIUDAD"]);
    $salario = fnum($rowData["SUELDO BRUTO"], true);
    $pago = fnum($rowData["NETO"], true);
    $indemnizacion = fnum($rowData["INDEMNIZACION"]);
    $antiguedad = fnum($rowData["PRIMA ANTIGÜEDAD"]);
    $aguinaldo = fnum($rowData["AGUINALDO"]);
    $vacacional = fnum($rowData["PRIMA VACACIONAL"]);
    $adeudo = fnum($rowData["ADEUDO"]);
    $isr = fnum($rowData["ISR"]);
    $neto = fnum($rowData["NETO"]);

    $data = [
        'nombre' => $nombre,
        'ine' => $ine,
        'rfc' => $rfc,
        'puesto' => $puesto,
        'departamento' => $departamento,
        'inicio' => $inicio,
        'fin' => $fin,
        'direccion' => $direccion,
        'salario' => $salario,
        'pago' => $pago,
        'indemnizacion' => $indemnizacion,
        'antiguedad' => $antiguedad,
        'aguinaldo' => $aguinaldo,
        'vacacional' => $vacacional,
        'adeudo' => $adeudo,
        'isr' => $isr,
        'neto' => $neto,
    ];

    // Crear documento Word y agregar al ZIP
    $wordFile = "{$tempDir}documento_{$index}.docx";
    $templateProcessor = new TemplateProcessor($templateFile);
    foreach ($data as $key => $value) {
        $templateProcessor->setValue($key, $value);
    }
    $templateProcessor->saveAs($wordFile);
    $zip->addFile($wordFile, basename($wordFile));
}

$zip->close();

// Limpiar carpeta temporal
array_map('unlink', glob("$tempDir/*.*"));
rmdir($tempDir);

// Devolver nombre del archivo ZIP
echo $zipFile;

// ---------------------------------------------------------------------------------------------------

function fnum($valor, $letra = false)
{
    if ($valor) {
        $numero = str_replace(['$', ' ', ','], '', $valor);
    } else {
        $numero = 0;
    }

    $valor = number_format($numero, 2, ".", ",");

    if ($letra) {
        $formatter = new NumeroALetras();
        $valor .= mb_strtolower(" (" . $formatter->toMoney($numero, 2, 'pesos', 'centavos')) . " M.N.)";
    }

    return limpiar($valor);
}

function fnombre($valor)
{
    $partes = explode(' ', $valor);

    // Asegúrate de que hay al menos dos partes
    if (count($partes) < 2) {
        return $valor; // Retorna el nombre original si no hay suficientes partes
    }

    // Extrae el primer nombre(s) y los apellidos
    $primerNombre = array_shift($partes); // Toma el primer nombre
    $segundoNombre = array_shift($partes); // Toma el segundo nombre (si existe)
    $apellidos = implode(' ', $partes); // Une el resto como apellidos

    // Devuelve el nombre formateado
    return limpiar("$primerNombre $segundoNombre $apellidos");
}

function fdireccion($calle, $numero, $colonia, $ciudad)
{
    if (!$calle && !$numero && !$ciudad) {
        $domicilio = "____________________________________";
    } else {
        $es_numero = is_numeric($numero) && (int) $numero == $numero && $numero > 0;
        $numero = $es_numero ? "NUMERO " . $numero : $numero;
        $colonia = $colonia ? $colonia . ", " : "";
        $domicilio = $calle . " " . $numero . ", " . $colonia . $ciudad;
    }

    return limpiar($domicilio) . ", GUANAJUATO, CÓDIGO POSTAL 38210";
}

function ffecha($valor)
{
    try {
        $date = Carbon::createFromFormat('d/m/Y', $valor);
    } catch (\Exception $e) {
        return $valor;
    }

    // Array de nombres de meses en español
    $meses = [
        '01' => 'ENERO',
        '02' => 'FEBRERO',
        '03' => 'MARZO',
        '04' => 'ABRIL',
        '05' => 'MAYO',
        '06' => 'JUNIO',
        '07' => 'JULIO',
        '08' => 'AGOSTO',
        '09' => 'SEPTIEMBRE',
        '10' => 'OCTUBRE',
        '11' => 'NOVIEMBRE',
        '12' => 'DICIEMBRE'
    ];

    // Extraer el día, mes y año
    $dia = $date->format('d');
    $mes = $date->format('m');
    $ano = $date->format('Y');

    // Obtener el nombre del mes en español
    $nombreMes = $meses[$mes];

    // Formatear la fecha en el formato deseado
    return "$dia DE $nombreMes DEL $ano";
}

function limpiar($valor)
{
    return trim(mb_strtoupper($valor));
}
