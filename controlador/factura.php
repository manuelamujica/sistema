<?php
session_start();
require_once "./vendor/autoload.php";
require_once "modelo/venta.php";
use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf('P', 'LETTER', 'es');
$obj=new Venta();
$cod_venta=143;
$dventa=$obj->factura($cod_venta);

$html = '
<page backtop="7mm" backbottom="10mm">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width:150px;">
                <img src="' . $_SESSION["logo"] . '" style="width:100px; height:auto;">
            </td>
            <td style="text-align:right; font-size:12px; width:180px;">
                NIT: 71.759.963-9<br>
                Dirección: Calle 44B 92-11
            </td>
            <td style="text-align:right; font-size:12px; width:180px;">
                Teléfono: 300 786 52 49<br>
                ventas@inventorysystem.com
            </td>
            <td style="text-align:center; color:red; width:200px; right=10px">
                <strong>FACTURA N.</strong><br>
                <span style="font-size:12px;">000143</span>
            </td>
        </tr>
    </table>

    <table style="width: 95%; font-size:10px; margin-top: 10px; border-collapse: collapse;">
        <tr>
            <td style="border: 1px solid #666; padding: 5px; background-color: #f9f9f9; width: 60%;">Cliente: DANIEL ROJAS</td>
            <td style="border: 1px solid #666; padding: 5px; background-color: #f9f9f9; text-align:right; width: 40%;">Fecha: 11/04/2024</td>
        </tr>
        <tr>
            <td colspan="2" style="border: 1px solid #666; padding: 5px; background-color: #f9f9f9; width=100%;">Vendedor: marcos aurelio</td>
        </tr>
    </table>

    <table style="width: 100%; font-size:10px; margin-top: 10px; border-collapse: collapse;">
        <tr>
            <th style="border: 1px solid #666; padding: 5px; background-color: #dedede; width: 28%;">Producto</th>
            <th style="border: 1px solid #666; padding: 5px; background-color: #dedede; width: 24%;">Cantidad</th>
            <th style="border: 1px solid #666; padding: 5px; background-color: #dedede; width: 24%;">Valor Unit.</th>
            <th style="border: 1px solid #666; padding: 5px; background-color: #dedede; width: 24%;">Valor Total</th>
        </tr>';

foreach ($dventa as $detalle) {
    $html .= '
        <tr>
            <td style="border: 1px solid #666; padding: 5px; text-align:center;">' . $detalle['producto_nombre'] . '</td>
            <td style="border: 1px solid #666; padding: 5px; text-align:center;">' . $detalle['cantidad'] . '</td>
            <td style="border: 1px solid #666; padding: 5px; text-align:center;">' . ($detalle['porcen_venta'] / 100 + 1) * $detalle['costo'] . 'Bs</td>
            <td style="border: 1px solid #666; padding: 5px; text-align:center;">' . $detalle['importe'] . 'Bs</td>
        </tr>';
}

$html .= '
    </table>

    <table style="width: 100%; font-size:10px; margin-top: 10px; border-collapse: collapse;">
        <tr>
            <td style="width: 60%;"></td>
            <td style="border: 1px solid #666; padding: 5px; background-color: #f9f9f9; text-align:right;">Neto:</td>
            <td style="border: 1px solid #666; padding: 5px; text-align:center;">2.5$</td>
        </tr>
        <tr>
            <td style="width: 60%;"></td>
            <td style="border: 1px solid #666; padding: 5px; background-color: #f9f9f9; text-align:right;">Impuesto:</td>
            <td style="border: 1px solid #666; padding: 5px; text-align:center;">0.4$</td>
        </tr>
        <tr>
            <td style="width: 60%;"></td>
            <td style="border: 1px solid #666; padding: 5px; background-color: #f9f9f9; text-align:right;">Total:</td>
            <td style="border: 1px solid #666; padding: 5px; text-align:center;">2.9$</td>
        </tr>
    </table>

    <page_footer>
        <div style="text-align: center; font-size:8px; margin-top: 10px;">
            <p>' . $_SESSION["telefono"] . ' | ' . $_SESSION["direccion"] . ' | ' . $_SESSION["email"] . '</p>
        </div>
    </page_footer>
</page>';

$html2pdf->writeHTML($html);
$html2pdf->output();

