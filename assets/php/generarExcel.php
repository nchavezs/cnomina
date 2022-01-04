<?php
   include("conexion.php");
   $conexion = conexion();
	require '../../vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	$ruta = '../archivos/';
	if (!file_exists($ruta)) {
		mkdir($ruta, 0777, true);
	}

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet()->setTitle("Empleados");
	$spreadsheet->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('5377DB');
	$spreadsheet->getActiveSheet()->getStyle('A1:L1')->getFont()->getColor()->setRGB('FFFFFF');
	
	$sheet->setCellValue('A1', 'ID');
	$sheet->setCellValue('B1', 'NOMBRE(S)');
	$sheet->setCellValue('C1', 'APELLIDO PATERNO');
	$sheet->setCellValue('D1', 'APELLIDO MATERNO');
	$sheet->setCellValue('E1', 'FECHA DE INGRESO');
	$sheet->setCellValue('F1', 'CURP');
	$sheet->setCellValue('G1', 'RFC');
	$sheet->setCellValue('H1', 'PUESTO');
	$sheet->setCellValue('I1', 'DEPARTAMENTO');
	$sheet->setCellValue('J1', 'CUENTA BANCARIA');
	$sheet->setCellValue('K1', 'NO. AFILIACIÓN');
	$sheet->setCellValue('L1', 'TIPO DE TRABAJADOR');


	$sql = "SELECT * FROM Usuario WHERE categoria = 'user'";
	$consulta = mysqli_query($conexion, $sql);
	if($consulta && (mysqli_num_rows($consulta) > 0)){
			$i = 2;
			while($res = mysqli_fetch_array($consulta)){
				$spreadsheet->getActiveSheet()->getCell('A'.$i)->setValueExplicit(str_pad($res[0], 5, '0', STR_PAD_LEFT),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$spreadsheet->getActiveSheet()->getCell('B'.$i)->setValueExplicit(mb_strtoupper($res[17]),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$spreadsheet->getActiveSheet()->getCell('C'.$i)->setValueExplicit(mb_strtoupper($res[15]),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$spreadsheet->getActiveSheet()->getCell('D'.$i)->setValueExplicit(mb_strtoupper($res[16]),\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$spreadsheet->getActiveSheet()->getCell('E'.$i)->setValueExplicit($res[10],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$spreadsheet->getActiveSheet()->getCell('F'.$i)->setValueExplicit($res[9],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$spreadsheet->getActiveSheet()->getCell('G'.$i)->setValueExplicit($res[8],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$spreadsheet->getActiveSheet()->getCell('H'.$i)->setValueExplicit($res[11],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$spreadsheet->getActiveSheet()->getCell('I'.$i)->setValueExplicit($res[12],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$spreadsheet->getActiveSheet()->getCell('J'.$i)->setValueExplicit($res[13],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$spreadsheet->getActiveSheet()->getCell('K'.$i)->setValueExplicit($res[14],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$spreadsheet->getActiveSheet()->getCell('L'.$i)->setValueExplicit($res[19],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
				$i++;
			}
		
			foreach(range('A','L') as $columnID) {
				$sheet->getColumnDimension($columnID)->setAutoSize(true);
			}
			$spreadsheet->getActiveSheet()->setAutoFilter('A1:L1');
	}

	mysqli_close($conexion);

	$writer = new Xlsx($spreadsheet);
	$writer->save('../archivos/empleados.xlsx');								
	echo "assets/archivos/empleados.xlsx";
	exit();
?>