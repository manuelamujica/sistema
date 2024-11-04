<?php

require_once "modelo/unidad.php";
$objUnidad= new Unidad;

if(isset($_POST['buscar'])){
    $pres=$_POST['buscar'];
    $result=$objUnidad->buscar($pres);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}else if(isset($_POST["guardar"]) || isset($_POST['guardaru'])){
    if(!empty($_POST["tipo_medida"])){
        if(!$objUnidad->buscar($_POST['tipo_medida'])){

        $objUnidad->setTipo($_POST["tipo_medida"]);
        $resul=$objUnidad->getcrearUnidad();

        if($resul == 1){
            $registrar = [
                "title" => "Registrado con éxito",
                "message" => "la unidad de medida ha sido registrada",
                "icon" => "success"
            ];
        }else{
            $registrar = [
                "title" => "Error",
                "message" => "Hubo un problema al registrar la unidad de medida",
                "icon" => "error"
            ];
        }
        } 
    }

}


$datos = $objUnidad->consultarUnidad();
if(isset($_POST["vista"])){
    $_GET['ruta'] = 'productos';
    //exit();
}else{
    $_GET['ruta'] = 'unidad';
}
require_once 'plantilla.php';


