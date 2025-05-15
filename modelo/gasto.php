<?php
require_once 'conexion.php';
require_once 'validaciones.php';
class Gastos extends Conexion
{
    use ValidadorTrait;
    private $errores = [];
    private $datos = [];
    #VARIABLES DEL AJUSTE DE GASTOS
    private $cod_frecuencia; //
    private $dias; //
    private $cod_naturaleza; //
    private $frecuencia; //
    private $cod_condicion; //
    private $cod_cat_gasto; //
    private $cod_tipo_gasto; //
    private $nombre; //
    private $origin; //
    private $fecha; //
    private $status_cat_gasto;
    //SETTER Y GETTER DE GASTOS
    private $cod_gasto; //
    private $descripcion; //
    private $monto; //
    private $status;

    public function __construct()
    {
        parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
    }

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

                    $this->status_cat_gasto = $value;

                    break;
                case 'frecuenciaC':
                    if (is_numeric($value)) {
                        $this->cod_frecuencia = $value;
                    } else if ($value == null) {
                        $this->cod_frecuencia = null;
                    } else {
                        $this->errores[] = "El campo $key debe ser numérico.";
                    }
                    break;
                case 'naturaleza':
                    if (is_numeric($value)) {
                        $this->cod_naturaleza = $value;
                    } else {
                        $this->errores[] = "El campo $key debe ser numérico.";
                    }
                    break;
                case 'cod_condicion':
                    if (is_numeric($value)) {
                        $this->cod_condicion = $value;
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
                case 'tipogasto':
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
                case 'nombre':

                    $res = $this->validarTexto($value, $key, 2, 50);
                    if ($res === true) {
                        $this->nombre = $value;
                    } else {
                        $this->errores[] = $res;
                    }

                    break;
                case 'origin':
                    $res = $this->validarTexto($value, $key, 2, 50);
                    if ($res === true) {
                        $this->origin = $value;
                    } else {
                        $this->errores[] = $res;
                    }

                    break;
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
                case 'fecha':
                    if (!empty($value)) {
                        $this->fecha = $value;
                    } else {
                        $this->errores[] = "El campo $key no puede estar vacío.";
                    }
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


    #SECCIÓN DE AJUSTES DE GASTOS
    private function registrarF()
    {
        if (!empty($this->frecuencia) && !empty($this->dias)) {
            $registro = "INSERT INTO frecuencia_gasto(nombre, dias) VALUES(:nombre, :dias)";
            parent::conectarBD();
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':nombre', $this->frecuencia);
            $strExec->bindParam(':dias', $this->dias);
            $resul = $strExec->execute();
            parent::desconectarBD();
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
        parent::conectarBD();
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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
        parent::conectarBD();
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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

        var_dump($this->cod_frecuencia);
        var_dump($this->cod_tipo_gasto);
        var_dump($this->nombre);
        var_dump($this->fecha);
        var_dump($this->cod_naturaleza);
        if (!empty($this->cod_tipo_gasto) && !empty($this->nombre) && !empty($this->fecha)) {
            if ($this->buscarCategoria() == null) {

                $registro = "INSERT INTO categoria_gasto(cod_tipo_gasto, cod_frecuencia, cod_naturaleza, nombre, fecha, status_cat_gasto) VALUES(:cod_tipo_gasto, :cod_frecuencia,:cod_naturaleza, :nombre, :fecha, 1)";
                parent::conectarBD();
                $strExec = $this->conex->prepare($registro);
                $strExec->bindParam(':cod_frecuencia', $this->cod_frecuencia);
                $strExec->bindParam(':cod_tipo_gasto', $this->cod_tipo_gasto);
                $strExec->bindParam(':nombre', $this->nombre);
                $strExec->bindParam(':cod_naturaleza', $this->cod_naturaleza);
                $strExec->bindParam(':fecha', $this->fecha);
                $resul = $strExec->execute();
                parent::desconectarBD();
                if ($resul == 1) {
                    return $r = 1;
                } else {
                    return $r = 0;
                }
            } else {
                return $r = 2;
            }
        }
    }
    public function publicregistrarc()
    {
        return $this->registrarC();
    }

    private function consultarC()
    {
        $sql = "SELECT c.cod_cat_gasto, c.cod_tipo_gasto, c.fecha, c.status_cat_gasto, c.cod_frecuencia, c.nombre AS categoria, f.cod_frecuencia, f.nombre, t.cod_tipo_gasto, t.nombre AS nombret, n.cod_naturaleza, n.nombre_naturaleza,
        f.nombre AS nombref FROM categoria_gasto c
        LEFT JOIN tipo_gasto t ON c.cod_tipo_gasto = t.cod_tipo_gasto
        LEFT JOIN naturaleza_gasto n ON c.cod_naturaleza = n.cod_naturaleza
        LEFT JOIN frecuencia_gasto f ON c.cod_frecuencia = f.cod_frecuencia";
        parent::conectarBD();
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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

    private function consultarCondicion()
    {
        $sql = "SELECT * FROM condicion_pagoe";
        parent::conectarBD();
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }
    public function consultarCondi()
    {
        return $this->consultarCondicion();
    }

    private function consulN()
    {
        $sql = "SELECT cod_naturaleza, nombre_naturaleza FROM naturaleza_gasto";
        parent::conectarBD();
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
        if ($resul) {
            return $datos;
        } else {
            return [];
        }
    }
    public function consulNaturaleza()
    {
        return $this->consulN();
    }

    private function buscarTporC()
    {
        $sql = "SELECT c.cod_cat_gasto, c.cod_tipo_gasto, c.nombre, t.cod_tipo_gasto, t.nombre AS nombret
                FROM categoria_gasto c
                JOIN tipo_gasto t ON c.cod_tipo_gasto = t.cod_tipo_gasto
                WHERE c.cod_cat_gasto = :cod_cat_gasto";
        parent::conectarBD();
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_cat_gasto', $this->cod_cat_gasto);
        $resul = $strExec->execute();
        $datos = $strExec->fetch(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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
        parent::conectarBD();
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':nombre', $this->nombre);
        $resul = $strExec->execute();
        $datos = $strExec->fetch(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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
        parent::conectarBD();
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':nombre', $this->frecuencia);
        $resul = $strExec->execute();
        $datos = $strExec->fetch(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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

    private function EditC() //listo
    {
        try {
            parent::conectarBD();
            if ($this->status_cat_gasto !== null && $this->nombre === $this->origin) {
                // Solo se está editando el status
                $registro = "UPDATE categoria_gasto SET status_cat_gasto = :status_cat_gasto WHERE cod_cat_gasto = :cod_cat_gasto";
                //parent::conectarBD();
                $strExec = $this->conex->prepare($registro);
                $strExec->bindParam(':cod_cat_gasto', $this->cod_cat_gasto);
                $strExec->bindParam(':status_cat_gasto', $this->status_cat_gasto);
                $res = $strExec->execute();
                parent::desconectarBD();
                if ($res) {
                    return $res = 1;
                } else {

                    return $res = 'error_query';
                }
            }

            $comparar = "SELECT nombre FROM categoria_gasto WHERE nombre = :nombre"; //funciona

            $strExec = $this->conex->prepare($comparar);
            $strExec->bindParam(':nombre', $this->nombre);
            $res = $strExec->execute();
            $datos = $strExec->fetch(PDO::FETCH_ASSOC);

            if ($datos != null) {
                if ($datos['nombre'] == $this->nombre && $this->nombre != $this->origin) {
                    parent::desconectarBD();
                    return 'error_associated'; // La categoría ya existe
                }
            } else {
                $registro = "UPDATE categoria_gasto SET status_cat_gasto = :status_cat_gasto, nombre = :nombre WHERE cod_cat_gasto = :cod_cat_gasto";
                $strExec = $this->conex->prepare($registro);
                $strExec->bindParam(':cod_cat_gasto', $this->cod_cat_gasto);
                $strExec->bindParam(':status_cat_gasto', $this->status_cat_gasto);
                $strExec->bindParam(':nombre', $this->nombre);
                $res = $strExec->execute();
                parent::desconectarBD();
                if ($res) {
                    return 1;
                } else {
                    return 'error_query';
                }
            }
        } catch (Exception $e) {
            error_log($e->getMessage());

            // Captura cualquier otro tipo de error
            parent::desconectarBD();
            $errores[] = throw new Exception("Error en la transacción: " . $e->getMessage());
        }
    }

    public function editarC()
    {
        return $this->EditC();
    }

    private function eliminarC()
    {
        try {
            parent::conectarBD();
            $this->conex->beginTransaction();

            // Verifica si hay pagos asociados
            $gasto = "SELECT COUNT(*) AS n_gasto FROM gasto WHERE cod_cat_gasto = :cod_cat_gasto";
            $strExec = $this->conex->prepare($gasto);
            $strExec->bindParam(':cod_cat_gasto', $this->cod_cat_gasto, PDO::PARAM_INT);
            $strExec->execute();
            $resultado = $strExec->fetch(PDO::FETCH_ASSOC);

            if ($resultado['n_gasto'] > 0) {
                var_dump($resultado['n_gasto']);
                $this->conex->rollBack();
                parent::desconectarBD();
                return "error_associated";
            }
            $status = "SELECT status_cat_gasto FROM categoria_gasto  WHERE cod_cat_gasto = :cod_cat_gasto";
            $strExec = $this->conex->prepare($status);
            $strExec->bindParam(':cod_cat_gasto', $this->cod_cat_gasto, PDO::PARAM_INT);
            $re = $strExec->execute();
            $estado = $strExec->fetch(PDO::FETCH_ASSOC);
            if($estado['status_cat_gasto'] == 1){
                $this->conex->rollBack();
                parent::desconectarBD();
                return "error_delete";
            }
            $fisico = "DELETE FROM categoria_gasto  WHERE cod_cat_gasto = :cod_cat_gasto";
            $strExec = $this->conex->prepare($fisico);
            $strExec->bindParam(':cod_cat_gasto', $this->cod_cat_gasto, PDO::PARAM_INT);
            $re = $strExec->execute();

            if ($re) {
                $this->conex->commit();
                parent::desconectarBD();
                return "success";
            } else {
                $this->conex->rollBack();
                parent::desconectarBD();
                return "error_delete";
            }
        } catch (Exception $e) {
            $this->conex->rollBack();
            parent::desconectarBD();
            return "error_query: " . $e->getMessage();
        }
    }

    public function eliminarCat()
    {
        return $this->eliminarC();
    }

    //FUNCIONES DE GASTOS

    private function registrarG()
    {

        $resp = $this->buscarG();
        if ($resp == null) {
            $registro = "INSERT INTO gasto(cod_cat_gasto,cod_condicion,descripcion, monto,fecha_creacion, status) VALUES(:cod_cat_gasto , :cod_condicion, :descripcion, :monto, :fecha_creacion, 1)";
            parent::conectarBD();
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':cod_cat_gasto', $this->cod_cat_gasto);
            $strExec->bindParam(':cod_condicion', $this->cod_condicion);
            $strExec->bindParam(':descripcion', $this->descripcion);
            $strExec->bindParam(':monto', $this->monto);
            $strExec->bindParam(':fecha_creacion', $this->fecha);
            $resul = $strExec->execute();
            parent::desconectarBD();
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
    public function publicregistrarg()
    {
        return $this->registrarG();
    }

    private function consultarV()
    {
        $valor = "variable";
        $registro = "SELECT 
        t.nombre,
        n.nombre_naturaleza,
        n.cod_naturaleza,
        g.cod_gasto, 
        g.descripcion, 
        g.monto, 
        g.status, 
        c.nombre AS categoria_nombre, 
        c.fecha AS fechac, 
        c.cod_tipo_gasto,
        p.cod_pago_emitido,
        COALESCE(p.fecha, 'Sin fecha') AS fecha,  
        COALESCE(p.monto_total, 0) AS monto_ultimo_pago,  
        COALESCE(tp.total_pagos_emitidos, 0) AS total_pagos_emitidos,  
        COALESCE(v.vuelto_total, 0) AS vuelto_total  
FROM 
        gasto g
LEFT JOIN 
        categoria_gasto c ON g.cod_cat_gasto = c.cod_cat_gasto
LEFT JOIN 
        tipo_gasto t ON c.cod_tipo_gasto = t.cod_tipo_gasto
LEFT JOIN 
        naturaleza_gasto n ON c.cod_naturaleza = n.cod_naturaleza
LEFT JOIN 
        (
            SELECT 
                pe.cod_gasto, 
                pe.cod_pago_emitido,
                pe.fecha,
                pe.monto_total,
                pe.cod_vuelto_r  
            FROM 
                pago_emitido pe
            INNER JOIN 
                (
                    SELECT 
                        cod_gasto, 
                        MAX(fecha) AS max_fecha
                    FROM 
                        pago_emitido
                    GROUP BY 
                        cod_gasto
                ) max_pe ON pe.cod_gasto = max_pe.cod_gasto AND pe.fecha = max_pe.max_fecha
        ) p ON g.cod_gasto = p.cod_gasto
LEFT JOIN vuelto_recibido v ON p.cod_vuelto_r = v.cod_vuelto_r 
LEFT JOIN 
        (
            SELECT 
                cod_gasto, 
                SUM(monto_total) AS total_pagos_emitidos
            FROM 
                pago_emitido
            GROUP BY 
                cod_gasto
        ) tp ON g.cod_gasto = tp.cod_gasto
WHERE 
        n.nombre_naturaleza = :nombre_naturaleza
GROUP BY 
        g.cod_gasto, g.descripcion, g.monto, g.status, c.nombre, c.fecha, c.cod_tipo_gasto, 
        p.cod_pago_emitido, p.fecha, p.monto_total, v.vuelto_total, n.nombre_naturaleza";
        parent::conectarBD();
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre_naturaleza', $valor);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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
        $valor = "fijo"; //TERMINADO 15/05/2025
        $registro = "SELECT 
        t.nombre,
        n.nombre_naturaleza,
        n.cod_naturaleza,
        g.cod_gasto, 
        g.descripcion, 
        g.monto, 
        g.status, 
        c.nombre AS categoria_nombre, 
        c.fecha AS fechac, 
        c.cod_tipo_gasto,
        p.cod_pago_emitido,
        COALESCE(p.fecha, 'Sin fecha') AS fecha,  
        COALESCE(p.monto_total, 0) AS monto_ultimo_pago,  
        COALESCE(tp.total_pagos_emitidos, 0) AS total_pagos_emitidos,  
        COALESCE(v.vuelto_total, 0) AS vuelto_total  
FROM 
        gasto g
LEFT JOIN 
        categoria_gasto c ON g.cod_cat_gasto = c.cod_cat_gasto
LEFT JOIN 
        tipo_gasto t ON c.cod_tipo_gasto = t.cod_tipo_gasto
LEFT JOIN 
        naturaleza_gasto n ON c.cod_naturaleza = n.cod_naturaleza
LEFT JOIN 
        (
            SELECT 
                pe.cod_gasto, 
                pe.cod_pago_emitido,
                pe.fecha,
                pe.monto_total,
                pe.cod_vuelto_r 
            FROM 
                pago_emitido pe
            INNER JOIN 
                (
                    SELECT 
                        cod_gasto, 
                        MAX(fecha) AS max_fecha
                    FROM 
                        pago_emitido
                    GROUP BY 
                        cod_gasto
                ) max_pe ON pe.cod_gasto = max_pe.cod_gasto AND pe.fecha = max_pe.max_fecha
        ) p ON g.cod_gasto = p.cod_gasto
LEFT JOIN vuelto_recibido v ON p.cod_vuelto_r = v.cod_vuelto_r 
LEFT JOIN 
        (
            SELECT 
                cod_gasto, 
                SUM(monto_total) AS total_pagos_emitidos
            FROM 
                pago_emitido
            GROUP BY 
                cod_gasto
        ) tp ON g.cod_gasto = tp.cod_gasto
WHERE 
        n.nombre_naturaleza = :nombre_naturaleza
GROUP BY 
        g.cod_gasto, g.descripcion, g.monto, g.status, c.nombre, c.fecha, c.cod_tipo_gasto, 
        p.cod_pago_emitido, p.fecha, p.monto_total, v.vuelto_total, n.nombre_naturaleza";
        parent::conectarBD();
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre_naturaleza', $valor);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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
        $registro = "SELECT SUM(g.monto) AS total_monto
                 FROM gasto g
                 JOIN categoria_gasto c ON g.cod_cat_gasto = c.cod_cat_gasto
                 JOIN tipo_gasto t ON c.cod_tipo_gasto = t.cod_tipo_gasto
                 WHERE t.nombre = :nombret AND status != 3";
        parent::conectarBD();
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombret', $variable);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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
        $registro = "SELECT SUM(g.monto) AS total_monto
                 FROM gasto g
                 JOIN categoria_gasto c ON g.cod_cat_gasto = c.cod_cat_gasto
                 JOIN tipo_gasto t ON c.cod_tipo_gasto = t.cod_tipo_gasto
                 WHERE t.nombre = :nombret AND g.status != 3";
        parent::conectarBD();
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombret', $variable);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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
        $registro = "SELECT SUM(g.monto) AS total_monto FROM gasto g WHERE g.status != 3";
        parent::conectarBD();
        $strExec = $this->conex->prepare($registro);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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
        $registro = "SELECT SUM(g.monto) AS total_monto FROM gasto g WHERE g.status = 3";
        parent::conectarBD();
        $strExec = $this->conex->prepare($registro);
        $resul = $strExec->execute();
        $datos = $strExec->fetchAll(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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
        $registro = "UPDATE gasto SET descripcion = :descripcion WHERE cod_gasto = :cod_gasto";
        parent::conectarBD();
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':descripcion', $this->descripcion);
        $strExec->bindParam(':cod_gasto', $this->cod_gasto);
        $res = $strExec->execute();
        parent::desconectarBD();
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
        $registro = "SELECT descripcion FROM gasto WHERE descripcion = :descripcion";
        parent::conectarBD();
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':descripcion', $this->descripcion);
        $res = $strExec->execute();
        $datos = $strExec->fetch(PDO::FETCH_ASSOC);
        parent::desconectarBD();
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

    private function eliminarG()
    {
        try {
            parent::conectarBD();
            $this->conex->beginTransaction();

            $pago = "SELECT COUNT(*) AS n_gasto FROM pago_emitido WHERE cod_gasto = :cod_gasto";
            $strExec = $this->conex->prepare($pago);
            $strExec->bindParam(':cod_gasto', $this->cod_gasto, PDO::PARAM_INT);
            $strExec->execute();
            $resultado = $strExec->fetch(PDO::FETCH_ASSOC);

            if ($resultado['n_gasto'] > 0) {
                var_dump($resultado['n_gasto']);
                $this->conex->rollBack();
                parent::desconectarBD();
                return "error_associated";
            }

            // Elimina el gasto
            $fisico = "UPDATE gasto SET status = 0 WHERE cod_gasto = :cod_gasto";
            $strExec = $this->conex->prepare($fisico);
            $strExec->bindParam(':cod_gasto', $this->cod_gasto, PDO::PARAM_INT);
            $re = $strExec->execute();

            if ($re) {
                $this->conex->commit();
                parent::desconectarBD();
                return "success";
            } else {
                $this->conex->rollBack();
                parent::desconectarBD();
                return "error_delete";
            }
        } catch (Exception $e) {
            $this->conex->rollBack();
            parent::desconectarBD();
            return "error_query: " . $e->getMessage();
        }
    }

    public function eliminarGasto()
    {
        return $this->eliminarG();
    }
}
