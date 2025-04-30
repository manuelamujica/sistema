<?php
require_once 'modelo/venta.php'; 
require_once 'modelo/tpago.php';
require_once 'modelo/pago.php';
require_once 'modelo/bitacora.php';

$obj=new Venta();
$objbitacora = new Bitacora();
$objpago=new Tpago();
$objp=new Pago();
if(isset($_POST['buscar'])){
    $result=$obj->b_productos($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Buscar producto', $_POST['buscar'], 'Productos');
}else if(isset($_POST['registrarv'])){
    if(!empty($_POST['cod_cliente']) && !empty($_POST['total_general']) && !empty($_POST['fecha_hora'])){
        if(isset($_POST['productos'])){
            $obj->set_total($_POST['total_general']);
            $obj->setfecha($_POST['fecha_hora']);
            $resul=$obj->registrar($_POST['cod_cliente'], $_POST['productos']);
            error_log($resul);
            header('Content-Type: application/json');
            if($resul>0){
                echo json_encode([
                    'success'=>true,
                    'cod_venta'=>$resul,
                    'total'=>$_POST['total_general'],
                    'fecha'=>$_POST['fecha_hora'],
                    'cliente'=>$_POST['nombre-cliente'],
                    'message' => 'Venta registrada exitosamente'
                ]);
                $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de venta', $_POST["total_general"], 'Venta');
            }else{
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al registrar la venta'
                ]);
            }
            exit;
        }else{
            echo json_encode([
                'success' => false,
                'message' => 'No se encontraron productos en la solicitud'
            ]);
            exit;
        }
    }else{
        echo json_encode([
            'success' => false,
            'message' => 'Faltan campos obligatorios'
        ]);
        exit;
    }

}else if(isset($_POST['finalizarp'])){
    if(!empty($_POST['nro_venta']) && !empty($_POST['monto_pagado'])){
        //if($_POST['monto_pagado']!=$_POST['monto_pagar']){
            if(isset($_POST['pago'])){
                $objp->set_cod_venta($_POST['nro_venta']);
                $objp->set_montototal($_POST['monto_pagado']);
                $resul=$objp->registrar($_POST['pago'], $_POST['monto_pagar']);
                if($resul==0){
                    $registrarp = [
                        "title" => "El pago de la venta ha sido registrado exitosamente.",
                        "message" => "La venta se ha completado en su totalidad.",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago', $_POST["monto_pagado"], 'Pago');
                }else if($resul>0){
                    $registrarp = [
                        "title" => "Se ha registrado un pago parcial.",
                        "message" => "El monto pendiente es de ".$resul."Bs.",
                        "icon" => "success"
                    ];
                    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago parcial', $_POST["monto_pagar"], 'Pago');
                }
            }
        //}
    }
}else if(isset($_POST['parcialp'])){
    if(!empty($_POST['codigop'])){
        if(isset($_POST['pago'])){
            $objp->set_cod_pago($_POST['codigop']);
            $objp->set_montototal($_POST['monto_pagar']);
            $objp->set_montodpago($_POST['monto_pagado']);
            $objp->set_cod_venta($_POST['nro_venta']);
            $resul=$objp->parcialp($_POST['pago']);
            if($resul==0){
                $registrarpp = [
                    "title" => "El pago de la venta ha sido registrado exitosamente.",
                    "message" => "La venta se ha completado en su totalidad.",
                    "icon" => "success"
                ];
                $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago parcial completado', $_POST["pago"], 'Pago');
            }else if($resul>0){
                $registrarpp = [
                    "title" => "Se ha registrado un pago parcial.",
                    "message" => "El monto pendiente es de ".$resul."Bs.",
                    "icon" => "success"
                ];
                $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de pago parcial', $_POST["pago"], 'Pago');
            }
        }
    }
} else if(isset($_POST['anular'])){
    if(!empty($_POST['cventa'])){
        $resul=$obj->anular($_POST['cventa']);
        if($resul==1){
            $anular = [
                "title" => "La venta ha sido anulada exitosamente.",
                "message" => "Todos los registros asociados han sido actualizados.",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Anulación de venta', $_POST["cventa"], 'Venta');
        }else{
            $anular = [
                "title" => "Ocurrió un error al intentar anular la venta.",
                "message" => "Inténtelo de nuevo o contacte a soporte.",
                "icon" => "error"
            ];
        }
    }
}

//$datos=$obj->v_cliente();
//$opciones=$objpago->consultar();
$consulta=$obj->consultar();
$_GET['ruta']='venta';
require_once 'plantilla.php';

