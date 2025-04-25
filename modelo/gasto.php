<?php
require_once 'conexion.php';
require_once 'validaciones.php';
class Gastos extends Conexion
{
    use ValidadorTrait;
    private $errores = [];
    private $conex;
    #VARIABLES DEL AJUSTE DE GASTOS
    private $cod_frecuencia;
    private $dias;
    private $frecuencia;
    private $cod_cat_gasto;
    private $cod_tipo_gasto;
    private $nombreC;
    private $fecha;

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


    #SETTER Y GETTER DEL AJUSTE DE GASTOS
    public function set_cod_cat_gasto($cod_cat_gasto)
    {
        $this->cod_cat_gasto = $cod_cat_gasto;
    }
    public function get_cod_cat_gasto()
    {
        return $this->cod_cat_gasto;
    }
    public function set_dias($dias)
    {
        $this->dias = $dias;
    }
    public function get_dias()
    {
        return $this->dias;
    }
    public function set_frecuencia($frecuencia)
    {
        $res = $this->validarTexto($frecuencia, 'nombre', 2, 50);
        if ($res === true) {
            $this->frecuencia = $frecuencia;
        } else {
            $this->errores[] = $res;
        }
    }
    public function get_frecuencia()
    {
        return $this->frecuencia;
    }
    public function set_cod_frecuencia($cod_frecuencia)
    {
        $this->cod_frecuencia = $cod_frecuencia;
    }
    public function get_cod_frecuencia()
    {
        return $this->cod_frecuencia;
    }
    public function set_cod_tipo_gasto($cod_tipo_gasto)
    {
        $this->cod_tipo_gasto = $cod_tipo_gasto;
    }
    public function get_cod_tipo_gasto()
    {
        return $this->cod_tipo_gasto;
    }

    public function set_nombreC($nombreC)
    {
        $res = $this->validarTexto($nombreC, 'Categoría', 2, 50);
        if ($res === true) {
            $this->nombreC = $nombreC;
        } else {
            $this->errores[] = $res;
        }
    }
    public function get_nombreC()
    {
        return $this->nombreC;
    }
    public function set_fecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function get_fecha()
    {
        return $this->fecha;
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
        return $datos ? $datos : []; 
    }

    public function buscarTporCategoria()
    {
        return $this->buscarTporC();
    }

    //SETTER Y GETTER DE GASTOS
    private $cod_gasto;
    private $descripcion;
    private $monto;
    private $status;
    public function set_cod_gasto($cod_gasto)
    {
        $this->cod_gasto = $cod_gasto;
    }
    public function get_cod_gasto()
    {
        return $this->cod_gasto;
    }
    public function set_descripcion($descripcion)
    {
        $res = $this->validarTexto($descripcion, 'descripcion', 2, 50);
        if ($res === true) {
            $this->descripcion = $descripcion;
        } else {
            $this->errores[] = $res;
        }
    }
    public function get_descripcion()
    {
        return $this->descripcion;
    }
    public function set_monto($monto)
    {
        $this->monto = $monto;
        
    }
    public function get_monto()
    {
        return $this->monto;
    }
    public function set_status($status)
    {
        $this->status = $status;
    }
    public function get_status()
    {
        return $this->status;
    }

    //FUNCIONES DE GASTOS
    private function registrarG()
    {
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
                    t.nombre AS nombret
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
                LEFT JOIN
                    detalle_vueltor detV ON p.cod_vuelto_r = detV.cod_vuelto_r
                LEFT JOIN 
                    vuelto_recibido v ON p.cod_vuelto_r = v.cod_vuelto_r
                WHERE 
                    t.nombre =:nombret
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
                    t.nombre AS nombret
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
                LEFT JOIN
                    detalle_vueltor detV ON p.cod_vuelto_r = detV.cod_vuelto_r
                LEFT JOIN 
                    vuelto_recibido v ON p.cod_vuelto_r = v.cod_vuelto_r
                WHERE 
                    t.nombre =:nombret
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
}
