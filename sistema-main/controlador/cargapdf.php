<?php
session_start();
require_once "./vendor/autoload.php";
require_once "modelo/dcarga.php";
require_once "modelo/general.php";


use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf('P', 'LETTER', 'es');
$obj = new Dcarga();
                                                    
$fechaInicio = $_POST['fechaInicio1'] ?? null;
$fechaFin = $_POST['fechaFin1'] ?? null;

if (!empty($fechaInicio) && !empty($fechaFin)) {
    $datos = $obj->getmostrarPorFechas($fechaInicio, $fechaFin);
} else {
    $datos = $obj->getodoo(); // Obtener todos los datos si no hay filtros
}

if (isset($datos)) {
    // Obtener la fecha y hora actual
    $fechaHoraGeneracion = date('d-m-Y H:i:s');
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
    .fecha-generacion {
        text-align: right; /* Alinear a la izquierda */
        margin-top: 10px; /* Espaciado superior */
    }
    .info-empresa {
        width: 100%;
        border-collapse: collapse;
        margin: 0; /* Sin margen para alineación a la izquierda */
    }
    .info-empresa td {
        border: none; /* Sin bordes en las celdas */
        text-align: left; /* Alinear el texto a la izquierda */
    }
</style>
    
<page backtop="7mm" backbottom="10mm">
    <table class="info-empresa">
        <tr>
            <td style="width:150px; border:none;">
                <img src="' . $_SESSION["logo"] . '" style="width:110px; height:auto;">
            </td>
            <td style="text-align:center; font-size:12px; width:360px; border:none;">
                <strong style="color:red; font-size:15px;">' . $_SESSION["n_empresa"] . '</strong><br>
                <b>Rif: ' . $_SESSION["rif"] . '</b><br>
                <b>Domicilio Fiscal: </b> ' . $_SESSION["direccion"] . '.<br>
                Barquisimeto Lara - <b>Teléfono: </b>' . $_SESSION["telefono"] . '<br>
                <b>Email: </b>' . $_SESSION["email"] . '
            </td>
        </tr>
    </table>
    

    <br>
    <hr style="border=0.5px;">
    <br>
    <p class="fecha-generacion">Fecha de creación: ' . $fechaHoraGeneracion . '</p>
    <h1 style="text-align:center;">Listado de Carga de productos</h1>
    
    <table id="t">
    <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Descripción</th>
                    <th>Cantidad cargada</th>
                </tr>
            </thead>
            <tbody>';
    foreach ($datos as $datos) {
        if ($datos['status'] != 2) {
            if ($datos["status"] == 1) {
                $status = 'Activo';
            } elseif ($datos["status"] == 0) {
                $status = 'Inactivo';
            }
            $html .= '
                <tr>
                    <td>' .  $datos['cod_carga'] . '</td>
                    <td>' . $datos['fecha'] . '</td>
                    <td>' . $datos['nombre'] . '</td>
                    <td>' . $datos['descripcion'] . '</td>
                    <td>' . $datos['cantidad'] . '</td>
                </tr>';
        }
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
    $html2pdf->output('reporte-carga.pdf');
}
