<?php
require_once 'conexion.php';

class StockMensual extends Conexion {
    private $errores = [];

    public function __construct() {
        parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }

    /*======================================================
    OBTENER STOCK MENSUAL
    ========================================================*/
    public function obtenerStockMensual() {
        $this->conectarBD();
        $sql = "SELECT 
                p.cod_producto,
                p.nombre as producto,
                sm.stock_inicial,
                sm.stock_final,
                sm.ventas_cantidad,
                sm.rotacion,
                sm.dias_rotacion
                FROM stock_mensual sm
                JOIN detalle_productos dp ON sm.cod_detallep = dp.cod_detallep
                JOIN presentacion_producto pp ON dp.cod_presentacion = pp.cod_presentacion
                JOIN productos p ON pp.cod_producto = p.cod_producto
                WHERE sm.mes = MONTH(CURRENT_DATE()) AND sm.ano = YEAR(CURRENT_DATE())";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
    }

    /*======================================================
    OBTENER STOCK POR PRODUCTO
    ========================================================*/
    public function obtenerStockProducto($cod_producto) {
        $this->conectarBD();
        $sql = "SELECT 
                sm.mes,
                sm.ano,
                sm.stock_inicial,
                sm.stock_final,
                sm.ventas_cantidad,
                sm.rotacion,
                sm.dias_rotacion
                FROM stock_mensual sm
                JOIN detalle_productos dp ON sm.cod_detallep = dp.cod_detallep
                JOIN presentacion_producto pp ON dp.cod_presentacion = pp.cod_presentacion
                WHERE pp.cod_producto = :cod_producto
                AND sm.mes = MONTH(CURRENT_DATE()) AND sm.ano = YEAR(CURRENT_DATE())";
        
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_producto', $cod_producto);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
    }
} 