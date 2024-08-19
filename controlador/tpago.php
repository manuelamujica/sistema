<?php
require_once 'modelo/tpago.php';
$obj= new Tpago();

if(isset($_POST['buscar'])){
    $result=$obj->buscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}else if(isset($_POST['registrar'])){
    if(!empty($_POST['tipo_pago'])){
        if(!$obj->buscar($_POST['tipo_pago'])){
        //$obj->setmoneda($_POST['moneda']);
        $obj->setmetodo($_POST['tipo_pago']);

        $result=$obj->incluir();
        if($result==1){
            echo "<script>
                    alert('registado con exito');
                </script>";
        }else {
            echo "<script>
                    alert('no se pudo registrar');
                </script>";
            }
        }
    }
} 

$registro=$obj->consultar();

$_GET['ruta'] = 'tpago';
require_once 'plantilla.php';


