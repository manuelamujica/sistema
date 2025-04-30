<?php
require_once 'conexion.php';
require_once 'validaciones.php';
class Gastos extends Conexion
{
    use ValidadorTrait;
    private $errores = [];
    private $conex;
    private $datos = [];
    #VARIABLES DEL AJUSTE DE GASTOS
    private $cod_frecuencia; //
    private $dias; //
    private $frecuencia; //
    private $cod_cat_gasto; //
    private $cod_tipo_gasto; //
    private $nombreC; //
    private $fecha; //
    private $status_cat_gasto;
    //SETTER Y GETTER DE GASTOS
    private $cod_gasto; //
    private $descripcion; //
    private $monto; //
    private $status;

    public function check()
    {
        if (!empty($this->errores)) {
            $mensajes = implode(" | ", $this->errores);
            throw new Exception("Errores de validación: $mensajes");
        }
    }

    // Si quieres acceder a los errores individualmente
    public function getErrores()
    {
        return $this->errores;
    }

    //SETTER Y GETTER 
    public function setDatos(array $datos)
    {
        foreach ($datos as $key => $value) {
            switch ($key) {

                case 'status_cat_gasto':
                    if (is_numeric($value)) {
                        $this->status_cat_gasto = $value;
                    } else {
                        $this->errores[] = "El campo $key debe ser numérico.";
                    }
                    break;
                case 'cod_frecuencia':
                    if (is_numeric($value)) {
                        $this->cod_frecuencia = $value;
                    } else {
                        $this->errores[] = "El campo $key debe ser numérico.";
                    }
                    break;
                case 'cod_cat_gasto':
                    if (is_numeric($value)) {
                        $this->cod_cat_gasto = $value;
                    } else {
                        $this->errores[] = "El campo $key debe ser numérico.";
                    }
                    break;
                case 'cod_tipo_gasto':
                    if (is_numeric($value)) {
                        $this->cod_tipo_gasto = $value;
                    } else {
                        $this->errores[] = "El campo $key debe ser numérico.";
                    }
                    break;
                case 'cod_gasto':
                    if (is_numeric($value)) {
                        $this->cod_gasto = $value;
                    } else {
                        $this->errores[] = "El campo $key debe ser numérico.";
                    }
                    break;
                case 'monto':
                    if (is_numeric($value)) {
                        $this->monto = $value;
                    } else {
                        $this->errores[] = "El campo $key debe ser numérico.";
                    }
                    break;
                case 'dias':
                    if (is_numeric($value)) {
                        $this->dias = $value;
                    } else {
                        $this->errores[] = "El campo $key debe ser numérico.";
                    }
                    break;
                case 'nombreC':
                case 'frecuencia':
                    $res = $this->validarTexto($value, $key, 2, 50);
                    if ($res === true) {
                        $this->frecuencia = $value;
                    } else {
                        $this->errores[] = $res;
                    }
                    break;
                case 'descripcion':
                    $res = $this->validarTexto($value, $key, 2, 50);
                    if ($res === true) {
                        $this->descripcion = $value;
                    } else {
                        $this->errores[] = $res;
                    }
                    break;
                case 'fecha:':
                    $this->fecha = $value;
                    break;
            }
            // Asignar el valor al arreglo dinámico
            $this->datos[$key] = $value;
        }
    }
    public function getDatos()
    {
        return $this->datos;
    }
    public function __construct()
    {
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

    #SECCIÓN DE AJUSTES DE GASTOS
    private function registrarF()
    {
        if (!empty($this->frecuencia) && !empty($this->dias)) {
            $registro = "INSERT INTO frecuencia_gasto(nombre, dias) VALUES(:nombre, :dias)";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':nombre', $this->frecuencia);
            $strExec->bindParam(':dias', $this->dias);
            $resul = $strExec->execute();
            if ($resul == 1) {
                $r = 1;
            } else {
                $r = 0;
            }
        } else {
            $r = 2;
        }

        return $r;
    }

