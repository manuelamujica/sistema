<?php
session_start();
require_once "./vendor/autoload.php";
require_once 'modelo/productos.php';
require_once 'modelo/categorias.php';
require_once 'modelo/unidad.php';
require_once "modelo/general.php";


use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf('P', 'LETTER', 'es');
#Objetos
$objProducto = new Productos();
// Obtener las fechas del formulario

$fechaInicio = $_POST['fechaInicio'] ?? null;
$fechaFin = $_POST['fechaFin'] ?? null;

if (!empty($fechaInicio) && !empty($fechaFin)) {
    $datos = $objProducto->getmostrarPorFechas($fechaInicio, $fechaFin);
} else {
    $datos = $objProducto->getmostrar(); // Obtener todos los datos si no hay filtros
}

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
if (isset($datos)) {
    $html = '
    <style>
    #t{
        width: 100%;
        border-collapse: collapse;
        margin: auto;
    }
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #db6a00;
    }
</style>
    
<page backtop="7mm" backbottom="10mm">
    <table id="membrete" style="width:100%; border:none;">
    <tr>
        <td style="text-align:left; border: none";>';
    if (isset($_SESSION["logo"])) {
        $html .= '<img src="' . $_SESSION["logo"] . '" style="width:100px; max-width:200px;">'; //ajustar el tama;o
    } else {
        $html .= '<img src="vista/dist/img/logo_generico.png" alt="Quesera Don Pedro" style="width:100%; max-width:200px;">';
    }
    $html .= '
        </td>
            <td style="text-align:rigth; border: none;">';
    $html .= '  
                <h3 style="margin-bottom: 5px;">' . $_SESSION["nombre"] . '</h3>
                <p style="margin-top: 0; margin-bottom: 5px;">' . $_SESSION["rif"] . '</p>
                <p style="margin-top: 0; margin-bottom: 5px;">' . $_SESSION["telefono"] . '</p>
                <p style="margin-top: 0;">' . $_SESSION["email"] . '</p>
                </td>
        </tr>
    </table>

    <br>
    <hr style="border=0.5px;">
    <br>
    <h1 style="text-align:center;">Reporte de Productos</h1>
    <table id="t">
    <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Presentacion</th>
                    <th>Categoría</th>
                    <th>Costo</th>
                    <th>IVA</th>
                    <th>Precio de venta</th>
                </tr>
            </thead>
            <tbody>';
    foreach ($datos as $datos) {
            $html .= '
                <tr>
                    <td>' .  $datos['cod_producto'] . '</td>
                    <td>' . $datos['nombre'] . '</td>
                    <td>' . $datos['marca'] . '</td>
                    <td>' . $datos['presentacion'] . '</td>
                    <td>' . $datos['cat_nombre'] . '</td>
                    <td>' .  $datos['costo'] . '</td>
                    <td>' . $datos['excento'] . '</td>
                    <td>' . $precioVenta = ($datos['porcen_venta']/100+1)*$datos['costo'] . '</td>
                </tr>';
    }
    $html .= '
            </tbody>
    </table>
    <page_footer>
                <div style="text-align: center;">
                    <p>' . $_SESSION["telefono"] . '  |  ' . $_SESSION["direccion"] . '  |  ' . $_SESSION["email"] . '</p>
                </div>
    </page_footer>
</page>';
    $html2pdf->writeHTML($html);
    $html2pdf->output();
}
