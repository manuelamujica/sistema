<?php
require_once 'conexion.php';

class Proyecciones extends Conexion {

    public function __construct() {

    }

    public function getProyecciones() {
        // Obtener proyecciones futuras
        parent::conectarBD();
        $sql_futuras = "SELECT pf.*, p.nombre as nombre_producto
                       FROM proyecciones_futuras pf
                       INNER JOIN productos p ON pf.cod_producto = p.cod_producto
                       WHERE pf.fecha_proyeccion >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)
                       AND pf.status = 1
                       ORDER BY pf.fecha_proyeccion DESC";
        
        // Obtener proyecciones históricas
        $sql_historicas = "SELECT ph.*, p.nombre as nombre_producto
                          FROM proyecciones_historicas ph
                          INNER JOIN productos p ON ph.cod_producto = p.cod_producto
                          WHERE ph.fecha_proyeccion >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)
                          AND ph.status = 1
                          ORDER BY ph.fecha_proyeccion DESC";
        
        $stmt_futuras = $this->conex->prepare($sql_futuras);
        $stmt_historicas = $this->conex->prepare($sql_historicas);
        
        $stmt_futuras->execute();
        $stmt_historicas->execute();
        parent::desconectarBD();
        return [
            'futuras' => $stmt_futuras->fetchAll(PDO::FETCH_ASSOC),
            'historicas' => $stmt_historicas->fetchAll(PDO::FETCH_ASSOC)
        ];
    }
} 