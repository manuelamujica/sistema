<?php
//require_once 'modelo/descarga.php';

//$objDescarga = new Descarga();

//BUSCAR
if(isset($_POST['buscar'])){
    $resul = $objDescarga->getbuscar($_POST['buscar']);
    header('Content-type: application/json');
    echo json_encode($resul);
    exit;
}


else if(isset($_POST['guardar'])){
    if(!empty($_POST)){}

}




$_GET['ruta'] = 'descarga';
require_once 'plantilla.php';