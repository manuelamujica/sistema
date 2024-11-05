<?php

require_once 'modelo/compras.php';
require_once 'modelo/proveedores.php';

$objCompras = new Compra();



if (isset($_POST['buscar'])) {
    $resul = $objCompras->getbuscar_p($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($resul);
    exit;
} else if(isset($_POST['b_lotes']) && isset($_POST['cod'])){
    $re = $objCompras->buscar_l($_POST['b_lotes'], $_POST['cod']);
    header('Content-Type: application/json');
    echo json_encode($re);
    exit;
}else if (isset($_POST["registrar"])) {  
    if (!empty($_POST["subtotal"]) && !empty($_POST["total_general"]) && !empty($_POST["impuesto_total"]) && 
        !empty($_POST["cod_prov"]) && !empty($_POST["fecha"])){
        if(isset($_POST['productos'])){
        $objCompras->setCod1($_POST['cod_prov']);
        $objCompras->setsubtotal($_POST['subtotal']);
        $objCompras->settotal($_POST['total_general']);
        $objCompras->setimpuesto_total($_POST['impuesto_total']);
        $objCompras->setfecha($_POST['fecha']);
        $resul=$objCompras->getRegistrarr($_POST['productos']);
        if($resul){

        }
        }
    }
        /* Establecer valores en el objeto de compras
        $objCompras->setCod1($_POST['cod_prov']);
        $objCompras->setsubtotal($_POST['subtotal']);
        $objCompras->settotal($_POST['total']);
        $objCompras->setfecha($_POST['fecha']);
        $objCompras->setimpuesto_total($_POST['impuesto_total']);
        $objCompras->setdescuento($_POST['descuento']);
        
        // Registrar compra
        $resul = $objCompras->getregistrarr();
        if ($resul == 1) {
            $producto = $_POST['cod_detallep'];
            $cantidad = $_POST['cantidad'];
            $monto = $_POST['monto'];

            // Establecer valores en el objeto de detalle de compra
            $objdcompra->setcod_detallep($producto);
            $objdcompra->setcantidad($cantidad);
            $objdcompra->setmonto($monto);
            
            // Registrar detalle de compra
            $registra = $objdcompra->getregistrar();
            if ($registra == 1) {
                $fecha_vencimiento = $_POST['fecha_vencimiento'];
                $lote = $_POST['lote'];

                // Establecer valores en  detalle de producto
                $objdproducto->setcod_detallep($producto);
                $objdproducto->setfecha_vencimiento($fecha_vencimiento);
                $objdproducto->setlote($lote);
                
                // Registrar producto
                $registrap = $objdproducto->getRegistrad();

                // Mensaje de éxito
                $registrar = [
                    "title" => "Registrado con éxito",
                    "message" => "La compra ha sido registrada",
                    "icon" => "success"
                ];
            } else {
                // Mensaje de error al registrar detalle de compra
                $registrar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al registrar el detalle de la compra",
                    "icon" => "error"
                ];
            }
        } else {
            // Mensaje de error al registrar compra
            $registrar = [
                "title" => "Error",
                "message" => "Hubo un problema al registrar la compra",
                "icon" => "error"
            ];
        }
    } else {
        // Mensaje de error por campos vacíos
        $registrar = [
            "title" => "Error",
            "message" => "Todos los campos son requeridos",
            "icon" => "error"
        ];
    }*/
}else if (isset($_POST['eliminar'])) {
    if (!empty($_POST['compraCodigo'])) {
        $resul = $objCompras->geteliminar($_POST["compraCodigo"]);

        if ($resul === 'success') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "la  compra ha sido eliminado",
                "icon" => "success"
            ];
        } elseif ($resul === 'error_delete') {
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un problema al eliminar la compra",
                "icon" => "error"
            ];
        }
    }
}




//$productos = $objdcompra->getP(); // --------------------------producto del selec
//$detalles = $objdcompra->getconsulta(); // -------------------------detalle_compra 
$compra = $objCompras->getconsultar();
//$registro = $objdcompra->gettodo(); // --------------------------A qui es toda la consulta que muestra todo

$_GET['ruta'] = 'compras';
require_once 'plantilla.php';
