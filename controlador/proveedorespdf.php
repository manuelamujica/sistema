<?php

session_start();
require_once "./vendor/autoload.php";
require_once 'modelo/proveedores.php';

use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf('P', 'LETTER', 'es');
$objProveedores = new Proveedor();
$registro = $objProveedores->getconsulta();

if (isset($registro)) {
    $html = '
    <style>
    #t{
        width: 100%;
        border-collapse: collapse;
        margin: auto;
    }
    th, td {
        border: 1px solid black;
        padding: 5px;
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
    <h1 style="text-align:center;">Listado de Proveedores</h1>
    <table id="t">
    <thead>
        <tr>
            <th>Código</th>
            <th>Rif</th>
            <th>Razon social</th>
            <th>Correo electronico</th>
            <th>Dirección</th>
            <th>Telefonos</th>
            <th>Representante</th>
        </tr>
    </thead>
    <tbody>';
    foreach ($registro as $datos) {
        if ($datos["status"] == 1) {
            $status = 'Activo';
        } elseif ($datos["status"] == 0) {
            $status = 'Inactivo';
        }
        $html .= '
            <tr>
                <td>' . $datos['cod_prov'] . '</td>
                <td>' . $datos['rif'] . '</td>
                <td>' . $datos['razon_social'] . '</td>
                <td>' . (!empty($datos['email']) ? $datos['email'] : 'No disponible') . '</td>
                <td>' . (!empty($datos['direccion']) ? $datos['direccion'] : 'No disponible') . '</td>
                <td>' . (!empty($datos['telefonos']) ? $datos['telefonos'] : 'No disponible') . '</td>
                <td>' . (!empty($datos['representante']) ? $datos['representante'] : 'No disponible') . '</td>
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
