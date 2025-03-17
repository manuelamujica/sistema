<?php
require_once 'modelo/compras.php';
require_once 'modelo/productos.php';    
require_once 'modelo/bitacora.php';

$objbitacora = new Bitacora();
$objCompras = new Compra();
$objProducto = new Productos();
$categoria = $objProducto->consultarCategoria(); 
$unidad = $objProducto->consultarUnidad(); 


if (isset($_POST['buscar'])) {
    $resul = $objCompras->getbuscar_p($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($resul);
    exit;
    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Buscar producto', $_POST['buscar'], 'Productos');
} else if(isset($_POST['b_lotes']) && isset($_POST['cod'])){
    $re = $objCompras->buscar_l($_POST['b_lotes'], $_POST['cod']);
    header('Content-Type: application/json');
    echo json_encode($re);
    exit;
}else if (isset($_POST['detallep'])) {
    $detalle = $objCompras->b_detalle($_POST['detallep']);
    header('Content-Type: application/json');
    echo json_encode($detalle);
    exit;
}else if (isset($_POST["registrar"])) {  
    if (!empty($_POST["subtotal"]) && !empty($_POST["total_general"]) && 
        !empty($_POST["cod_prov"]) && !empty($_POST["fecha"])){
        if(isset($_POST['productos'])){
        $objCompras->setCod1($_POST['cod_prov']);
        $objCompras->setsubtotal($_POST['subtotal']);
        $objCompras->settotal($_POST['total_general']);
        $objCompras->setimpuesto_total($_POST['impuesto_total']);
        $objCompras->setfecha($_POST['fecha']);
        $resul=$objCompras->getRegistrarr($_POST['productos']);
        if($resul==1){
            $registrar = [
                "title" => "La compra ha sido registrada exitosamente.",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de compra', $_POST["subtotal"], 'Compras');
        }else{
            $registrar = [
                "title" => "Error al registrar la compra.",
                "icon" => "error"
            ];
        }
        }else{
            $registrar = [
                "title" => "No se encontraron productos en tu solicitud.",
                "icon" => "error"
            ];
        }
    } else{
        $registrar = [
            "title" => "Faltan campos obligatorios.",
            "icon" => "error"
        ];
    }
}else if (isset($_POST['anular'])) {
    if (!empty($_POST['codcom'])) {
        $resul = $objCompras->anular($_POST["codcom"]);

        if ($resul==1) {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "la  compra ha sido eliminada",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Anulación de compra', $_POST["codcom"], 'Compras');
        } elseif ($resul==0) {
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un problema al eliminar la compra",
                "icon" => "error"
            ];
        }
    }
}
$opciones=$objCompras->divisas();
$compra = $objCompras->getconsultar();
$_GET['ruta'] = 'compras';
require_once 'plantilla.php';
