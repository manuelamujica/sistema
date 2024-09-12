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
                    window.location = 'tpago'
                </script>";
        }else {
            echo "<script>
                    alert('no se pudo registrar');
                    window.location = 'tpago'
                </script>";
            }
        }
    }
}else if(isset($_POST['editar'])){
    if(!empty($_POST['tpago'])){
        if($_POST['tpago'] !== $_POST['origin']){
            if($obj->buscar($_POST['tpago'])){
                echo "<script>
                    alert('el tipo de pago ya existe');
                    window.location = 'tpago'
                </script>";
            }
            
        }else {
            $obj->setmetodo($_POST['tpago']);
            $obj->setstatus($_POST['status']);
            $result=$obj->editar($_POST['codigo']);
            if($result==1){
                echo "<script>
                        alert('modificado con exito');
                        window.location = 'tpago'
                    </script>";
            }else {
                echo "<script>
                        alert('no se pudo modificar');
                        window.location = 'tpago'
                    </script>";
            }
        }
    }
}else if(isset($_POST['borrar'])){
    if(!empty($_POST['tpagoCodigo'])){
    $result = $obj->eliminar($_POST["tpagoCodigo"]);
        if($result == 1){
            echo "<script>alert('se ha eliminado con exito');
            window.location = 'tpago' </script>";
        }else{
            echo "<script>alert('No se pudo eliminar');
            window.location = 'tpago' </script>";
        }
    }
}

$registro=$obj->consultar();

$_GET['ruta'] = 'tpago';
require_once 'plantilla.php';


