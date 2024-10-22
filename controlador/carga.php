<?php
require_once "modelo/carga.php";
require_once "modelo/dcarga.php";

$objcarga = new Carga();
$objcargad = new Dcarga();

// Manejo de búsqueda de carga
if (isset($_POST['buscar'])) {
    $resul = $objcarga->getbuscar($_POST['buscar']);
    header('Content-type: application/json');
    echo json_encode($resul);
    exit;
}else if (isset($_POST['buscar1'])) {
    $producto = $_POST['buscar1'];
    $detalle = $objcargad->verificarDetalleProducto($producto);
    header('Content-type: application/json');

    // Verifica si hay detalles
    if ($detalle && isset($detalle['cod_detallep'])) {
        echo json_encode($detalle);
    } else {
        echo json_encode('');
    }
    exit;
}
// Manejo de guardar carga
else if (isset($_POST['guardar'])) {
    if (!empty($_POST['fecha']) && !empty($_POST['descripcion']) && !empty($_POST['cod_producto'])) {
        $objcarga->setFecha($_POST['fecha']);
        $objcarga->setDes($_POST['descripcion']);
        $resul = $objcarga->getcrear(); // Registrar carga
    

        if ($resul == 1) {
            $producto = $_POST['cod_producto'];
            $cantidad = $_POST['cantidad'];
            $cont = count($producto);
            $cargaExitosa = true; // Para verificar si la carga fue exitosa

            for ($i = 0; $i < $cont; $i++) {
                $detalle = $objcargad->verificarDetalleProducto($producto[$i]);

                if ($detalle && isset($detalle['cod_detallep'])) {
                    // Si el detalle existe, registrar el producto
                    $detallep = $detalle['cod_detallep']; // Acceso correcto a la clave del array

                    $objcargad->setcodp($detallep); // Aquí deberías asegurarte de que se está pasando correctamente
                    $objcargad->setcantidad($cantidad[$i]);
                    $regis = $objcargad->getcrear();

                    if ($regis != 1) {
                        $cargaExitosa = false; // Marcar como no exitosa
                        echo "<script>alert('Error al registrar el producto: " . $producto[$i] . "');</script>";
                    }
                } else {
                    // Si no hay detalle, mostrar mensaje
                    echo "<script>alert('El producto " . $producto[$i] . " no tiene detalle');</script>";
                    $cargaExitosa = false; // Marcar como no exitosa
                }
            }

            if ($cargaExitosa) {
                echo "<script>alert('Carga registrada con éxito'); window.location.href='?pagina=carga';</script>";
            }
        } else {
            echo "<script>alert('Datos no válidos'); window.location.href='?pagina=carga';</script>";
        }
    }
} 
// Manejo de edición de carga
else if (isset($_POST['editar'])) {
    $cod_carga = $_POST['cod_carga'];
    $des = $_POST['descripcion'];
    $objcarga->setCod($cod_carga);
    $objcarga->setDes($des);
    $res = $objcarga->geteditar();
    if ($res == 1) {
        echo "<script>alert('Carga actualizada con éxito'); window.location.href='?pagina=carga'; </script>";
    } else {
        echo "<script>alert('No se pudo actualizar'); window.location.href='?pagina=carga'; </script>";
    }
}

// Código adicional para manejar otras operaciones
$productos = $objcargad->getP();
$detalles = $objcargad->getmos();
$carga = $objcarga->getmosc();
$datos = $objcargad->gettodo();
$_GET['ruta'] = 'carga';
require_once 'plantilla.php';
?>
