<?php
#1) Requerir los modelos

require_once 'modelo/productos.php';
require_once 'modelo/categorias.php';
require_once 'modelo/unidad.php';

#Objetos
$objCategoria = new Categoria();
$objProducto = new Productos();
$objUnidad = new Unidad();

$categoria = $objCategoria->getmostrar(); // Obtener los categorias para pasarlos a la vista
$unidad = $objUnidad->consultarUnidad();  // Obtener las unidades de medida para pasarlos a la vista

//REGISTRAR
if(isset($_POST['buscar'])){
    $result=$obj->buscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
    
}elseif (isset($_POST['guardar'])){

    if(!empty($_POST["nombre"]) && !empty($_POST["categoria"]) && !empty($_POST["unidad"]) && !empty($_POST["iva"] && !empty($_POST["costo"]))){

        $objProducto->setNombre($_POST["nombre"]);
        $objProducto->setMarca($_POST["marca"]);
        $objProducto->setExcento($_POST["iva"]);
        $objProducto->setCosto($_POST["costo"]);
        $objProducto->setGanancia($_POST["porcen"]);
        $objProducto->setPresentacion($_POST["presentacion"]);
        $objProducto->setCantPresentacion($_POST["cant_presentacion"]);

        $categoria = $_POST["categoria"];
        $unidad = $_POST['unidad'];

        $result=$objProducto->getregistrar($_POST["unidad"], $_POST["categoria"]);

        if($result == 1){
            $registrarp = [
            "title" => "Registrado con éxito",
            "message" => "El producto ha sido registrado",
            "icon" => "success"
            ];
    }else{
            $registrarp = [
                "title" => "Error",
                "message" => "Hubo un error al registrar el producto",
                "icon" => "error"
            ];
        }
    } else{
        $registrarp = [
            "title" => "Error",
            "message" => "Completa todos los campos",
            "icon" => "error"
        ];
    }
//EDITAR
} else if (isset($_POST['editar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['categoria']) && !empty($_POST['costo']) && !empty($_POST['unidad'])){
        
        $cod_producto = $_POST['cod_producto'];
        $cod_presentacion = $_POST['cod_presentacion'];
        
        var_dump($cod_producto, $cod_presentacion);

        $objProducto->setNombre($_POST['nombre']);
        $objProducto->setMarca($_POST['marca']);
        $objProducto->setCosto($_POST['costo']);
        $objProducto->setExcento($_POST['iva']);
        $objProducto->setGanancia($_POST["porcen"]);
        $objProducto->setPresentacion($_POST['presentacion']);
        $objProducto->setCantPresentacion($_POST['cant_presentacion']);

        
        //$categoria = $_POST["categoria"];
        //$unidad = $_POST['unidad'];

        $result=$objProducto->editar($cod_presentacion, $cod_producto, $_POST["categoria"],$_POST['unidad']);
        
        if($result == 1){
            $editar = [
            "title" => "Editado con éxito",
            "message" => "El producto ha sido actualizado",
            "icon" => "success"
            ];
    }else{
            $editar = [
                "title" => "Error",
                "message" => "Hubo un error al editar el producto",
                "icon" => "error"
            ];
        }
    } else{
        $editar = [
            "title" => "Error",
            "message" => "Completa todos los campos",
            "icon" => "error"
        ];
    }
//ELIMINAR
} else if(isset($_POST['borrar'])){
    if(!empty($_POST['present_codigo'])){

        $codigop = $_POST['p_codigo'];
        $codigopresent = $_POST["present_codigo"];

        var_dump($codigop,$codigopresent);

        $result = $objProducto->eliminar($codigop,$codigopresent);

        if ($result == 'success') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "La presentacion ha sido eliminado",
                "icon" => "success"
                ];
        } elseif ($result == 'error_stock') {
            $eliminar = [
                "title" => "Error",
                "message" => "No se puede eliminar porque tiene stock",
                "icon" => "error"
            ];
        }elseif ($result == 'error_delete') {
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un error al intentar eliminar el producto",
                "icon" => "error"
                ];
        } elseif ($result == 'producto') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "El producto ha sido eliminado",
                "icon" => "success"
                ];
        }
        } 
    }

$registro = $objProducto->getmostrar();

$_GET['ruta'] = 'productos';
require_once 'plantilla.php';