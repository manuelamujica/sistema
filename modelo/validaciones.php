<?php

trait ValidadorTrait {

    public function validarTexto($valor, $campo, $min = 1, $max = 255) {
        $valor = trim($valor);
        if (!preg_match("/^[\p{L}\s]+$/u", $valor)) {  
            return "El campo $campo solo puede contener letras y espacios.";
        }
        if (mb_strlen($valor) < $min || mb_strlen($valor) > $max) {
            return "El campo $campo debe tener entre $min y $max caracteres.";
        }
        return true;
    }

    public function validarDescripcion($valor, $campo, $min = 1, $max = 255) {
        $valor = trim($valor);
        if (!preg_match("/^[\p{L}ñÑ\d\s\.,\-#áéíóúÁÉÍÓÚüÜ]+$/u", $valor)) { // nuevo, agregado por manu
            return "El campo $campo solo puede contener letras, números y algunos signos (.,-#).";
        }
        if (mb_strlen($valor) < $min || mb_strlen($valor) > $max) {
            return "El campo $campo debe tener entre $min y $max caracteres.";
        }
        return true;
    }

    public function validarEmail($valor) {
        $valor = trim($valor);
        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            return "Correo electrónico no válido.";
        }
        return true;
    }

    public function validarTelefono($valor) {
        $valor = trim($valor);
        if (!preg_match("/^[0-9\s\-\(\)]+$/", $valor)) {
            return "Teléfono no válido.";
        }
        return true;
    }

    public function validarNumerico($valor, $campo,  $min = 1, $max = 20) {
        $valor = trim($valor);
        if (!preg_match("/^\d+$/", $valor)) {
            return "El campo $campo solo puede contener números.";
        }
        if (mb_strlen($valor) < $min || mb_strlen($valor) > $max) {
            return "El campo $campo debe tener entre $min y $max dígitos.";
        }
        return true;
    }


    public function validarAlfanumerico($valor, $campo, $min = 1, $max = 255) {
        $valor = trim($valor);
        if (!preg_match("/^[\p{L}\d\s\.,\-#]+$/u", $valor)) {
            return "El campo $campo solo puede contener letras, números y algunos signos (.,-#).";
        }
        if (mb_strlen($valor) < $min || mb_strlen($valor) > $max) {
            return "El campo $campo debe tener entre $min y $max caracteres.";
        }
        return true;
    }
    public function validarStatusInactivo($valor, $campo = 'status') {
        $valor = trim($valor);
        if ($valor == '1') {
            return "El campo $campo debe estar inactivo.";
        }
        return true;
    }
        
    public function validarStatus($valor) {
        if($valor > 1 || $valor < 0) {
            return "El campo status es incorrecto";
        }
        return true;
    }
    
    public function validarDecimal($valor, $campo, $min = 1, $max = 20) {
        $valor = trim($valor);
        
        // Validar que sea un número decimal o entero positivo (sin letras ni símbolos)
        if (!preg_match("/^\d+(\.\d+)?$/", $valor)) {
            return "El campo $campo solo puede contener números o decimales positivos.";
        }
    
        // Verificar la longitud total del número
        if (mb_strlen($valor) < $min || mb_strlen($valor) > $max) {
            return "El campo $campo debe tener entre $min y $max caracteres.";
        }
    
        return true;
    }

    public function validarFecha($valor, $campo) {
        $valor = trim($valor);
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $valor)) {
            return "El campo $campo no es una fecha válida.";
        }
        return true;
    }

    public function validardatetime($valor, $campo) {
        $valor = trim($valor);
        if (!preg_match("/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/", $valor)) {
            return "El campo $campo no es una fecha y hora válida.";
        }
        return true;
    }

    //BACKUP nuevo, agregado por manu
    public function nombreArchivo($nombre) {
        $nombre = trim($nombre);
    
        if (strlen($nombre) < 3 || strlen($nombre) > 50) {
            $this->errores['nombre'] = "El nombre debe tener entre 3 y 50 caracteres.";
        } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $nombre)) {
            $this->errores['nombre'] = "Solo se permiten letras, números, guiones y guiones bajos.";
        } else {
            $this->nombreArchivo = $nombre;
        }
        return true;

    }
    

}

