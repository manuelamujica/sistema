<?php  
require_once 'modelo/proveedores.php';

$objProveedores = new Proveedor();

if(isset($_POST['buscar'])){
    $rif=$_POST['buscar'];
    $result=$objProveedores->buscar($rif);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}else if (isset($_POST["guardar"])) {

    if (!empty($_POST["rif"]) && !empty($_POST["razon_social"])&& !empty($_POST["correo"]) && !empty($_POST["direccion"])) {
        
        if(!$objProveedores->buscar($_POST['rif'])){
        $objProveedores->setRif($_POST['rif']);
        $objProveedores->setRazon_Social($_POST['razon_social']);
        $objProveedores->setcorreo($_POST['correo']);
        $objProveedores->setDireccion($_POST['direccion']);

        $resul=$objProveedores->registrar();

        if ($resul ==1) {
        echo    "<script>
                        alert('Registrado con éxito');
                        window.location = 'proveedores';
                    </script>";
        } 
        } 
    }else {
        echo    "<script>
                    alert('¡La campos vacios!');
                </script>";
        }
}

$registro=$objProveedores->consultar();

$_GET['ruta']='proveedores';
require_once 'plantilla.php';

?>