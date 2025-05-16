<?php
require_once 'conexion.php';

class AnalisisRentabilidad extends Conexion {
    private $errores = [];

    public function __construct() {
        parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }

    /*======================================================
    OBTENER ANÁLISIS DE RENTABILIDAD
    ========================================================*/
    public function getAnalisisRentabilidad() {
        $this->conectarBD();
        $sql = "SELECT 
                p.cod_producto,
                p.nombre as producto,
                ar.ventas_totales,
                ar.costo_ventas,
                ar.margen_bruto,
                (ar.margen_bruto / ar.ventas_totales * 100) as rentabilidad,
                ((ar.ventas_totales - ar.costo_ventas) / ar.costo_ventas * 100) as roi
                FROM analisis_rentabilidad ar
                JOIN detalle_productos dp ON ar.cod_detallep = dp.cod_detallep
                JOIN presentacion_producto pp ON dp.cod_presentacion = pp.cod_presentacion
                JOIN productos p ON pp.cod_producto = p.cod_producto
                WHERE MONTH(ar.fecha_calculo) = MONTH(CURRENT_DATE()) 
                AND YEAR(ar.fecha_calculo) = YEAR(CURRENT_DATE())";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
    }

    /*======================================================
    OBTENER RENTABILIDAD POR PRODUCTO
    ========================================================*/
    public function getRentabilidadProducto($cod_producto) {
        $this->conectarBD();
        $sql = "SELECT 
                ar.ventas_totales,
                ar.costo_ventas,
                ar.margen_bruto,
                ar.fecha_inicio,
                ar.fecha_fin
                FROM analisis_rentabilidad ar
                JOIN detalle_productos dp ON ar.cod_detallep = dp.cod_detallep
                JOIN presentacion_producto pp ON dp.cod_presentacion = pp.cod_presentacion
                WHERE pp.cod_producto = :cod_producto
                AND MONTH(ar.fecha_calculo) = MONTH(CURRENT_DATE()) 
                AND YEAR(ar.fecha_calculo) = YEAR(CURRENT_DATE())";
        
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_producto', $cod_producto);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
    }
}   