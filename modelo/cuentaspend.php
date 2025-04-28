<?php 

require_once "conexion.php";

class CuentasPendientes{

    private $conex;
    private





private function mostrar(){
    $sql = "SELECT
	v.cod_venta,
    c.nombre AS cliente,
    v.total,
    v.fecha_vencimiento,
    v.fecha,
    COALESCE(SUM(pr.monto_total),0) AS monto_total,
    (v.total - COALESCE(SUM(pr.monto_total),0)) AS saldo_pendiente,
        CASE	
            WHEN v.status = 3 THEN 'Pagado'
            WHEN v.fecha_vencimiento < CURDATE() THEN 'Vencido'
            ELSE 'Pendiente'
        END AS estado
    FROM ventas v 
    INNER JOIN clientes c ON v.cod_cliente = c.cod_cliente
    LEFT JOIN pago_recibido pr ON pr.cod_venta = v.cod_venta
    WHERE v.status IN(1,2)
    GROUP BY v.cod_venta, c.nombre, v.total, v.fecha_vencimiento, v.status
    ORDER BY v.fecha_vencimiento ASC;"

}
    
public function getmostrar(){
    return $this->mostrar();
}

}


