<?php
require_once 'modelo/compras.php';
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
        if($resul==1){
            $registrar = [
                "title" => "La compra ha sido registrada exitosamente.",
                "icon" => "success"
            ];
        }
        }
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
