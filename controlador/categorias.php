<?php

require_once "modelo/categorias.php"; 

$objCategoria= new Categoria();

if(isset($_POST['buscar'])){
    $nombre = $_POST['buscar']; #Se asigna el valor de buscar a la variable nombre
    $result = $objCategoria->buscar($nombre); #Se instancia al metodo buscar y le enviamos por parametro el nombre
    header('Content-Type: application/json'); #establece el encabezado de la respuesta http, indica que el JSON
    echo json_encode($result); #Se envia $result como JSON al cliente 
    exit;

}else if (isset($_POST['guardar'])){

    if(!empty($_POST["nombre"])){

        if (!$objCategoria->buscar($_POST["nombre"])){ #Optimizado (Si el metodo buscar no devuelve nada entonces la categoria no existe y se puede registrar)

            $objCategoria->setNombre($_POST["nombre"]);
            $result=$objCategoria->getregistrar();
            
            if($result == 1){
                    echo "<script>
                    alert('Registrado con exito');
                    location = 'categorias' </script>";
            }else{
                    echo "<script>
                    alert('No se permiten campos vacios');
                    location = 'categorias' </script>";
            }
        }
    }
}else if (isset($_POST['actualizar'])){
    if(!empty($_POST['nombre'])){
        
        $objCategoria->setNombre($_POST['nombre']);
        $objCategoria->setStatus($_POST['status']);

        $result=$objCategoria->editar($_POST['codigo']);

        if($result==1){
            echo "<script>
                    alert('modificado con exito');
                    location = 'categorias'
                </script>";
        }else {
            echo "<script>
                    alert('no se pudo modificar');
                    location = 'categorias'
                </script>";
        }
    }
}else if(isset($_POST['borrar'])){
    if(!empty($_POST['catcodigo'])){
    $result = $objCategoria->eliminar($_POST["catcodigo"]);
    
    if ($result == 'success') {
        echo "<script>
                alert('Categoría eliminada exitosamente.');
                location = 'categorias';
              </script>";
    } elseif ($result == 'error_associated') {
        echo "<script>
                alert('No se puede eliminar la categoría porque tiene productos asociados.');
                location = 'categorias';
              </script>";
    } elseif ($result == 'error_delete') {
        echo "<script>
                alert('Hubo un error al intentar eliminar la categoría.');
                location = 'categorias';
              </script>";
    } else {
        echo "<script>
                alert('Hubo un error al intentar verificar la categoría.');
                location = 'categorias';
              </script>";
    }
}

}

$registro = $objCategoria->mostrar();

$_GET['ruta'] = 'categorias';
require_once 'plantilla.php';
