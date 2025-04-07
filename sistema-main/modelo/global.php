<?php

class GlobalVariables {
    private static $data = [];

    // Establecer un valor global
    public static function set($key, $value) {
        self::$data[$key] = $value;
    }

    // Obtener un valor global
    public static function get($key) {
        return isset(self::$data[$key]) ? self::$data[$key] : null;
    }

    // Eliminar un valor global
    public static function remove($key) {
        if (isset(self::$data[$key])) {
            unset(self::$data[$key]);
        }
    }
}