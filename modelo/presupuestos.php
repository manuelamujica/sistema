<?php
require_once 'conexion.php';

class Presupuestos extends Conexion {
    private $errores = [];

    public function __construct() {
        parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }

    /*======================================================
    OBTENER PRESUPUESTOS
    ========================================================*/
    public function obtenerPresupuestos() {
        $this->conectarBD();
        $sql = "SELECT 
                cg.cod_cat_gasto,
                cg.nombre as categoria,
                p.monto as presupuesto,
                COALESCE(SUM(g.monto), 0) as gasto_real,
                p.monto - COALESCE(SUM(g.monto), 0) as diferencia,
                CASE 
                    WHEN p.monto > 0 THEN (COALESCE(SUM(g.monto), 0) / p.monto * 100)
                    ELSE 0 
                END as porcentaje_utilizado,
                CASE 
                    WHEN COALESCE(SUM(g.monto), 0) <= p.monto THEN 'success'
                    ELSE 'danger'
                END as estado
                FROM categoria_gasto cg
                LEFT JOIN presupuestos p ON p.cod_cat_gasto = cg.cod_cat_gasto 
                    AND DATE_FORMAT(p.mes, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')
                LEFT JOIN gasto g ON g.cod_cat_gasto = cg.cod_cat_gasto 
                    AND DATE_FORMAT(g.fecha_creacion, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')
                GROUP BY cg.cod_cat_gasto, cg.nombre, p.monto
                ORDER BY cg.nombre";
        
        try {
            $strExec = $this->conex->prepare($sql);
            $resul = $strExec->execute();
            $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
            $this->desconectarBD();
            return $resul ? $datos : [];
        } catch (PDOException $e) {
            error_log("Error en obtenerPresupuestos: " . $e->getMessage());
            $this->desconectarBD();
            return [];
        }
    }

    /*======================================================
    OBTENER CATEGORÍAS
    ========================================================*/
    public function obtenerCategorias() {
        $this->conectarBD();
        $sql = "SELECT cod_cat_gasto, nombre FROM categoria_gasto ORDER BY nombre";
        
        try {
            $strExec = $this->conex->prepare($sql);
            $resul = $strExec->execute();
            $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
            $this->desconectarBD();
            return $resul ? $datos : [];
        } catch (PDOException $e) {
            error_log("Error en obtenerCategorias: " . $e->getMessage());
            $this->desconectarBD();
            return [];
        }
    }

    /*======================================================
    OBTENER DATOS PARA GRÁFICO
    ========================================================*/
    public function obtenerDatosGraficoPresupuestos() {
        $this->conectarBD();
        $sql = "SELECT 
                DATE_FORMAT(p.mes, '%b %Y') as mes_label,
                p.mes as mes_fecha,
                SUM(p.monto) as presupuesto,
                COALESCE(SUM(g.monto), 0) as gasto_real
                FROM presupuestos p
                LEFT JOIN gasto g ON g.cod_cat_gasto = p.cod_cat_gasto 
                    AND DATE_FORMAT(g.fecha, '%Y-%m') = DATE_FORMAT(p.mes, '%Y-%m')
                GROUP BY p.mes
                ORDER BY p.mes";
        
        try {
            $strExec = $this->conex->prepare($sql);
            $resul = $strExec->execute();
            $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
            
            if ($resul) {
                return [
                    'labels' => array_map(function($row) { return $row['mes_label']; }, $datos),
                    'presupuesto' => array_map(function($row) { return floatval($row['presupuesto']); }, $datos),
                    'gasto_real' => array_map(function($row) { return floatval($row['gasto_real']); }, $datos)
                ];
            }
            
            $this->desconectarBD();
            return [
                'labels' => [],
                'presupuesto' => [],
                'gasto_real' => []
            ];
        } catch (PDOException $e) {
            error_log("Error en obtenerDatosGraficoPresupuestos: " . $e->getMessage());
            $this->desconectarBD();
            return [
                'labels' => [],
                'presupuesto' => [],
                'gasto_real' => []
            ];
        }
    }

    /*======================================================
    OBTENER RESUMEN DE PRESUPUESTO
    ========================================================*/
    public function obtenerResumenPresupuesto() {
        $this->conectarBD();
        $sql = "SELECT 
                SUM(p.monto) as presupuesto_total,
                COALESCE(SUM(g.monto), 0) as gasto_real_total
                FROM presupuestos p
                LEFT JOIN gasto g ON g.cod_cat_gasto = p.cod_cat_gasto 
                    AND DATE_FORMAT(g.fecha, '%Y-%m') = DATE_FORMAT(p.mes, '%Y-%m')
                WHERE DATE_FORMAT(p.mes, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')";
        
        try {
            $strExec = $this->conex->prepare($sql);
            $resul = $strExec->execute();
            $datos = $strExec->fetch(PDO::FETCH_ASSOC);
            
            if ($resul && $datos) {
                $presupuestoTotal = floatval($datos['presupuesto_total']);
                $gastoRealTotal = floatval($datos['gasto_real_total']);
                $diferencia = $presupuestoTotal - $gastoRealTotal;
                $porcentajeUtilizado = $presupuestoTotal > 0 ? ($gastoRealTotal / $presupuestoTotal) * 100 : 0;
                
                return [
                    'presupuesto_total' => $presupuestoTotal,
                    'gasto_real_total' => $gastoRealTotal,
                    'diferencia' => $diferencia,
                    'porcentaje_utilizado' => $porcentajeUtilizado
                ];
            }
            
            $this->desconectarBD();
            return [
                'presupuesto_total' => 0,
                'gasto_real_total' => 0,
                'diferencia' => 0,
                'porcentaje_utilizado' => 0
            ];
        } catch (PDOException $e) {
            error_log("Error en obtenerResumenPresupuesto: " . $e->getMessage());
            $this->desconectarBD();
            return [
                'presupuesto_total' => 0,
                'gasto_real_total' => 0,
                'diferencia' => 0,
                'porcentaje_utilizado' => 0
            ];
        }
    }
} 