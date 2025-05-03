<?php
#1) Requerir los modelos
require_once 'modelo/productos.php';
require_once 'modelo/bitacora.php';

#Objetos
$objProducto = new Productos();
$objbitacora = new Bitacora();
$categoria = $objProducto->consultarCategoria(); 
$unidad = $objProducto->consultarUnidad();
$marcas = $objProducto->consultarMarca();

//BUSCAR REGISTRAR
if(isset($_POST['buscar'])){
    $result=$objProducto->buscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Buscar producto', $_POST['buscar'], 'Productos');
    exit;

//CONSULTAR DETALLE DEPENDIENDO DEL PRODUCTO(PRESENTACION)
}else if(isset($_POST['detallep'])){
    $result=$objProducto->consultardetalleproducto($_POST['detallep']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;

//REGISTRAR
}elseif (isset($_POST['guardar'])){
    $errores = [];

    try {
        $objProducto->setNombre($_POST['nombre']);
        $objProducto->setMarca($_POST['marca']);
        $objProducto->setPresentacion($_POST['presentacion']);
        $objProducto->setCantPresentacion($_POST['cant_presentacion']);
        $objProducto->setCosto($_POST['costo']);
        $objProducto->setGanancia($_POST['porcen']);
        $objProducto->setExcento($_POST['iva']);

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $rutaImagen = $objProducto->procesar($_FILES['imagen'], null, $_POST['nombre']);
            $objProducto->setImagen($rutaImagen);
        } else {
            $objProducto->setImagen('vista/dist/img/productos/default.png');
        }

        $objProducto->check();

    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }

    if (!empty($errores)) {
        $registrarp = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else {
        if (!empty($_POST["cod_productoR"])) {
            // Si existe el ID del producto, registrar solo la presentación
            $cod_producto = $_POST["cod_productoR"];
            $result = $objProducto->registrar2($_POST['unidad'], $cod_producto);
        } else {
            // Si no existe, registrar un nuevo producto con su presentación
            $result = $objProducto->getRegistrar($_POST['unidad'], $_POST['categoria']);
        }

        if($result == 1){
            $registrarp = [
                "title" => "Registrado con éxito",
                "message" => "El producto ha sido registrado",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Registro de producto', $_POST["nombre"], 'Productos');
        } else {
            $registrarp = [
                "title" => "Error",
                "message" => "Hubo un error al registrar el producto",
                "icon" => "error"
            ];
        }
    }

//EDITAR
} else if (isset($_POST['editar'])){
    $errores = [];

    try {
        $objProducto->setNombre($_POST['nombre']);
        $objProducto->setMarca($_POST['marca']);
        $objProducto->setPresentacion($_POST['presentacion']);
        $objProducto->setCantPresentacion($_POST['cant_presentacion']);
        $objProducto->setCosto($_POST['costo']);
        $objProducto->setGanancia($_POST['porcen']);
        $objProducto->setExcento($_POST['iva']);

        if (isset($_FILES['imagenE']) && $_FILES['imagenE']['error'] === UPLOAD_ERR_OK) {
            $rutaImagen = $objProducto->procesar($_FILES['imagenE'], $_POST['imagenActual'], $_POST['nombre']);
            $objProducto->setImagen($rutaImagen);
        } else {
            $objProducto->setImagen($_POST['imagenActual']);
        }

        $objProducto->check();

    } catch (Exception $e) {
        $errores[] = $e->getMessage();
    }

    if (!empty($errores)) {
        $editar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else {
        $result = $objProducto->editar($_POST['cod_presentacion'], $_POST['cod_producto'], $_POST['categoria'], $_POST['unidad']);
        
        if($result == 1){
            $editar = [
                "title" => "Editado con éxito",
                "message" => "El producto ha sido actualizado",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar producto', $_POST["nombre"], 'Productos');
        } else {
            $editar = [
                "title" => "Error",
                "message" => "Hubo un error al editar el producto",
                "icon" => "error"
            ];
        }
    }

//ELIMINAR
} else if(isset($_POST['borrar'])){
    if(!empty($_POST['present_codigo'])){
        $codigop = $_POST['p_codigo'];
        $codigopresent = $_POST["present_codigo"];

        $result = $objProducto->eliminar($codigop, $codigopresent);

        if ($result == 'success') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "La presentacion ha sido eliminado",
                "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar producto', "Eliminada la presentacion con el código ".$_POST["present_codigo"], 'Productos');
        } elseif ($result == 'error_stock') {
            $eliminar = [
                "title" => "Error",
                "message" => "No se puede eliminar porque tiene stock",
                "icon" => "error"
            ];
        } elseif ($result == 'error_delete') {
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
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar producto', "Eliminado el producto con el código ".$_POST["p_codigo"], 'Productos');
        }
    }
}

$registro = $objProducto->getmostrar();
$datos = $objProducto->getinventario_costo();
if(isset($_POST['compra'])){
    $_GET['ruta']=$_POST['compra'];
}else {
    $_GET['ruta'] = 'productos';
}
require_once 'plantilla.php';