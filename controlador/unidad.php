<?php

require_once "modelo/unidad.php"; //requiero al modelo
$objUnidad= new Unidad;

if(isset($_POST['buscar'])){
    $pres=$_POST['buscar'];
    $result=$objUnidad->buscar($pres);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}else if(isset($_POST["guardar"])){
    if(!empty($_POST["tipo_medida"])){
        if(!$objUnidad->buscar($_POST['tipo_medida'])){
        #Instanciar los setter
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

//AQUI LLAMO PARA MOSTRAR LOS REGISTROS
$datos = $objUnidad->consultarUnidad();
$_GET['ruta']='unidad';
require_once 'plantilla.php';


