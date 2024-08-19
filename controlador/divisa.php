<?php
require_once 'modelo/divisa.php';
$obj=new Divisa();

if(isset($_POST['buscar'])){
    $result=$obj->buscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;

}else if(isset($_POST['registrar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['simbolo'])){
        if(!$obj->buscar($_POST['nombre'])){
            $obj->setnombre($_POST['nombre']);
            $obj->setsimbolo($_POST['simbolo']);
            
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

$consulta=$obj->consultar();
$_GET['ruta']='divisa';
require_once 'plantilla.php';