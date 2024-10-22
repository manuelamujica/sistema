<?php

require_once "modelo/unidad.php"; //requiero al modelo
$objUnidad= new Unidad;

if(isset($_POST['buscar'])){
    $tipo_medida=$_POST['buscar'];
    $result=$objUnidad->getbuscar($tipo_medida);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}else if(isset($_POST["guardar"])){
    if(preg_match("/^[a-zA-Z]+$/",$_POST["tipo_medida"])){
    if(!empty($_POST["tipo_medida"])){
        if(!$objUnidad->getbuscar($_POST['tipo_medida'])){
        #Instanciar los setter
        $objUnidad->setTipo($_POST["tipo_medida"]);
        
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

}else if(isset($_POST['editar'])){
    
    $cod_unidad = $_POST['cod_unidad'];
    $tipo_medida = $_POST['tipo_medida'];
    $status = $_POST['status'];
    
    $objUnidad->setCod($_POST["cod_unidad"]);
    $objUnidad->setTipo($_POST["tipo_medida"]);
    $objUnidad->setStatus($status);
    $res = $objUnidad->geteditar();
    if($res == 1){
        echo "<script>alert('Información actualizada con éxito'); window.location.href='?pagina=unidad';</script>";
    }else{
        echo "<script>alert('Error al actualizar'); window.location.href='?pagina=unidad';</script>";
    }

}else if(isset($_POST['eliminar'])){
    $cod_unidad = $_POST['eliminar'];
    //$objUnidad->setCod($cod_unidad);
    $resul = $objUnidad->geteliminar($cod_unidad);
    if($resul == 1){
        echo "<script>alert('Eliminado con Ã©xito'); window.location.href='?pagina=unidad';</script>";
    }else{
        echo "<script>alert('No se pudo eliminar'); window.location.href='?pagina=unidad';</script>";
    }
}

//AQUI LLAMO PARA MOSTRAR LOS REGISTROS
$datos = $objUnidad->consultarUnidad();
$_GET['ruta']='unidad';
require_once 'plantilla.php';


