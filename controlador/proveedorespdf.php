<?php

session_start();
require_once "./vendor/autoload.php";
require_once 'modelo/proveedores.php';
require_once "modelo/general.php";

use Spipu\Html2Pdf\Html2Pdf;

// Cambiar 'P' a 'L' para orientación horizontal
$html2pdf = new Html2Pdf('L', 'LETTER', 'es');
$objProveedores = new Proveedor();
$general = new General();
$registro = $objProveedores->getconsulta();
$logo = $general->mostrar();
if (!empty($logo)) {
    $_SESSION["logo"] = $logo[0]["logo"];
    $_SESSION["n_empresa"] = $logo[0]["nombre"];
    $_SESSION["rif"] = $logo[0]["rif"];
    $_SESSION["telefono"] = $logo[0]["telefono"];
    $_SESSION["email"] = $logo[0]["email"];
    $_SESSION["direccion"] = $logo[0]["direccion"];
}
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
    //$nombreEmpresa = isset($_SESSION["nombre-empresa"]) ? $_SESSION["nombre-empresa"] : 'Nombre no disponible';
    //$rifEmpresa = isset($_SESSION["rif"]) ? $_SESSION["rif"] : 'RIF no disponible';
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
    <h1 style="text-align:center;">Reporte De Proveedores</h1>
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
        if ($datos['status'] != 2) {
            if ($datos["status"] == 1) {
                $status = 'Activo';
            } elseif ($datos["status"] == 0) {
                $status = 'Inactivo';
            }
            $html .= '
                <tr>
                    <td>' .  $datos['cod_prov'] . '</td>
                    <td>' . $datos['rif'] . '</td>
                    <td>' . $datos['razon_social'] . '</td>
                    <td>' . $datos['email'] . '</td>
                    <td>' . $datos['direccion'] . '</td>
                    <td>' . $datos['telefono'] . '</td>
                    <td>' . $datos['nombre'] . '</td>
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
    $html2pdf->output();
}
