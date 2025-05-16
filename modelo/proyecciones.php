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
            COALESCE((
                SELECT SUM(dv.cantidad * dv.importe)
                FROM detalle_ventas dv
                JOIN ventas v ON dv.cod_venta = v.cod_venta
                JOIN detalle_productos dp ON dv.cod_detallep = dp.cod_detallep
                JOIN presentacion_producto pp ON dp.cod_presentacion = pp.cod_presentacion
                WHERE pp.cod_producto = p.cod_producto
                AND v.fecha >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
                AND v.status = 3
            ), 0) as ventas_actuales,
            (
                SELECT pf2.valor_proyectado
                FROM proyecciones_futuras pf2
                WHERE pf2.cod_producto = p.cod_producto
                AND pf2.mes = DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 3 MONTH), '%Y-%m-01')
                LIMIT 1
            ) as proyeccion_3m,
            (
                SELECT pf3.valor_proyectado
                FROM proyecciones_futuras pf3
                WHERE pf3.cod_producto = p.cod_producto
                AND pf3.mes = DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 6 MONTH), '%Y-%m-01')
                LIMIT 1
            ) as proyeccion_6m,
            (
                SELECT pf4.valor_proyectado
                FROM proyecciones_futuras pf4
                WHERE pf4.cod_producto = p.cod_producto
                AND pf4.mes = DATE_FORMAT(DATE_ADD(CURRENT_DATE(), INTERVAL 12 MONTH), '%Y-%m-01')
                LIMIT 1
            ) as proyeccion_12m
            FROM productos p
            WHERE EXISTS (
                SELECT 1 
                FROM proyecciones_futuras pf 
                WHERE pf.cod_producto = p.cod_producto
            )
            GROUP BY p.cod_producto, p.nombre
            ORDER BY p.nombre";
        
        try {
            $strExec = $this->conex->prepare($sql);
            $resul = $strExec->execute();
            $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculate tendencia based on 6-month projection vs current sales
            foreach ($datos as &$row) {
                $row['tendencia'] = ($row['proyeccion_6m'] > $row['ventas_actuales']) ? 'up' : 'down';
            }
            
            $this->desconectarBD();
            return $resul ? $datos : [];
        } catch (PDOException $e) {
            error_log("Error en obtenerProyeccionesFuturas: " . $e->getMessage());
            $this->desconectarBD();
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
                ph.mes,
                ph.valor_proyectado,
                ph.valor_real,
                ph.precision_valor,
                ph.ventana_ma,
                ph.create_at
                FROM proyecciones_historicas ph
                JOIN productos p ON ph.cod_producto = p.cod_producto
                WHERE ph.mes >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
                ORDER BY ph.mes DESC";
        
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
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
        
        return $resul ? $datos : [];
    }

    /*======================================================
    OBTENER PROYECCIONES POR PRODUCTO
    ========================================================*/
    public function obtenerProyeccionesProducto($cod_producto) {
        $this->conectarBD();
        $sql = "SELECT 
                pf.mes,
                pf.valor_proyectado,
                ph.valor_real,
                ph.precision_valor,
                pf.ventana_ma,
                pf.create_at
                FROM proyecciones_futuras pf
                LEFT JOIN proyecciones_historicas ph ON 
                    pf.cod_producto = ph.cod_producto AND 
                    pf.mes = ph.mes
                WHERE pf.cod_producto = ?
                ORDER BY pf.mes";
        
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(1, $cod_producto, PDO::PARAM_INT);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        $this->desconectarBD();
        
        return $resul ? $datos : [];
    }

    /*======================================================
    OBTENER DATOS PARA GRÁFICO PRINCIPAL
    ========================================================*/
    public function obtenerDatosGrafico($meses_historico = 6, $meses_proyeccion = 12) {
        $this->conectarBD();
        
        // ventas historicas 3 meses
        $sql_historico = "SELECT 
            DATE_FORMAT(v.fecha, '%Y-%m-01') as mes,
            SUM(dv.cantidad * dv.importe) as total_ventas
            FROM ventas v
            JOIN detalle_ventas dv ON v.cod_venta = dv.cod_venta
            WHERE v.fecha >= DATE_SUB(CURRENT_DATE(), INTERVAL 3 MONTH)
            AND v.status = 3
            GROUP BY DATE_FORMAT(v.fecha, '%Y-%m-01')
            ORDER BY mes";
        
        // proyecciones futuras suma 12 meses
        $sql_proyecciones = "SELECT 
            pf.mes,
            SUM(pf.valor_proyectado) as total_proyectado
            FROM proyecciones_futuras pf
            WHERE pf.mes >= CURRENT_DATE()
            AND pf.mes <= DATE_ADD(CURRENT_DATE(), INTERVAL 12 MONTH)
            GROUP BY pf.mes
            ORDER BY pf.mes";
            
        // proyecciones historicas suma ult 6 meses
        $sql_historicas = "SELECT 
            ph.mes,
            SUM(ph.valor_proyectado) as total_proyectado,
            SUM(ph.valor_real) as total_real
            FROM proyecciones_historicas ph
            WHERE ph.mes >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
            AND ph.mes <= CURRENT_DATE()
            GROUP BY ph.mes
            ORDER BY ph.mes";
        
        try {
            //datos historicos 3 meses
            $stmt_historico = $this->conex->prepare($sql_historico);
            $stmt_historico->execute();
            $datos_historico = $stmt_historico->fetchAll(PDO::FETCH_ASSOC);
            
            //proyecciones12 meses
            $stmt_proyecciones = $this->conex->prepare($sql_proyecciones);
            $stmt_proyecciones->execute();
            $datos_proyecciones = $stmt_proyecciones->fetchAll(PDO::FETCH_ASSOC);
            
            //proyecciones historicas
            $stmt_historicas = $this->conex->prepare($sql_historicas);
            $stmt_historicas->execute();
            $datos_historicas = $stmt_historicas->fetchAll(PDO::FETCH_ASSOC);
            
            //datos para grafico
            $resultado = [
                'historico' => [
                    'labels' => array_map(function($row) {
                        return date('M Y', strtotime($row['mes']));
                    }, $datos_historico),
                    'valores' => array_map(function($row) {
                        return floatval($row['total_ventas']);
                    }, $datos_historico)
                ],
                'proyecciones' => [
                    'labels' => array_map(function($row) {
                        return date('M Y', strtotime($row['mes']));
                    }, $datos_proyecciones),
                    'valores' => array_map(function($row) {
                        return floatval($row['total_proyectado']);
                    }, $datos_proyecciones)
                ],
                'historicas' => [
                    'labels' => array_map(function($row) {
                        return date('M Y', strtotime($row['mes']));
                    }, $datos_historicas),
                    'valores' => array_map(function($row) {
                        return floatval($row['total_proyectado']);
                    }, $datos_historicas),
                    'reales' => array_map(function($row) {
                        return floatval($row['total_real']);
                    }, $datos_historicas)
                ]
            ];
            
            return $resultado;
        } catch (PDOException $e) {
            error_log("Error en obtenerDatosGrafico: " . $e->getMessage());
            $this->desconectarBD();
            return [
                'historico' => [
                    'labels' => [],
                    'valores' => []
                ],
                'proyecciones' => [
                    'labels' => [],
                    'valores' => []
                ],
                'historicas' => [
                    'labels' => [],
                    'valores' => [],
                    'reales' => []
                ]
            ];
        } finally {
            $this->desconectarBD();
        }
    }

    /*======================================================
    OBTENER PROYECCIONES HISTÓRICAS
    =========================================================*/
    public function obtenerProyeccionesHistoricas($periodo = 6) {
        $this->conectarBD();
        $sql = "SELECT 
            p.cod_producto,
            p.nombre as producto,
            ROUND(AVG(ph.precision_valor), 2) as precision_promedio,
            MAX(ph.precision_valor) as mejor_precision,
            MIN(ph.precision_valor) as peor_precision
            FROM productos p
            JOIN proyecciones_historicas ph ON p.cod_producto = ph.cod_producto
            WHERE ph.mes >= DATE_SUB(CURRENT_DATE(), INTERVAL ? MONTH)
            AND ph.precision_valor IS NOT NULL
            GROUP BY p.cod_producto, p.nombre
            ORDER BY precision_promedio DESC";
        
        try {
            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(1, $periodo, PDO::PARAM_INT);
            $resul = $strExec->execute();
            $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
            
            $this->desconectarBD();
            return $resul ? $datos : [];
        } catch (PDOException $e) {
            error_log("Error en obtenerProyeccionesHistoricas: " . $e->getMessage());
            $this->desconectarBD();
            return [];
        }
    }
}