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

