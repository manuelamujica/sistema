<?php
require_once 'modelo/venta.php'; 
require_once 'modelo/tpago.php';
require_once 'modelo/pago.php';
$obj=new Venta();
$objpago=new Tpago();
$objp=new Pago();
if(isset($_POST['buscar'])){
    $result=$obj->b_productos($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}else if(isset($_POST['registrarv'])){
    if(!empty($_POST['cod_cliente']) && !empty($_POST['total_general']) && !empty($_POST['fecha_hora'])){
        if(isset($_POST['productos'])){
            $obj->set_total($_POST['total_general']);
            $obj->setfecha($_POST['fecha_hora']);
            $resul=$obj->registrar($_POST['cod_cliente'], $_POST['productos']);
            error_log($resul);
            header('Content-Type: application/json');
            if($resul){
                echo json_encode([
                    'success'=>true,
                    'cod_venta'=>$resul,
                    'total'=>$_POST['total_general'],
                    'fecha'=>$_POST['fecha_hora'],
                    'cliente'=>$_POST['nombre-cliente'],
                    'message' => 'Venta registrada exitosamente'
                ]);
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
            'message' => 'Faltan campos obligatorios: cod_cliente, total_general o fecha_hora'
        ]);
        exit;
    }

}else if(isset($_POST['finalizarp'])){
    if(!empty($_POST['nro_venta']) && !empty($_POST['monto_pagado'])){
        if($_POST['monto_pagado']!=$_POST['monto_pagar']){
            if(isset($_POST['pago'])){
                $objp->set_cod_venta($_POST['nro_venta']);
                $objp->set_montototal($_POST['monto_pagado']);
                $objp->registrar($_POST['pago'], $_POST['monto_pagar']);
            }
        }
    }
}else if(isset($_POST['parcialp'])){
    if(!empty($_POST['codigop'])){
        if(isset($_POST['pago'])){
            $objp->set_cod_pago($_POST['codigop']);
            $objp->set_montototal($_POST['monto_pagar']);
            $objp->set_montodpago($_POST['monto_pagado']);
            $objp->set_cod_venta($_POST['nro_venta']);
            $resul=$objp->parcialp($_POST['pago']);
        }
    }
} else if(isset($_POST['anular'])){
    if(!empty($_POST['cventa'])){
        $obj->anular($_POST['cventa']);
    }
}

$opciones=$objpago->consultar();
$consulta=$obj->consultar();
$_GET['ruta']='venta';
require_once 'plantilla.php';

