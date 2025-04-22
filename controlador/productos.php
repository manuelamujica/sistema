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
    if(!empty($_POST["nombre"]) && !empty($_POST["categoria"]) && !empty($_POST["marca"]) && !empty($_POST["unidad"]) && !empty($_POST["iva"])){

        $errors = [];
        $marca = '';
        $presentacion = '';
        $cant_presentacion = '';

        $imageValid = true;
        $validationPassed = true;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen = $_FILES['imagen'];
            $tipoImagen = $imagen['type'];
            $tamanoImagen = $imagen['size'];
            $imagenTemp = $imagen['tmp_name'];
            $imagenNombre = $imagen['name'];

            // Verificar si el archivo es una imagen válida
            $infoImagen = getimagesize($imagenTemp);
            if ($infoImagen === false) {
                $error = [
                    "title" => "Advertencia",
                    "message" => "El archivo no es una imagen válida",
                    "icon" => "warning"
                ];
                $imageValid = false;
            } else {
                list($ancho, $alto) = $infoImagen;

                if ($ancho > 600 || $alto > 600) {
                    $error = [
                        "title" => "Advertencia",
                        "message" => "Las dimensiones de la imagen deben ser como máximo 600px de ancho y 600px de alto",
                        "icon" => "warning"
                    ];
                    $imageValid = false;
                }

                if ($tamanoImagen > 1024 * 1024 * 5) {
                    $error = [
                        "title" => "Advertencia",
                        "message" => "El tamaño de la imagen es demasiado grande",
                        "icon" => "warning"
                    ];
                    $imageValid = false;
                }

                $tiposPermitidos = array('image/jpeg', 'image/png', 'image/gif');
                if (!in_array($tipoImagen, $tiposPermitidos)) {
                    $error = [
                        "title" => "Advertencia",
                        "message" => "El tipo de imagen no es permitido",
                        "icon" => "warning"
                    ];
                    $imageValid = false;
                }

                if (!$imageValid) {
                    $validationPassed = false;
                }
            }
        } else {
            $objProducto->setImagen('vista/dist/img/productos/default.png');
            $imageValid = false;
        }

        if ($imageValid && isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $objProducto->subirImagen($_FILES['imagen']);
        }

        // Validación de marca
        if (!preg_match('/^[a-zA-ZÀ-ÿ0-9\-\s]+$/', $_POST['marca'])){
               $errors[] = true;  // Marca no válida
        } else {
            $marca = $_POST["marca"];
        }

        // Validación de presentación
        if (!empty($_POST['presentacion'])){
            if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $_POST['presentacion'])){
                $errors[] = true;  
            } else {
                $presentacion = $_POST["presentacion"];
            }
        }

        // Validación de cantidad de presentación
        if (!empty($_POST['cant_presentacion'])){
            if (!preg_match('/^[a-zA-ZÀ-ÿ0-9\s.,]+$/', $_POST['cant_presentacion'])){
                $errors[] = true;  
            } else {
                $cant_presentacion = $_POST["cant_presentacion"];
            }
        }

        // Validación del nombre del producto
        if (!preg_match('/^[a-zA-ZÀ-ÿ0-9\s]+$/', $_POST['nombre'])){
            $errors[] = true;
        } else {
            $nombre = $_POST["nombre"];
        }

        if ($validationPassed && count($errors) === 0) {
            $categoria = $_POST["categoria"];
            $marca = $_POST["marca"];
            $unidad = $_POST['unidad'];

            $objProducto->setNombre($nombre);
            $objProducto->setExcento($_POST["iva"]);
            $objProducto->setCosto($_POST["costo"]);
            $objProducto->setGanancia($_POST["porcen"]);
            $objProducto->setMarca($marca);
            $objProducto->setPresentacion($presentacion);
            $objProducto->setCantPresentacion($cant_presentacion);

            if (!empty($_POST["cod_productoR"])) {
                // Si existe el ID del producto, registrar solo la presentación
                $cod_producto = $_POST["cod_productoR"];
                $result = $objProducto->registrar2($unidad, $cod_producto);
            } else {
                // Si no existe, registrar un nuevo producto con su presentación
                $result = $objProducto->getRegistrar($unidad, $categoria);
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
        } else if (!$validationPassed) {
            $registrarp = $error;
        } else {
            $registrarp = [
                "title" => "Error",
                "message" => "Algunos caracteres ingresados no son permitidos.",
                "icon" => "error"
            ];
        }
    } else {
        $registrarp = [
        "title" => "Error",
        "message" => "Completa todos los campos obligatorios",
        "icon" => "error"
    ];
}
//EDITAR
} else if (isset($_POST['editar'])){
    if(!empty($_POST["nombre"]) && !empty($_POST["categoria"]) && !empty($_POST["marca"]) && !empty($_POST["unidad"]) && !empty($_POST["iva"])){

        $errors = [];
        $marca = 0;
        $presentacion = '';
        $cant_presentacion = '';

        $imageValid = true;
        $validationPassed = true;

        if (isset($_FILES['imagenE']) && $_FILES['imagenE']['error'] === UPLOAD_ERR_OK) {
            $imagen = $_FILES['imagenE'];
            $tipoImagen = $imagen['type'];
            $tamanoImagen = $imagen['size'];
            $imagenTemp = $imagen['tmp_name'];
            $imagenNombre = $imagen['name'];

            // Verificar si el archivo es una imagen válida
            $infoImagen = getimagesize($imagenTemp);
            if ($infoImagen === false) {
                $error = [
                    "title" => "Advertencia",
                    "message" => "El archivo no es una imagen válida",
                    "icon" => "warning"
                ];
                $imageValid = false;
            } else {
                list($ancho, $alto) = $infoImagen;

                if ($ancho > 600 || $alto > 600) {
                    $error = [
                        "title" => "Advertencia",
                        "message" => "Las dimensiones de la imagen deben ser como máximo 600px de ancho y 600px de alto",
                        "icon" => "warning"
                    ];
                    $imageValid = false;
                }

                if ($tamanoImagen > 1024 * 1024 * 5) {
                    $error = [
                        "title" => "Advertencia",
                        "message" => "El tamaño de la imagen es demasiado grande",
                        "icon" => "warning"
                    ];
                    $imageValid = false;
                }

                $tiposPermitidos = array('image/jpeg', 'image/png', 'image/gif');
                if (!in_array($tipoImagen, $tiposPermitidos)) {
                    $error = [
                        "title" => "Advertencia",
                        "message" => "El tipo de imagen no es permitido",
                        "icon" => "warning"
                    ];
                    $imageValid = false;
                }

                if (!$imageValid) {
                    $validationPassed = false;
                }

            }
        } else {
            //si no se sube imagen nueva, mantener anterior
            $imageValid = false;
            $objProducto->setImagen($_POST['imagenActual']);
        }

        if ($imageValid) {
            $objProducto->subirImagen($_FILES['imagenE']);
        }

        // Validación de marca
        if (!preg_match('/^[a-zA-ZÀ-ÿ0-9\-\s]+$/', $_POST['marca'])){
            $errors[] = 1;  // Marca no válida
        } else {
            $marca = $_POST["marca"];
        }

        // Validación de presentación
        if (!empty($_POST['presentacion'])){
            if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $_POST['presentacion'])){
                $errors[] = true;  
            } else {
                $presentacion = $_POST["presentacion"];
            }
        }

        // Validación de cantidad de presentación
        if (!empty($_POST['cant_presentacion'])){
            if (!preg_match('/^[a-zA-ZÀ-ÿ0-9\s.,]+$/', $_POST['cant_presentacion'])){
                $errors[] = true;  
            } else {
                $cant_presentacion = $_POST["cant_presentacion"];
            }
        }

        // Validación del nombre del producto
        if (!preg_match('/^[a-zA-ZÀ-ÿ0-9\s]+$/', $_POST['nombre'])){
            $errors[] = true;
        } else {
            $nombre = $_POST["nombre"];
        }

        if ($validationPassed && count($errors) === 0) {
            $cod_producto = $_POST['cod_producto'];
            $cod_presentacion = $_POST['cod_presentacion'];
            $cat = $_POST["categoria"];
            $uni = $_POST['unidad'];

            $objProducto->setNombre($_POST['nombre']);
            $objProducto->setMarca($_POST['marca']);
            $objProducto->setCosto($_POST['costo']);
            $objProducto->setExcento($_POST['iva']);
            $objProducto->setGanancia($_POST["porcen"]);
            $objProducto->setPresentacion($_POST['presentacion']);
            $objProducto->setCantPresentacion($_POST['cant_presentacion']);

            $result=$objProducto->editar($cod_presentacion, $cod_producto, $cat, $uni);
        
        if($result == 1){
            $editar = [
            "title" => "Editado con éxito",
            "message" => "El producto ha sido actualizado",
            "icon" => "success"
            ];
            $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Editar producto', $_POST["nombre"], 'Productos');
        }else{
            $editar = [
                "title" => "Error",
                "message" => "Hubo un error al editar el producto",
                "icon" => "error"
                ];
            }
        } else if (!$validationPassed) {
            $editar = $error;
        } else {
            $editar = [
                "title" => "Error",
                "message" => "Algunos caracteres ingresados no son permitidos.",
                "icon" => "error"
            ];
        }
    } else {
        $editar = [
        "title" => "Error al editar",
        "message" => "Completa todos los campos obligatorios",
        "icon" => "error"
        ];
}

//ELIMINAR
} else if(isset($_POST['borrar'])){
    if(!empty($_POST['present_codigo'])){

        $codigop = $_POST['p_codigo'];
        $codigopresent = $_POST["present_codigo"];

        $result = $objProducto->eliminar($codigop,$codigopresent);

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
                $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Eliminar producto', "Eliminado el producto con el código ".$_POST["p_codigo"], 'Productos');
        }
    } 
}

/*BUSCAR ELIMINAR DETALLE (AJAX/JSON)
else if(isset($_POST['codigo'])){
    $result=$objProducto->eliminardetalle($_POST['codigo']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}*/

$registro = $objProducto->getmostrar();
$datos = $objProducto->getinventario_costo();
if(isset($_POST['compra'])){
    $_GET['ruta']=$_POST['compra'];
}else {
    $_GET['ruta'] = 'productos';
}
require_once 'plantilla.php';