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


if (isset($_POST['guardar'])){

    if(!empty($_POST["nombre"]) && !empty($_POST["categoria"]) && !empty($_POST["unidad"]) && !empty($_POST["iva"] && !empty($_POST["costo"]))){

        $objProducto->setNombre($_POST["nombre"]);
        $objProducto->setMarca($_POST["marca"]);
        $objProducto->setCosto($_POST["costo"]);
        $objProducto->setExcento($_POST["iva"]);
        $objProducto->setGanancia($_POST["porcen"]);
        $objProducto->setPresentacion($_POST["presentacion"]);
        $objProducto->setCantPresentacion($_POST["cant_presentacion"]);

        $categoria = $_POST["categoria"];
        $unidad = $_POST['unidad'];

        $result=$objProducto->getregistrar($_POST["unidad"], $_POST["categoria"]);

        if($result == 1){
            $registrar = [
            "title" => "Registrado con éxito",
            "message" => "El producto ha sido registrado",
            "icon" => "success"
            ];
    }else{
            $registrar = [
                "title" => "Error",
                "message" => "Hubo un error al registrar el producto",
                "icon" => "error"
            ];
        }
    } else{
        $registrar = [
            "title" => "Error",
            "message" => "Completa todos los campos",
            "icon" => "error"
        ];
    }
} else if (isset($_POST['editar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['categoria']) && !empty($_POST['costo']) && !empty($_POST['unidad'])){
        $objProducto->setNombre($_POST['nombre']);
        $objProducto->setMarca($_POST['marca']);
        $objProducto->setCosto($_POST['costo']);
        $objProducto->setExcento($_POST['iva']);
        $objProducto->setGanancia($_POST["porcen"]);
        $objProducto->setPresentacion($_POST['presentacion']);
        $objProducto->setCantPresentacion($_POST['cant_presentacion']);

        //$categoria = $_POST["categoria"];
        //$unidad = $_POST['unidad'];

        $result=$objProducto->editar($_POST["codigo"],$_POST["categoria"],$_POST['unidad']);
        
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
} else if(isset($_POST['borrar'])){
    if(!empty($_POST['p_codigo'])){
        $result = $objProducto->eliminar($_POST["p_codigo"]);
        
        if ($result == 'success') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "El producto ha sido actualizado",
                "icon" => "success"
                ];
        } elseif ($result == 'error_detalle') {
            $eliminar = [
                "title" => "Error",
                "message" => "No se puede eliminar porque tiene detalles asociados",
                "icon" => "error"
            ];
            }
        } elseif ($result == 'error_delete') {
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un error al intentar eliminar el producto",
                "icon" => "error"
                ];
        }
    }

$registro = $objProducto->getmostrar();

$_GET['ruta'] = 'productos';
require_once 'plantilla.php';