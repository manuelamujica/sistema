<?php
#1) Requerir los modelos
require_once 'modelo/productos.php';
require_once 'modelo/bitacora.php';

#Objetos
$objProducto = new Productos();
$objbitacora = new Bitacora();
$categoria = $objProducto->consultarCategoria(); 
$unidad = $objProducto->consultarUnidad(); 

//BUSCAR REGISTRAR
if(isset($_POST['buscar'])){
    $result=$objProducto->buscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
    $objbitacora->registrarEnBitacora($_SESSION['cod_usuario'], 'Buscar producto', $_POST['buscar'], 'Productos');

//CONSULTAR DETALLE DEPENDIENDO DEL PRODUCTO(PRESENTACION)
}else if(isset($_POST['detallep'])){
    $result=$objProducto->consultardetalleproducto($_POST['detallep']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;

//REGISTRAR
}elseif (isset($_POST['guardar'])){
    if(!empty($_POST["nombre"]) && !empty($_POST["categoria"]) && !empty($_POST["unidad"]) && !empty($_POST["iva"])){

        $errors = [];
        $marca = '';
        $presentacion = '';
        $cant_presentacion = '';

        // Validación de marca
        if (!empty($_POST['marca'])){
            if (!preg_match('/^[a-zA-ZÀ-ÿ0-9\-\s]+$/', $_POST['marca'])){
                $errors[] = true;  // Marca no válida
            } else {
                $marca = $_POST["marca"];
            }
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

        if (count($errors) > 0) {
            $registrarp = [
                "title" => "Error",
                "message" => "Algunos caracteres ingresados no son permitidos.",
                "icon" => "error"
            ];
        } else{

            $categoria = $_POST["categoria"];
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
    if(!empty($_POST["nombre"]) && !empty($_POST["categoria"]) && !empty($_POST["unidad"]) && !empty($_POST["iva"])){

            $errors = [];
            $marca = '';
            $presentacion = '';
            $cant_presentacion = '';
    
            // Validación de marca
            if (!empty($_POST['marca'])){
                if (!preg_match('/^[a-zA-ZÀ-ÿ0-9\-\s]+$/', $_POST['marca'])){
                    $errors[] = 1;  // Marca no válida
                } else {
                    $marca = $_POST["marca"];
                }
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

            if (count($errors) > 0) {
                $registrarp = [
                    "title" => "Error",
                    "message" => "Algunos caracteres ingresados no son permitidos.",
                    "icon" => "error"
                ];
            } else{

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
if(isset($_POST['compra'])){
    $_GET['ruta']=$_POST['compra'];
}else {
    $_GET['ruta'] = 'productos';
}
require_once 'plantilla.php';