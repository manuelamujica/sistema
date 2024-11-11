<?php
require_once 'modelo/representantes.php';


$objRepresentante = new Representantes();

if (isset($_POST['buscar'])) {

    $resul = $objRepresentante->getbuscar($_POST['buscar']);
    header('Content-Type: application/json');
    echo json_encode($resul);
    exit;
}else if (isset($_POST["ok"])) {
    $errores = []; 

    // Verifica que los campos obligatorios no estén vacíos
    if (empty($_POST["cedula"]) || empty($_POST["nombre"])) {
        $errores[] = "Los campos  son obligatorios.";
    } else {
        // Validación del campo cédula
        if (!preg_match("/^[0-9\-\.]+$/", $_POST['cedula']) || strlen($_POST['cedula']) < 6 || strlen($_POST['cedula']) > 12) {
            $errores[] = "La cédula debe tener maximo  12 caracteres y solo contener números y signos.";
        } else {
            $cedula = $_POST["cedula"];
            $dato = $objRepresentante->getbuscar($cedula);
            if ($dato) {
                $errores[] = "La cédula ya está registrada.";
            }
        }

        // Validación del campo nombre
        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $_POST['nombre']) || strlen($_POST['nombre']) < 4 || strlen($_POST['nombre']) > 20) {
            $errores[] = "El nombre debe tener maximo 20 caracteres y solo contener letras.";
        }

        // Validación del campo apellido (opcional)
        if (!empty($_POST['apellido'])) {
            if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $_POST['apellido']) || strlen($_POST['apellido']) < 4 || strlen($_POST['apellido']) > 20) {
                $errores[] = "El apellido debe tener maximo  20 caracteres y solo contener letras.";
            }
        }

        // Validación del campo teléfono (opcional)
        if (!empty($_POST['telefono'])) {
            if (!preg_match("/^[0-9\-\+\s]+$/", $_POST['telefono']) || strlen($_POST['telefono']) < 8 || strlen($_POST['telefono']) > 12) {
                $errores[] = "El teléfono debe contener solo números y signos, y tener maximo 12 caracteres.";
            }
        }
    }

    // Si hay errores, se preparan para mostrar
    if (!empty($errores)) {
        $registrar = [
            "title" => "Error",
            "message" => implode(" ", $errores),
            "icon" => "error"
        ];
    } else {
        // Asignar valores a los atributos, permitiendo que apellido y teléfono sean opcionales
        $objRepresentante->setcedula($_POST['cedula']);
        $objRepresentante->setnombre($_POST['nombre']);
        $objRepresentante->setapellido($_POST['apellido']); // Se permite vacío
        $objRepresentante->settelefono($_POST['telefono']); // Se permite vacío
        $objRepresentante->setCod1($_POST['cod_provREPRE']);

        $resul = $objRepresentante->getregistra();

        if ($resul == 1) {
            $registrar = [
                "title" => "Registrado con éxito",
                "message" => "El representante ha sido registrado",
                "icon" => "success"
            ];
        } else {
            $registrar = [
                "title" => "Error",
                "message" => "Hubo un problema al registrar el representante",
                "icon" => "error"
            ];
        }
    }
}
else if (isset($_POST['editarr'])) {

    // Verifica que los campos requeridos no estén vacíos
    if (!empty($_POST["cedula"]) && !empty($_POST["nombre"]) && isset($_POST["status"])) {

        // Verifica si la cédula ha cambiado y si ya existe en la base de datos
        if ($_POST['cedula'] !== $_POST['origin'] && $objRepresentante->getbuscar($_POST['cedula'])) {
            
        } else {
            // Asignación de valores a los atributos del objeto
            $objRepresentante->setcedula($_POST["cedula"]);
            $objRepresentante->setnombre($_POST["nombre"]);

            // Validación del apellido (opcional)
            $apellido = trim($_POST["apellido"]);
            if (!empty($apellido)) {
                $objRepresentante->setapellido($apellido); // Usa el apellido solo si no está vacío
            } else {
                $objRepresentante->setapellido(null); // O puedes omitir esta línea si no deseas actualizar el campo
            }

            // Validación del teléfono (opcional)
            $telefono = trim($_POST["reptel"]);
            if (!empty($telefono)) {
                $objRepresentante->settelefono($telefono); // Usa el teléfono solo si no está vacío
            } else {
                $objRepresentante->settelefono(null); // O puedes omitir esta línea si no deseas actualizar el campo
            }

            $objRepresentante->setStatus($_POST["status"]);
            $objRepresentante->setCod($_POST["cod_representante"]);

            // Llama al método para editar
            $resul = $objRepresentante->getedita();

            // Verifica el resultado de la edición
            if ($resul == 1) {
                $editar = [
                    "title" => "Editado con éxito",
                    "message" => "Los datos del representante han sido actualizados.",
                    "icon" => "success"
                ];
            } else {
                $editar = [
                    "title" => "Error",
                    "message" => "Hubo un problema al editar los datos del representante.",
                    "icon" => "error"
                ];
            }
        }
    } 
} else if (isset($_POST['eliminar'])) {
    if (!empty($_POST['reprCodigo'])) {
        $resul = $objRepresentante->geteliminar($_POST["reprCodigo"]);

   
        if ($resul === 'success_physical_delete') {
            $eliminar = [
                "title" => "Eliminado con éxito",
                "message" => "El representante ha sido eliminado .",
                "icon" => "success"
            ];
        } else {
            $eliminar = [
                "title" => "Error",
                "message" => "Hubo un error al eliminar el representante.",
                "icon" => "error"
            ];
        }
    }
}



$registro = $objRepresentante->getconsulta();

$_GET['ruta'] = 'proveedores';
require_once 'plantilla.php';



$registro = $objRepresentante->getconsulta();

$_GET['ruta'] = 'proveedores';
require_once 'plantilla.php';
