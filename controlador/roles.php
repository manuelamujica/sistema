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
        $resul=$objRol->getcrearRol();

        if($resul == 1){
            echo    "<script>
                        alert('Registrado con éxito');
                        window.location = 'roles';
                    </script>";
        } else {
            echo    "<script>
                        alert('¡Los roles no pueden ir vacios o llevar caracteres especiales!');
                    </script>";
            }
        }
    }

}

$registro=$objRol->consultar();
$_GET['ruta']='roles';
require_once 'plantilla.php';

