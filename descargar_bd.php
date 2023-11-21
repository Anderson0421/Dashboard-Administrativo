<?php
require 'vendor/autoload.php';
// CODIGO PARA DESCARGAR LA BD EN FORMATO EXCEL
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

// Establecer conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "polleria";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Crear un nuevo objeto Spreadsheet
$spreadsheet = new Spreadsheet();

// Obtener la hoja activa del objeto Spreadsheet
$activeWorksheet = $spreadsheet->getActiveSheet();

// Realizar la consulta SQL para obtener los datos de la base de datos
$sql = "SELECT * FROM reservas";
$result = $conn->query($sql);

// Establecer las cabeceras en la primera fila con estilos
$columnIndex = 1;
$headers = ['ID', 'Nombre', 'Teléfono', 'Correo', 'ID Pedido', 'Pedido Plus', 'Cantidad', 'Total', 'Mensaje', 'Fecha de Registro', 'Estatus'];
foreach ($headers as $header) {
    $activeWorksheet->setCellValueByColumnAndRow($columnIndex, 1, $header);
    $activeWorksheet->getStyleByColumnAndRow($columnIndex, 1)->applyFromArray([
        'font' => [
            'bold' => true,
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
                'rgb' => 'D9D9D9',
            ],
        ],
    ]);
    $columnIndex++;
}

// Obtener los datos y agregarlos a la hoja de cálculo
if ($result->num_rows > 0) {
    $rowIndex = 2;
    while ($row = $result->fetch_assoc()) {
        $columnIndex = 1;
        foreach ($row as $value) {
            $activeWorksheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
            $columnIndex++;
        }
        $rowIndex++;
    }
}

// Ajustar el ancho de las columnas automáticamente
foreach (range('A', 'K') as $column) {
    $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
}

// Aplicar estilo a todas las celdas
$activeWorksheet->getStyle('A1:K' . ($rowIndex - 1))->applyFromArray([
    'alignment' => [
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
]);

// Crear un objeto Writer para guardar el archivo Excel
$writer = new Xlsx($spreadsheet);
$filename = 'base_de_datos.xlsx';
$writer->save($filename);

// Establecer las cabeceras para descargar el archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Leer el archivo Excel y enviarlo al navegador
readfile($filename);

// Cerrar la conexión a la base de datos
$conn->close();

// Eliminar el archivo temporal
unlink($filename);

// Terminar la ejecución del script
exit;
?>
