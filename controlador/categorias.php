<?php

require_once "modelo/categorias.php"; 

$objCategoria= new Categoria();

if(isset($_POST['buscar'])){
    $nombre = $_POST['buscar']; #Se asigna el valor de buscar a la variable nombre
    $result = $objCategoria->getbuscar($nombre); #Se instancia al metodo buscar y le enviamos por parametro el nombre
    header('Content-Type: application/json'); #establece el encabezado de la respuesta http, indica que el JSON
    echo json_encode($result); #Se envia $result como JSON al cliente 
    exit;

}else if (isset($_POST['guardar'])){

    if(!empty($_POST["nombre"])){

        if (!$objCategoria->getbuscar($_POST["nombre"])){ #Optimizado (Si el metodo buscar no devuelve nada entonces la categoria no existe y se puede registrar)

            $objCategoria->setNombre($_POST["nombre"]);
            $result=$objCategoria->getregistrar();
            
            if($result == 1){
                #PRUEBA USANDO SWEETALERT2
                $registrar = [
                    "title" => "Registrado con éxito",
                    "message" => "La categoría ha sido registrada",
                    "icon" => "success"
                ];
                
            }else{
                $registrar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al registrar la categoría",
                    "icon" => "error"
                ];
            }
        }
    }
}else if (isset($_POST['actualizar'])){
    if(!empty($_POST['nombre'])){
        
        #validacion de que no haya esa categoria registrada
        $objCategoria->setNombre($_POST['nombre']);
        $objCategoria->setStatus($_POST['status']);

        $result=$objCategoria->geteditar($_POST['codigo']);

        if($result == 1){
            $editar = [
                "title" => "Editado con éxito",
                "message" => "La categoría ha sido actualizada",
                "icon" => "success"
            ];
        }else {
            $editar = [
                "title" => "Error",
                "message" => "Hubo un problema al editar la categoría",
                "icon" => "error"
            ];
    }
}
}else if(isset($_POST['borrar'])){
    if(!empty($_POST['catcodigo'])){
    $result = $objCategoria->geteliminar($_POST["catcodigo"]);
    
    if ($result == 'success') {
        $eliminar = [
            "title" => "Eliminado con éxito",
            "message" => "La categoría ha sido eliminada",
            "icon" => "success"
        ];
    } elseif ($result == 'error_associated') {
        $eliminar = [
            "title" => "Error",
            "message" => "La categoría no se puede eliminar porque tiene productos asociados",
            "icon" => "error"
        ];
    } elseif ($result == 'error_delete') {
        $editar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la categoría",
            "icon" => "error"
        ];
    } else {
        $editar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la categoría",
            "icon" => "error"
        ];
    }
}

}

$registro = $objCategoria->getmostrar();

$_GET['ruta'] = 'categorias';
require_once 'plantilla.php';
