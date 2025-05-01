<?php
require_once 'conexion.php';

class StockMensual extends Conexion {


    public function __construct() {

    }

    public function getStockMensual() {
        parent::conectarBD();
        $sql = "SELECT sm.*, dp.cod_detallep, p.nombre as nombre_producto,
                       pp.presentacion, pp.cantidad_presentacion
                FROM stock_mensual sm
                INNER JOIN detalle_productos dp ON sm.cod_detallep = dp.cod_detallep
                INNER JOIN presentacion_producto pp ON dp.cod_presentacion = pp.cod_presentacion
                INNER JOIN productos p ON pp.cod_producto = p.cod_producto
                WHERE sm.ano = YEAR(CURRENT_DATE)
                ORDER BY sm.mes DESC, p.nombre ASC";
        
        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        parent::desconectarBD();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 