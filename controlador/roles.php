<?php

require_once "modelo/roles.php"; //requiero al modelo
$objRol= new Rol();

if(isset($_POST['buscar'])){
    $rol=$_POST['buscar'];
    $result=$objRol->buscar($rol);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;

}else if(isset($_POST["guardar"])){
    if(!empty($_POST["rol"])){

        if(!$objRol->buscar($_POST['rol'])){
        #Instanciar los setter
        $objRol->setRol($_POST["rol"]);
        $resul=$objRol->getcrearRol($_POST['permisos']);

        if($resul == 1){
            $registrar = [
                "title" => "Registrado con éxito",
                "message" => "El rol ha sido registrado",
                "icon" => "success"
            ];
        }else{
            $registrar = [
                "title" => "Error",
                "message" => "Hubo un problema al registrar el rol",
                "icon" => "error"
            ];
        }
        }
    }

}

$permiso=$objRol->permisos();

$registro=$objRol->consultar();
$_GET['ruta']='roles';
require_once 'plantilla.php';

