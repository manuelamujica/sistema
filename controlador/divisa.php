<?php
require_once 'modelo/divisa.php';
$obj=new Divisa();

if(isset($_POST['buscar'])){
    $result=$obj->buscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}else if(isset($_POST['registrar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['simbolo']) && !empty($_POST['tasa']) && !empty($_POST['fecha'])){
        if(!$obj->buscar($_POST['nombre'])){
            $obj->setnombre($_POST['nombre']);
            $obj->setsimbolo($_POST['simbolo']);
            $obj->set_tasa($_POST['tasa']);
            $obj->setfecha($_POST['fecha']);
            $result=$obj->incluir();
            if($result==1){
                $registrar = [
                    "title" => "Registrado con éxito",
                    "message" => "La divisa ha sido registrada",
                    "icon" => "success"
                ];
            }else {
                $registrar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al registrar la divisa",
                    "icon" => "error"
                ];
            }
        }
    }
}else if(isset($_POST['actualizar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['abreviatura'])){
        if($_POST['nombre'] !== $_POST['origin'] && $obj->buscar($_POST['nombre'])){
            echo "<script>
                alert('este nombre ya existe');
                window.location = 'divisa'
            </script>";
        }else {
            $obj->setnombre($_POST['nombre']);
            $obj->setsimbolo($_POST['abreviatura']);
            $obj->setstatus($_POST['status']);
            $obj->set_tasa($_POST['tasa']);
            $obj->setfecha($_POST['fecha']);
            $result=$obj->editar($_POST['codigo']);
            if($result==1){
                $editar = [
                    "title" => "Editado con éxito",
                    "message" => "La divisa ha sido actualizada",
                    "icon" => "success"
                ];
            }else {
                $editar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al editar la divisa",
                    "icon" => "error"
                ];
            }
        }
    }
}else if(isset($_POST['borrar'])){
    if(!empty($_POST['divisaCodigo'])){
    $result = $obj->eliminar($_POST["divisaCodigo"]);
        if($result == 1){
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "La divisa ha sido eliminada",
                "icon" => "success"
            ];
        }else{
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un problema al eliminar la divisa",
                "icon" => "error"
            ];
        }
    }
}

$consulta=$obj->consultar();
$_GET['ruta']='divisa';
require_once 'plantilla.php';