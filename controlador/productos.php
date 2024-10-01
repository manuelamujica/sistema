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

    if(!empty($_POST["nombre"]) && !empty($_POST["marca"]) && !empty($_POST["categoria"]) && !empty($_POST["unidad"]) && !empty($_POST["excento"] && !empty($_POST["costo"]))){

        $objProducto->setNombre($_POST["nombre"]);
        $objProducto->setMarca($_POST["marca"]);
        $objProducto->setCosto($_POST["costo"]);
        $objProducto->setExcento($_POST["excento"]);
        $objProducto->setGanancia($_POST["ganancia"]);

        $categoria = $_POST["categoria"];
        $unidad = $_POST['unidad'];

        $result=$objProducto->getregistrar($unidad, $categoria);

        if($result == 1){
            echo "<script>
            alert('Registrado con exito');
            location = 'usuarios' </script>";
    }else{
            echo "<script>
            alert('No se pudo completar el registro');
            location = 'usuarios' </script>";
        }
    }


}