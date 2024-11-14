<?php

session_start();

require_once 'vendor/autoload.php';
require_once 'modelo/clientes.php';

use Spipu\Html2Pdf\Html2Pdf;

$obj = new Clientes();
$listado = $obj->consultar();

if (isset($listado)) { //&& $listado != []
    // Inicializa HTML2PDF
    $html2pdf = new Html2Pdf('P', 'LETTER', 'es'); #vertical, formato pagina, idioma

    $fechaHoraGeneracion = date('d-m-Y H:i:s');
    // HTML que se convertirá en PDF
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

    <!-- Tabla de datos -->
    <br>
    <hr style="border=0.5px;">
    <br>
    <h1 style="text-align:center;">Listado de Clientes</h1>
        <table id="t">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cédula</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Direccion</th>
                </tr>
            </thead>
            <tbody>';
    foreach ($listado as $cliente) {
            $html .= '
                <tr>
                    <td>' . $cliente["cod_cliente"] . '</td>
                    <td>' . $cliente["nombre"] . '</td>
                    <td>' . $cliente["apellido"] . '</td>
                    <td>' . $cliente["cedula_rif"] . '</td>
                    <td>' . $cliente["telefono"] . '</td>
                    <td>' .  $cliente["email"] . '</td>
                    <td>' . $cliente["direccion"] . '</td>
                </tr>';
    }
    $html .= '
            </tbody>
        </table>
        <!-- Pie de página -->
            <page_footer>
                <div style="text-align: center;">
                    <p>' . $_SESSION["telefono"] . '  |  ' . $_SESSION["direccion"] . '  |  ' . $_SESSION["email"] . '</p>
                    <p><i>Fecha de generación: ' . $fechaHoraGeneracion . '</i></p>
                    </div>
            </page_footer>
</page>';
    // Escribe el HTML en el PDF
    $html2pdf->writeHTML($html);

    // Muestra el PDF
    $html2pdf->output('reporte-clientes.pdf');
}
