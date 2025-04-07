<?php

trait ValidadorTrait {

    // Validación para texto
    public function validarTexto($valor,$campo, $min = 1, $max = 255) {
        if (!preg_match("/^[\p{L}\s]+$/u", $valor)) {
            throw new Exception("El Campo $campo solo puede contener letras y espacios.");
        }
        if (mb_strlen($valor) < $min || mb_strlen($valor) > $max) {
            throw new Exception("Debe tener entre $min y $max caracteres.");
        }
        return $valor;
    }

    // Validación para números
    public function validarNumerico($valor,$campo, $min = 1, $max = 20) {
        if (!preg_match("/^\d+$/", $valor)) {
            throw new Exception("El Campo $campo solo puede contener números.");
        }
        if (mb_strlen($valor) < $min || mb_strlen($valor) > $max) {
            throw new Exception("Debe tener entre $min y $max dígitos.");
        }
        return $valor;
    }

    // Validación alfanumérica (texto y números)
    public function validarAlfanumerico($valor,$campo,$min = 1, $max = 255) {
        if (!preg_match("/^[\p{L}\d\s\.,\-#]+$/u", $valor)) {
            throw new Exception("El campo $campo solo puede contener letras, números y algunos signos (.,-#).");
        }
        if (mb_strlen($valor) < $min || mb_strlen($valor) > $max) {
            throw new Exception("Debe tener entre $min y $max caracteres.");
        }
        return $valor;
    }

    // Validación de correo electrónico
    public function validarEmail($valor) {
        if (!filter_var($valor, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Correo electrónico no válido.");
        }
        return $valor;
    }

    // Validación de teléfono
    public function validarTelefono($valor) {
        if (!preg_match("/^[0-9\s\-\(\)]+$/", $valor)) {
            throw new Exception("Teléfono no válido.");
        }
        return $valor;
    }

    // Validación de imagen
    public function validarImagen($archivo, $tamanoMaximo = 5, $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif']) {
        // Verificar si el archivo es una imagen
        if (!isset($archivo) || $archivo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("No se cargó una imagen o hubo un error en la carga.");
        }

        // Verificar el tipo de archivo
        if (!in_array($archivo['type'], $tiposPermitidos)) {
            throw new Exception("El archivo debe ser una imagen válida (JPEG, PNG, GIF).");
        }

        // Verificar el tamaño del archivo (en bytes)
        if ($archivo['size'] > $tamanoMaximo * 1024 * 1024) { // Convertimos MB a bytes
            throw new Exception("El archivo excede el tamaño máximo permitido de {$tamanoMaximo}MB.");
        }

        // Verificar si es una imagen real (no solo por la extensión)
        if (!getimagesize($archivo['tmp_name'])) {
            throw new Exception("El archivo no es una imagen válida.");
        }

        return true;
    }

    // Método general para validar varios campos
    public function validarConjunto(array $datos, array $reglas): array {
        $validados = [];

        foreach ($reglas as $campo => $regla) {
            if (!isset($datos[$campo])) { // para saber si hay algo en el campo
                throw new Exception("El campo '$campo' es obligatorio.");
            }

            $valor = trim($datos[$campo]);
            
            $tipo = $regla['tipo'];
            $min = $regla['min'] ?? 1;
            $max = $regla['max'] ?? 255;

            switch ($tipo) {
                case 'texto':
                    $validados[$campo] = $this->validarTexto($valor,$campo, $min, $max);
                    break;
                case 'numerico':
                    $validados[$campo] = $this->validarNumerico($valor,$campo, $min, $max);
                    break;
                case 'alfanumerico':
                    $validados[$campo] = $this->validarAlfanumerico($valor,$campo, $min, $max);
                    break;
                case 'email':
                    $validados[$campo] = $this->validarEmail($valor);
                    break;
                case 'telefono':
                    $validados[$campo] = $this->validarTelefono($valor);
                    break;
                case 'imagen':
                    $validados[$campo] = $this->validarImagen($datos[$campo], $regla['tamanoMaximo'], $regla['tiposPermitidos']);
                    break;
                default:
                    throw new Exception("Tipo de validación '$tipo' no soportado.");
            }
        }

        return $validados;
    }
}