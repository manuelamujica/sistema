<?php
require_once 'modelo/divisa.php';
$obj=new Divisa();

if(isset($_POST['buscar'])){
    $result=$obj->buscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}else if(isset($_POST['registrar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['simbolo'])){
        if(!$obj->buscar($_POST['nombre'])){
            $obj->setnombre($_POST['nombre']);
            $obj->setsimbolo($_POST['simbolo']);
            
            $result=$obj->incluir();
            if($result==1){
                echo "<script>
                        alert('registado con exito');
                        window.location = 'divisa'
                    </script>";
            }else {
                echo "<script>
                        alert('no se pudo registrar');
                        window.location = 'divisa'
                    </script>";
            }
        }
    }
}else if(isset($_POST['actualizar'])){
    if(!empty($_POST['nombre']) && !empty($_POST['abreviatura'])){
        if($_POST['nombre'] !== $_POST['origin'] && $obj->buscar($_POST['nombre'])){
            echo "<script>
                alert('este nombre ya existe');
                window.location = 'divisa'
            </script>";
        }else {
            $obj->setnombre($_POST['nombre']);
            $obj->setsimbolo($_POST['abreviatura']);
            $obj->setstatus($_POST['status']);
            $result=$obj->editar($_POST['codigo']);
            if($result==1){
                echo "<script>
                        alert('modificado con exito');
                        window.location = 'divisa'
                    </script>";
            }else {
                echo "<script>
                        alert('no se pudo modificar');
                        window.location = 'divisa'
                    </script>";
            }
        }
    }
}else if(isset($_POST['borrar'])){
    if(!empty($_POST['divisaCodigo'])){
    $result = $obj->eliminar($_POST["divisaCodigo"]);
        if($result == 1){
            echo "<script>alert('se ha eliminado con exito');
            window.location = 'divisa' </script>";
        }else{
            echo "<script>alert('No se pudo eliminar');
            window.location = 'divisa' </script>";
        }
    }
}

$consulta=$obj->consultar();
$_GET['ruta']='divisa';
require_once 'plantilla.php';