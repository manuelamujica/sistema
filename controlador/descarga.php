<?php
require_once 'modelo/descarga.php';

$objDescarga = new Descarga();

//BUSCAR DETALLE
if(isset($_POST['buscar'])){
    $resul = $objDescarga->buscar($_POST['buscar']);
    header('Content-type: application/json');
    echo json_encode($resul);
    exit;
}

else if(isset($_POST['guardar'])){
    if(!empty($_POST['fecha']) && !empty($_POST['descripcion'])){

        $objDescarga->setfecha($_POST['fecha']);
        $objDescarga->setdescripcion($_POST['descripcion']);

        foreach ($_POST['productos'] as $index => $producto) {
        if (empty($producto['cantidad'])) {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Error al registrar',
                    text: 'No se aceptan campos vacíos.',
                    confirmButtonText: 'Aceptar',
                    }).then((result) => {
                    if (result.isConfirmed) {
                    $('#modalRegistrarDescarga').modal('show');
                    window.location = 'descarga';
                    }
                });
            </script>";
            exit;
        }
        $objDescarga->setcantidad($producto['cantidad']);
    }

        $resul = $objDescarga->registrar($_POST['productos']);

        if($resul){
            $registrar = [
                "title" => "Registrado con éxito",
                "message" => "La categoría ha sido registrada",
                "icon" => "success"
            ];
        }else{
            $registrar = [
                "title" => "Error",
                "message" => "Hubo un problema al registrar la descarga",
                "icon" => "error"
            ];
        }
    }else{
        $registrar = [
            "title" => "Error",
            "message" => "Hubo un problema al registrar la descarga. Intenta nuevamente",
            "icon" => "error"
        ];
    }
}


$descarga=$objDescarga->consultardescarga();
$_GET['ruta'] = 'descarga';
require_once 'plantilla.php';