    public function publicregistrarf()
    {
        return $this->registrarF();
    }

    private function consultarF()
    {
        $registro = "SELECT * FROM frecuencia_gasto";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }
    public function consultarFrecuencia()
    {
        return $this->consultarF();
    }

    private function consultarT()
    {
        $sql = "SELECT * FROM tipo_gasto";
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }
    public function consultarTipo()
    {
        return $this->consultarT();
    }

    private function registrarC()
    {
        if (!empty($this->cod_frecuencia) && !empty($this->cod_tipo_gasto) && !empty($this->nombreC) && !empty($this->fecha)) {

            $registro = "INSERT INTO categoria_gasto(cod_tipo_gasto, cod_frecuencia, nombre, fecha, status_cat_gasto) VALUES(:cod_tipo_gasto, :cod_frecuencia, :nombre, :fecha, 1)";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':cod_frecuencia', $this->cod_frecuencia);
            $strExec->bindParam(':cod_tipo_gasto', $this->cod_tipo_gasto);
            $strExec->bindParam(':nombre', $this->nombreC);
            $strExec->bindParam(':fecha', $this->fecha);
            $resul = $strExec->execute();
            if ($resul == 1) {
                $r = 1;
            } else {
                $r = 0;
            }
            return $r;
        }
    }
    public function publicregistrarc()
    {
        return $this->registrarC();
    }

    private function consultarC()
    {
        $sql = "SELECT c.status_cat_gasto,c.fecha, c.cod_cat_gasto, c.cod_tipo_gasto, c.cod_frecuencia, c.nombre, f.cod_frecuencia, f.nombre, t.cod_tipo_gasto, t.nombre AS nombret,
        f.nombre AS nombref FROM categoria_gasto c
        JOIN tipo_gasto t ON c.cod_tipo_gasto = t.cod_tipo_gasto
        JOIN frecuencia_gasto f ON c.cod_frecuencia = f.cod_frecuencia";
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }

    public function consultarCategoria()
    {
        return $this->consultarC();
    }

    private function buscarTporC()
    {
        $sql = "SELECT c.cod_cat_gasto, c.cod_tipo_gasto, c.nombre, t.cod_tipo_gasto, t.nombre AS nombret
                FROM categoria_gasto c
                JOIN tipo_gasto t ON c.cod_tipo_gasto = t.cod_tipo_gasto
                WHERE c.cod_cat_gasto = :cod_cat_gasto";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_cat_gasto', $this->cod_cat_gasto);
        $resul = $strExec->execute();
        $datos = $strExec->fetch(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }

    public function buscarTporCategoria()
    {
        return $this->buscarTporC();
    }

    private function bC()
    {
        $sql = "SELECT nombre FROM categoria_gasto WHERE nombre = :nombre";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':nombre', $this->nombreC);
        $resul = $strExec->execute();
        $datos = $strExec->fetch(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }


    public function buscarCategoria()
    {
        return $this->bc();
    }

    private function bF()
    {
        $sql = "SELECT nombre FROM frecuencia_gasto WHERE nombre = :nombre";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':nombre', $this->frecuencia);
        $resul = $strExec->execute();
        $datos = $strExec->fetch(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }
    public function buscarFrecuencia()
    {
        return $this->bF();
    }

    private function EditC()
    {
        $registro = "UPDATE categoria_gasto SET cod_frecuencia = :cod_frecuencia, status_cat_gasto = :status_cat_gasto , nombre = :nombre, fecha = :fecha WHERE cod_cat_gasto = :cod_cat_gasto";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':cod_frecuencia', $this->cod_frecuencia);
        $strExec->bindParam(':cod_cat_gasto', $this->cod_cat_gasto);
        $strExec->bindParam(':status_cat_gasto', $this->status_cat_gasto);
        $strExec->bindParam(':nombre', $this->nombreC);
        $strExec->bindParam(':fecha', $this->fecha);
        $res = $strExec->execute();
        if ($res) {
            return 1;
        } else {
            return 0;
        }
    }

    public function editarC()
    {
        return $this->EditC();
    }



    //FUNCIONES DE GASTOS
    
    private function registrarG()
    {
        $resp = $this->buscarG();
        if ($resp == null) {
            $registro = "INSERT INTO gasto(cod_cat_gasto,detgasto_descripcion, detgasto_monto, detgasto_status) VALUES(:cod_cat_gasto ,:detgasto_descripcion, :detgasto_monto, 1)";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':cod_cat_gasto', $this->cod_cat_gasto);
            $strExec->bindParam(':detgasto_descripcion', $this->descripcion);
            $strExec->bindParam(':detgasto_monto', $this->monto);
            $resul = $strExec->execute();
            if ($resul == 1) {
                $r = 1;
            } else {
                $r = 0;
            }
        }else{
            $r = 2;
        }
        return $r;
    }
    public function publicregistrarg()
    {
        return $this->registrarG();
    }

    private function consultarV()
    {
        $valor = "variable";
        $registro = "SELECT 
                    p.cod_pago_emitido, 
                    p.fecha, 
                    p.cod_vuelto_r, 
                    v.vuelto_total, 
                    v.status_vuelto,
                    detV.monto AS monto_detallev,
                    SUM(detp.monto) AS monto_detalle,  
                    p.monto_total, 
                    g.cod_gasto, 
                    g.cod_cat_gasto, 
                    g.detgasto_descripcion, 
                    g.detgasto_monto, 
                    g.detgasto_status, 
                    c.nombre AS categoria_nombre, 
                    c.fecha AS fechac, 
                    c.cod_tipo_gasto, 
                    t.nombre AS nombret,
                    dettipo.cod_cuenta_bancaria,
                    dettipo.cod_detalle_caja
                    
                FROM 
                    gasto g
                LEFT JOIN 
                    categoria_gasto c ON g.cod_cat_gasto = c.cod_cat_gasto
                LEFT JOIN 
                    tipo_gasto t ON c.cod_tipo_gasto = t.cod_tipo_gasto
                LEFT JOIN 
                    pago_emitido p ON g.cod_gasto = p.cod_gasto
                LEFT JOIN 
                    detalle_pago_emitido detp ON p.cod_pago_emitido = detp.cod_pago_emitido
                LEFT JOIN detalle_tipo_pago dettipo ON detp.cod_tipo_pagoe = dettipo.cod_tipo_pago
                LEFT JOIN
                    detalle_vueltor detV ON p.cod_vuelto_r = detV.cod_vuelto_r
                LEFT JOIN 
                    vuelto_recibido v ON p.cod_vuelto_r = v.cod_vuelto_r
                WHERE 
                    t.nombre = :nombret
                GROUP BY 
                    p.cod_pago_emitido, p.fecha, p.cod_vuelto_r, v.vuelto_total, v.status_vuelto, 
                    p.monto_total, g.cod_gasto, g.cod_cat_gasto, g.detgasto_descripcion, 
                    g.detgasto_monto, g.detgasto_status, c.nombre, c.fecha, c.cod_tipo_gasto,t.nombre";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombret', $valor);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }
    public function consultarGastoV()
    {
        return $this->consultarV();
    }
    private function consultarGF()
    {
        $valor = "fijo";
        $registro = "SELECT 
                    p.cod_pago_emitido, 
                    p.fecha, 
                    p.cod_vuelto_r, 
                    v.vuelto_total, 
                    v.status_vuelto,
                    detV.monto AS monto_detallev,
                    SUM(detp.monto) AS monto_detalle, 
                    p.monto_total, 
                    g.cod_gasto, 
                    g.cod_cat_gasto, 
                    g.detgasto_descripcion, 
                    g.detgasto_monto, 
                    g.detgasto_status, 
                    c.nombre AS categoria_nombre, 
                    c.fecha AS fechac, 
                    c.cod_tipo_gasto, 
                    t.nombre AS nombret,
                    dettipo.cod_cuenta_bancaria,
                    dettipo.cod_detalle_caja
                    
                FROM 
                    gasto g
                LEFT JOIN 
                    categoria_gasto c ON g.cod_cat_gasto = c.cod_cat_gasto
                LEFT JOIN 
                    tipo_gasto t ON c.cod_tipo_gasto = t.cod_tipo_gasto
                LEFT JOIN 
                    pago_emitido p ON g.cod_gasto = p.cod_gasto
                LEFT JOIN 
                    detalle_pago_emitido detp ON p.cod_pago_emitido = detp.cod_pago_emitido
                LEFT JOIN detalle_tipo_pago dettipo ON detp.cod_tipo_pagoe = dettipo.cod_tipo_pago
                LEFT JOIN
                    detalle_vueltor detV ON p.cod_vuelto_r = detV.cod_vuelto_r
                LEFT JOIN 
                    vuelto_recibido v ON p.cod_vuelto_r = v.cod_vuelto_r
                WHERE 
                    t.nombre = :nombret
                GROUP BY 
                    p.cod_pago_emitido, p.fecha, p.cod_vuelto_r, v.vuelto_total, v.status_vuelto, 
                    p.monto_total, g.cod_gasto, g.cod_cat_gasto, g.detgasto_descripcion, 
                    g.detgasto_monto, g.detgasto_status, c.nombre, c.fecha, c.cod_tipo_gasto,t.nombre ";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombret', $valor);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }
    public function consultarGastoF()
    {
        return $this->consultarGF();
    }

    private function totalV()
    {
        $variable = "variable";
        $registro = "SELECT SUM(g.detgasto_monto) AS total_monto
                 FROM gasto g
                 JOIN categoria_gasto c ON g.cod_cat_gasto = c.cod_cat_gasto
                 JOIN tipo_gasto t ON c.cod_tipo_gasto = t.cod_tipo_gasto
                 WHERE t.nombre = :nombret AND detgasto_status != 3";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombret', $variable);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }
    public function consultarTotalV()
    {
        return $this->totalV();
    }
    private function totalF()
    {
        $variable = "fijo";
        $registro = "SELECT SUM(g.detgasto_monto) AS total_monto
                 FROM gasto g
                 JOIN categoria_gasto c ON g.cod_cat_gasto = c.cod_cat_gasto
                 JOIN tipo_gasto t ON c.cod_tipo_gasto = t.cod_tipo_gasto
                 WHERE t.nombre = :nombret AND detgasto_status != 3";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombret', $variable);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }
    public function consultarTotalF()
    {
        return $this->totalF();
    }
    private function totalG()
    {
        $registro = "SELECT SUM(g.detgasto_monto) AS total_monto FROM gasto g WHERE g.detgasto_status != 3";
        $strExec = $this->conex->prepare($registro);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }
    public function consultarTotalG()
    {
        return $this->totalG();
    }

    private function totalP()
    {
        $registro = "SELECT SUM(g.detgasto_monto) AS total_monto FROM gasto g WHERE g.detgasto_status = 3";
        $strExec = $this->conex->prepare($registro);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }
    public function consultarTotalP()
    {
        return $this->totalP();
    }

    private function editarG()
    {
        $registro = "UPDATE gasto SET detgasto_descripcion = :detgasto_descripcion WHERE cod_gasto = :cod_gasto";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':detgasto_descripcion', $this->descripcion);
        $strExec->bindParam(':cod_gasto', $this->cod_gasto);
        $res = $strExec->execute();
        if ($res) {
            return 1;
        } else {
            return 0;
        }
    }

    public function editarGasto()
    {
        return $this->editarG();
    }

   

    private function buscarG()
    {
        $registro = "SELECT detgasto_descripcion FROM gasto WHERE detgasto_descripcion = :detgasto_descripcion";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':detgasto_descripcion', $this->descripcion);
        $res = $strExec->execute();
        $datos = $strExec->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            return $datos;
        } else {
            return [];
        }
    }

    public function buscar_gasto()
    {
        return $this->buscarG();
    }
}
