<?php
session_start();
require_once "./vendor/autoload.php";
require_once "modelo/venta.php";
use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf('P', 'LETTER', 'es');
$obj=new Venta();

$dato=$obj->v_cliente();

$html = '
<style>
    #t {
        width: 95%; /* Ajuste del ancho de la tabla */
        border-collapse: collapse;
        margin: auto;
    }
    th{
        border: 1px solid black;
        padding: 8px;
        font-size: 12px;
    }
    th {
        background-color: #db6a00;
        color: white;
        text-align: center;
    }
    #td1 {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
    #td2 {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
    }
</style>

<page backtop="7mm" backbottom="10mm">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 150px;">
                <img src="' . $_SESSION["logo"] . '" style="width: 110px; height: auto;">
            </td>
            <td style="text-align: center; font-size: 12px; width: 500px;">
                <strong style="color: red; font-size: 15px;">' . $_SESSION["n_empresa"] . '</strong><br>
                <b>Rif: ' . $_SESSION["rif"] . '</b><br>
                <b>Domicilio Fiscal:</b> ' . $_SESSION["direccion"] . '.<br>
                Barquisimeto Lara - <b>Teléfono:</b> ' . $_SESSION["telefono"] . '<br>
                <b>Email:</b> ' . $_SESSION["email"] . '
            </td>
        </tr>
    </table>

    <br>
    <hr style="border: 0.5px solid black;">
    <br>
    <h1 style="text-align: center; font-size: 18px; color: #db6a00;">Lista de Clientes y sus ventas</h1>
    <table id="t">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cédula/RIF</th>
                <th>Teléfono</th>
                <th>Cantidad de Ventas</th>
                <th>Monto Total</th>
            </tr>
        </thead>
        <tbody>';

foreach ($dato as $datos) {
    if($datos['cantidad_ventas']>0){
    $html .= '
        <tr>
            <td id="td1">' . $datos["nombre"] . ' ' . $datos["apellido"] . '</td>
            <td id="td1">' . $datos['cedula_rif'] . '</td>
            <td id="td1">' . $datos['telefono'] . '</td>
            <td id="td2">' . $datos['cantidad_ventas'] . '</td>
            <td id="td2">' . number_format($datos['monto_total'], 2) . ' Bs</td>
        </tr>';
    }
}

$html .= '
        </tbody>
    </table>

    <page_footer>
        <div style="text-align: center; font-size: 12px; margin-top: 10px;">
            <p>' . $_SESSION["telefono"] . ' | ' . $_SESSION["direccion"] . ' | ' . $_SESSION["email"] . '</p>
        </div>
    </page_footer>
</page>';

$html2pdf->writeHTML($html);
$html2pdf->output();
