<?php
require_once 'modelo/venta.php';
$obj=new Venta();
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
            if($resul){
                header('Content-Type: application/json');
                echo json_encode([
                    'success'=>true,
                    'cod_venta'=>$cod_venta,
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
        }else{
        echo "<script>
            alert('no entro en productos');
            location = 'venta' </script>";}
    }else{
        echo "<script>
            alert('algun post');
            location = 'venta' </script>";
    }

}


$consulta=$obj->consultar();
$_GET['ruta']='venta';
require_once 'plantilla.php';

