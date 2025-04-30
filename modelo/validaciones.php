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
    
    
}

    public function validarStatus($valor) {
        if($valor > 1 || $valor < 0) {
            return "El campo status es incorrecto";
        }
        return true;
    }

   
    public function validarDecimal($valor, $campo, $min = null, $max = null) {
        // Primero validar formato decimal con punto
        if (!preg_match('/^\d+(\.\d+)?$/', $valor)) {
            return "El campo $campo debe ser un número decimal válido con punto (.) como separador.";
        }
    
        // Convertir a número decimal real
        $numero = floatval($valor);
    
        // Validar rango si se especificó
        if ($min !== null && $numero < $min) {
            return "El campo $campo debe ser mayor o igual a $min.";
        }
        if ($max !== null && $numero > $max) {
            return "El campo $campo debe ser menor o igual a $max.";
        }
    
        return true;
    }


}

