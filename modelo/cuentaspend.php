<?php 
require_once "conexion.php";

class CuentasPendientes extends Conexion{

    private $conex;

    public function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }



//METODO PARA EL LISTADO DE CUENTAS POR COBRAR: Filtradas por status 1 y 2 (Pendiente y Pago parcial)
private function mostrar(){
    $sql = "SELECT
	v.cod_venta,
    c.nombre AS cliente,
    v.total,
    v.fecha_vencimiento,
    v.fecha,
    COALESCE(SUM(pr.monto_total),0) AS monto_total, 
    (v.total - COALESCE(SUM(pr.monto_total),0)) AS saldo_pendiente,
    DATEDIFF(v.fecha_vencimiento, CURDATE()) AS dias_restantes,
        CASE	
            WHEN v.status = 3 THEN 'Pagado'
            WHEN v.status = 2 THEN 'Pago parcial'
            WHEN v.fecha_vencimiento < CURDATE() THEN 'Vencido'
            ELSE 'Pendiente'
        END AS estado
    FROM ventas v 
    INNER JOIN clientes c ON v.cod_cliente = c.cod_cliente
    LEFT JOIN pago_recibido pr ON pr.cod_venta = v.cod_venta
    WHERE v.status IN(1,2)
    GROUP BY v.cod_venta, c.nombre, v.total, v.fecha_vencimiento, v.status  
    ORDER BY `v`.`fecha_vencimiento` ASC;";
    $consulta = $this->conex->prepare($sql);
    $resul = $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if($resul){
        return $datos;
    }else{
        return $r=0;
    }

}
    
public function getmostrar(){
    return $this->mostrar();
}

/*METODO PARA EL LISTADO DE CUENTAS POR PAGAR
Acomodar los status de acuerdo a los pagos (como venta)* ademas debo incluir GASTOS*/

private function mostrarCuentasPagar(){
    $sql = "SELECT
	c.cod_compra,
    p.razon_social AS proveedor,
    c.total,
    c.fecha_vencimiento,
    c.fecha,
    COALESCE(SUM(pe.monto_total),0) AS monto_pagado,
    (c.total - COALESCE(SUM(pe.monto_total),0)) AS saldo_pendiente,
    DATEDIFF(c.fecha_vencimiento, CURDATE()) AS dias_restantes,
        CASE	
            WHEN c.fecha_vencimiento < CURDATE() THEN 'Vencido'
            ELSE 'Pendiente'
        END AS estado
    FROM compras c
    INNER JOIN proveedores p ON p.cod_prov = p.cod_prov
    LEFT JOIN pago_emitido pe ON pe.cod_compra = c.cod_compra
    WHERE c.status IN(1,2)
    GROUP BY c.cod_compra, p.razon_social, c.total, c.fecha_vencimiento, c.status  
    ORDER BY `c`.`fecha_vencimiento` ASC;";
    $consulta = $this->conex->prepare($sql);
    $resul = $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if($resul){
        return $datos;
    }else{
        return 0;
    }
}

public function getmostrarCuentasPagar(){
    return $this->mostrarCuentasPagar();
}
}


