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
                    echo "<script>alert('Registrado con exito');
                    location = '?pagina=categorias' </script>";
            }
        }
    }else{
    echo "<script>alert('No se permiten campos vacios');
        location = '?pagina=categorias' </script>";
    }
}


$registro = $objCategoria->mostrar();

$_GET['ruta'] = 'categorias';
require_once 'plantilla.php';
