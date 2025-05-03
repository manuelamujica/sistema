<?php
require_once 'conexion.php';

class Proyecciones extends Conexion {
    private $errores = [];

    public function __construct() {
        parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }

    /*======================================================
    OBTENER PROYECCIONES FUTURAS
    ========================================================*/
    public function obtenerProyeccionesFuturas($periodo = 6) {
        $this->conectarBD();
        $sql = "SELECT 
                p.cod_producto,
                p.nombre as producto,
                pf.fecha_proyeccion,
                pf.periodo_inicio,
                pf.periodo_fin,
                pf.valor_proyectado,
                pf.ventana_ma,
                COALESCE(
                    (SELECT SUM(dv.cantidad * dv.importe)
                     FROM detalle_ventas dv
                     JOIN ventas v ON dv.cod_venta = v.cod_venta
                     JOIN detalle_productos dp ON dv.cod_detallep = dp.cod_detallep
                     JOIN presentacion_producto pp ON dp.cod_presentacion = pp.cod_presentacion
                     WHERE pp.cod_producto = p.cod_producto
                     AND v.fecha >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
                    ), 0) as ventas_actuales
                FROM proyecciones_futuras pf
                JOIN productos p ON pf.cod_producto = p.cod_producto
                WHERE pf.status = 1
                AND pf.periodo_fin <= DATE_ADD(CURRENT_DATE(), INTERVAL ? MONTH)
                GROUP BY p.cod_producto
                ORDER BY ventas_actuales DESC";
        
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(1, $periodo, PDO::PARAM_INT);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        if($resul) {
            return $datos;
        } else {
            return [];
        }
    }

    /*======================================================
    OBTENER PRECISIÓN HISTÓRICA
    ========================================================*/
    public function obtenerPrecisionHistorica() {
        $this->conectarBD();
        $sql = "SELECT 
                p.cod_producto,
                p.nombre as producto,
                AVG(ph.precision_valor) as precision_promedio,
                MAX(ph.precision_valor) as mejor_precision,
                MIN(ph.precision_valor) as peor_precision,
                ph.valor_proyectado,
                ph.valor_real,
                ph.mes
                FROM proyecciones_historicas ph
                JOIN productos p ON ph.cod_producto = p.cod_producto
                WHERE ph.status = 1
                AND ph.fecha_proyeccion >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
                GROUP BY p.cod_producto
                ORDER BY precision_promedio DESC";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        if($resul) {
            return $datos;
        } else {
            return [];
        }
    }

    /*======================================================
    OBTENER HISTÓRICO DE VENTAS
    ========================================================*/
    public function obtenerHistoricoVentas($meses = 6) {
        $this->conectarBD();
        $sql = "SELECT 
                DATE_FORMAT(v.fecha, '%b') as mes,
                SUM(dv.cantidad * dv.importe) as total_ventas
                FROM ventas v
                JOIN detalle_ventas dv ON v.cod_venta = dv.cod_venta
                WHERE v.fecha >= DATE_SUB(CURRENT_DATE(), INTERVAL ? MONTH)
                AND v.status = 3
                GROUP BY MONTH(v.fecha)
                ORDER BY v.fecha";
        
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(1, $meses, PDO::PARAM_INT);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        if($resul) {
            return $datos;
        } else {
            return [];
        }
    }

    /*======================================================
    OBTENER PROYECCIONES POR PRODUCTO
    ========================================================*/
    public function obtenerProyeccionesProducto($cod_producto) {
        $this->conectarBD();
        $sql = "SELECT 
                pf.fecha_proyeccion,
                pf.valor_proyectado,
                ph.valor_real,
                ph.precision_valor,
                DATE_FORMAT(COALESCE(ph.mes, pf.mes), '%b') as mes
                FROM proyecciones_futuras pf
                LEFT JOIN proyecciones_historicas ph ON 
                    pf.cod_producto = ph.cod_producto AND 
                    pf.mes = ph.mes
                WHERE pf.cod_producto = ?
                AND pf.status = 1
                ORDER BY COALESCE(ph.mes, pf.mes)";
        
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(1, $cod_producto, PDO::PARAM_INT);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        if($resul) {
            return $datos;
        } else {
            return [];
        }
    }
}