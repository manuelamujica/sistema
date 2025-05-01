<?php
require_once 'conexion.php';

class AnalisisRentabilidad extends Conexion {
    public function __construct() {
    }

    public function getAnalisisRentabilidad() {
        parent::conectarBD();
        $sql = "SELECT ar.*, dp.cod_detallep, p.nombre as nombre_producto
                FROM analisis_rentabilidad ar
                INNER JOIN detalle_productos dp ON ar.cod_detallep = dp.cod_detallep
                INNER JOIN presentacion_producto pp ON dp.cod_presentacion = pp.cod_presentacion
                INNER JOIN productos p ON pp.cod_producto = p.cod_producto
                WHERE ar.fecha_calculo >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH)
                ORDER BY ar.fecha_calculo DESC";
        
        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        parent::desconectarBD();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}   