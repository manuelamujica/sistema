<?php

require_once "modelo/categorias.php"; 

$objCategoria= new Categoria();

if(isset($_POST['buscar'])){
    $nombre = $_POST['buscar']; #Se asigna el valor de buscar a la variable nombre
    $result = $objCategoria->getbuscar($nombre); #Se instancia al metodo buscar y le enviamos por parametro el nombre
    header('Content-Type: application/json'); #establece el encabezado de la respuesta http, indica que el JSON
    echo json_encode($result); #Se envia $result como JSON al cliente 
    exit;

}else if (isset($_POST['guardar']) || isset($_POST['registrarc'])){ #Si viene de productos o de categoria

    if(!empty($_POST['nombre']) && preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $_POST['nombre']) && strlen($_POST['nombre']) <= 40) {

        if (!$objCategoria->getbuscar($_POST["nombre"])){ #Optimizado (Si el metodo buscar no devuelve nada entonces la categoria no existe y se puede registrar)

            $objCategoria->setNombre($_POST["nombre"]);
            $result=$objCategoria->getregistrar();
            
            if($result == 1){
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
    }else {
        $registrar = [
        "title" => "Error",
        "message" => "Hubo un problema al registrar la categoría. Intenta nuevamente",
        "icon" => "error"
        ];
    }
}else if (isset($_POST['actualizar'])){
    if(!empty($_POST['nombre']) && preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $_POST['nombre']) && strlen($_POST['nombre']) <= 40) {
        
        if($_POST['nombre'] !== $_POST['origin']){
            // Si la categoria cambió, verificamos si ya existe en la base de datos
            if ($objCategoria->buscar($_POST['nombre'])) {
                $editar = [
                    "title" => "Advertencia",
                    "message" => "La categoria ya está registrada.",
                    "icon" => "warning"
                ];
            }
        } else {

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
}else{
    $editar = [
        "title" => "Error",
        "message" => "Algunos caracteres ingresados no son permitidos.",
        "icon" => "error"
    ];
}

}else if(isset($_POST['borrar'])){
    if(!empty($_POST['catcodigo']) && $_POST['statusDelete'] !== '1'){ //Eliminar solo si el status es inactivo
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
        $eliminar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la categoría",
            "icon" => "error"
        ];
    } else {
        $eliminar = [
            "title" => "Error",
            "message" => "Hubo un problema al eliminar la categoría",
            "icon" => "error"
        ];
    }
} else {$eliminar = [
    "title" => "Error",
    "message" => "No se puede eliminar una categoría activa",
    "icon" => "error"
];
}

}

$registro = $objCategoria->getmostrar();

if(isset($_POST["vista"])){
    $_GET['ruta'] = 'productos';
    //exit();
}else{
    $_GET['ruta'] = 'categorias';
}
require_once 'plantilla.php';
