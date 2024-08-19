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
    if(!empty($_POST["tipo_medida"]) && !empty($_POST['presentacion']) && !empty($_POST['cantidad_presentacion'])){
        if(!$objUnidad->buscar($_POST['presentacion'])){
        #Instanciar los setter
        $objUnidad->setTipo($_POST["tipo_medida"]);
        $objUnidad->setPresentacion($_POST["presentacion"]);
        $objUnidad->setCantidad($_POST["cantidad_presentacion"]);
        
        $resul=$objUnidad->getcrearUnidad();

        if($resul == 1){
            echo    "<script>
                        alert('Registrado con éxito');
                        window.location = 'unidad';
                    </script>";
        } else {
            echo    "<script>
                        alert('¡Las unidades de medida no pueden ir vacía o llevar caracteres especiales!');
                    </script>";
            }
        } 
    }

}

//AQUI LLAMO PARA MOSTRAR LOS REGISTROS
$datos = $objUnidad->consultarUnidad();
$_GET['ruta']='unidad';
require_once 'plantilla.php';


