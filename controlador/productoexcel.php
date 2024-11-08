<?php
session_start();
require_once "./vendor/autoload.php";
require_once 'modelo/productos.php';
require_once 'modelo/categorias.php';
require_once 'modelo/unidad.php';
require_once "modelo/general.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing; // Importar la clase Drawing
use PhpOffice\PhpSpreadsheet\Style\Color; // Importar la clase Color
use PhpOffice\PhpSpreadsheet\Style\Style; // Importar la clase Style

// Objetos
$objProducto = new Productos();
$datos = $objProducto->getmostrar();
$general = new General();
$logo = $general->mostrar();

if (!empty($logo)) {
    $_SESSION["logo"] = $logo[0]["logo"];
    $_SESSION["n_empresa"] = $logo[0]["nombre"];
    $_SESSION["rif"] = $logo[0]["rif"];
    $_SESSION["telefono"] = $logo[0]["telefono"];
    $_SESSION["email"] = $logo[0]["email"];
    $_SESSION["direccion"] = $logo[0]["direccion"];
}

// Crear un nuevo objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Agregar el logo
if (!empty($_SESSION["logo"])) {
    $drawing = new Drawing();
    $drawing->setName('Logo');
    $drawing->setDescription('Logo de la empresa');
    $drawing->setPath($_SESSION["logo"]); // Ruta del logo
    $drawing->setHeight(50); // Altura del logo
    $drawing->setCoordinates('A1'); // Coordenadas donde se insertará el logo
    $drawing->setWorksheet($sheet);
}

// Agregar encabezado de la empresa
$sheet->setCellValue('B1', $_SESSION["n_empresa"]);
$sheet->setCellValue('B2', 'RIF: ' . $_SESSION["rif"]);
$sheet->setCellValue('B3', 'Teléfono: ' . $_SESSION["telefono"]);
$sheet->setCellValue('B4', 'Email: ' . $_SESSION["email"]);
$sheet->setCellValue('B5', 'Dirección: ' . $_SESSION["direccion"]);

// Establecer formato para el encabezado
$sheet->getStyle('B1:O5')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('B1:O5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

// Establecer color de fondo para el encabezado
$sheet->getStyle('A1:J5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$sheet->getStyle('A1:J5')->getFill()->getStartColor()->setARGB('DB6A00'); // Color de fondo

// Ajustar el ancho de las columnas
$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(20);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(20);
$sheet->getColumnDimension('I')->setWidth(15);
$sheet->getColumnDimension('J')->setWidth(20);

// Dejar una fila en blanco antes de los encabezados de la tabla
$row = 7;

// Establecer los encabezados de la tabla
$sheet->setCellValue('A' . $row, 'Código');
$sheet->setCellValue('B' . $row, 'Nombre');
$sheet->setCellValue('C' . $row, 'Marca');
$sheet->setCellValue('D' . $row, 'Presentación');
$sheet->setCellValue('E' . $row, 'Categoría');
$sheet->setCellValue('F' . $row, 'Costo');
$sheet->setCellValue('G' . $row, 'IVA');
$sheet->setCellValue('H' . $row, 'Precio de venta');
$sheet->setCellValue('I' . $row, 'Stock');
$sheet->setCellValue('J' . $row, 'Detalle');

// Establecer formato para el encabezado de la tabla
$sheet->getStyle('A' . $row . ':J' . $row)->getFont()->setBold(true);
$sheet->getStyle('A' . $row . ':J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Establecer color de fondo para el encabezado de la tabla de productos
$sheet->getStyle('A' . $row . ':J' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
$sheet->getStyle('A' . $row . ':J' . $row)->getFill()->getStartColor()->setARGB('D9EAD3'); // Color de fondo verde claro

// Aplicar bordes al encabezado de la tabla de productos
// Aplicar bordes al encabezado de la tabla de productos
$sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '000000'], // Color de los bordes
        ],
    ],
]);

// Llenar los datos de los productos
$row++; // Pasar a la siguiente fila
foreach ($datos as $producto) {
    $precioVenta = ($producto["porcen_venta"] / 100 + 1) * $producto["costo"];
    
    $sheet->setCellValue('A' . $row, $producto["cod_producto"]);
    $sheet->setCellValue('B' . $row, $producto["nombre"]);
    $sheet->setCellValue('C' . $row, $producto["marca"]);
    $sheet->setCellValue('D' . $row, $producto["presentacion"]);
    $sheet->setCellValue('E' . $row, $producto["cat_nombre"]);
    $sheet->setCellValue('F' . $row, $producto["costo"]);
    $sheet->setCellValue('G' . $row, $producto["excento"]);
    $sheet->setCellValue('H' . $row, $precioVenta);
    $sheet->setCellValue('I' . $row, 'Stock total'); // Aquí puedes agregar el stock real si lo tienes
    $sheet->setCellValue('J' . $row, 'Detalle'); // Aquí puedes agregar el detalle real si lo tienes

   // Aplicar sombreado de fondo a la fila de datos (más claro)
   $sheet->getStyle('A' . $row . ':J' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
   $sheet->getStyle('A' . $row . ':J' . $row)->getFill()->getStartColor()->setARGB('F0F0F0'); // Color de fondo gris claro

   // Aplicar bordes a las filas de datos
   $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
       'borders' => [
           'allBorders' => [
               'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
               'color' => ['argb' => '000000'], // Color de los bordes
           ],
       ],
   ]);
    $row++;
}

// Establecer el nombre del archivo
$filename = 'productos.xlsx';

// Crear un escritor para guardar el archivo
$writer = new Xlsx($spreadsheet);

// Forzar la descarga del archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// Guardar el archivo en la salida
$writer->save('php://output');
exit;
?>