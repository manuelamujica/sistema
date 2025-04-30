<?php
require_once 'conexion.php';
require_once 'validaciones.php';

class Divisa extends Conexion
{
    use ValidadorTrait; // Usar el trait para validaciones

    private $errores = [];
    private $nombre;
    private $simbolo;

    private $status;
    private $tasa;
    private $fecha;



    public function __construct()
    {
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

    public function setnombre($valor)
    {
        $res = $this->validarTexto($valor, 'nombre', 2, 50);
        if ($res === true) {
            $this->nombre = $valor;
        } else {
            $this->errores[] = $res;
        }
    }
    public function setsimbolo($valor)
    {
        $res = $this->validarTexto($valor, 'simbolo', 2, 10);
        if ($res === true) {
            $this->simbolo = $valor;
        } else {
            $this->errores[] = $res;
        }
    }
    public function setstatus($valor)
    {
        $res = $this->validarNumerico($valor, 'status', 1, 1);
        if ($res === true) {
            $this->status = $valor;
        } else {
            $this->errores[] = $res;
        }
    }
    public function set_tasa($valor)
    {
        $res = $this->validarNumerico($valor, 'tasa', 0, 1000000);
        if ($res === true) {
            $this->tasa = $valor;
        } else {
            $this->errores[] = $res;
        }
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


    public function setfecha($valor)
    {
        $this->fecha = $valor;
    }

    public function getnombre()
    {
        return $this->nombre;
    }
    public function getsimbolo()
    {
        return $this->simbolo;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function get_tasa()
    {
        return $this->tasa;
    }
    public function getfecha()
    {
        return $this->fecha;
    }

    public function incluir()
    {
        $registro = "INSERT INTO divisas(nombre, abreviatura, status) VALUES(:nombre, :abreviatura, 1)";

        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':abreviatura', $this->simbolo);
        $resul = $strExec->execute();
        if ($resul) {
            $ultimo_cod = $this->conex->lastInsertId();
            $sqlCambio = "INSERT INTO cambio_divisa (cod_divisa, tasa, fecha) VALUES (:cod_divisa, :tasa, :fecha)";
            $strExec = $this->conex->prepare($sqlCambio);
            $strExec->bindParam(':cod_divisa', $ultimo_cod);
            $strExec->bindParam(':tasa', $this->tasa);
            $strExec->bindParam(':fecha', $this->fecha);
            $strExec->execute();
            $res = 1;
        } else {
            $res = 0;
        }
        $this->desconectarBD();
        return $res;
    }


    public function consultarDivisas() {
        $sql = "SELECT *FROM divisas";
        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consultar(){
        $registro="SELECT d.cod_divisa, d.nombre, d.abreviatura, d.status AS divisa_status,c.cod_cambio, c.tasa, c.fecha
        FROM divisas AS d
        JOIN cambio_divisa AS c 
            ON d.cod_divisa = c.cod_divisa
        JOIN ( SELECT cod_divisa, MAX(fecha) AS ultima_fecha
            FROM cambio_divisa
            GROUP BY cod_divisa ) AS ultimos_cambios
            ON c.cod_divisa = ultimos_cambios.cod_divisa 
            AND c.fecha = ultimos_cambios.ultima_fecha
        ORDER BY d.cod_divisa;";

        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return $res = 0;
        }
    }


    public function buscar($valor)
    {
        $this->nombre = $valor;
        $registro = "select * from divisas where nombre='" . $this->nombre . "'";
        $resutado = "";
        $dato = $this->conex->prepare($registro);
        $resul = $dato->execute();
        $resultado = $dato->fetch(PDO::FETCH_ASSOC);
        if ($resul) {
            return $resultado;
        } else {
            return false;
        }
    }

    public function editar($valor)
    {
        $registro = "UPDATE divisas SET nombre=:nombre, abreviatura=:abreviatura, status=:status WHERE cod_divisa=$valor";
        $strExec = $this->conex->prepare($registro);
        #instanciar metodo bindparam
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':abreviatura', $this->simbolo);
        $strExec->bindParam(':status', $this->status);
        $resul = $strExec->execute();
        if ($resul) {
            $r = 1;
        } else {
            $r = 0;
        }
        $this->desconectarBD();
        return $r;
    }


    public function eliminar($valor)
    {
        $registro = "SELECT COUNT(*) AS v_count FROM cambio_divisa cd LEFT JOIN detalle_caja dc ON dc.cod_divisas = cd.cod_cambio LEFT JOIN cuenta_bancaria cb ON cb.cod_divisa = cd.cod_cambio WHERE cd.cod_cambio = $valor";
        $strExec = $this->conex->prepare($registro);
        $resul = $strExec->execute();
        if ($resul) {
            $resultado = $strExec->fetch(PDO::FETCH_ASSOC);
            if ($resultado['v_count'] > 0) {
                $r = 0;
            } else {
                $fisico = "DELETE FROM divisas WHERE cod_divisa=$valor";
                $strExec = $this->conex->prepare($fisico);
                $strExec->execute();
                $r = 1;
            }

        }
        return $r;
    }

    public function tasa($valor)
    {
        foreach ($valor as $divisa) {
            $sql = "INSERT INTO cambio_divisa (cod_divisa, tasa, fecha) VALUES (:cod_divisa, :tasa, :fecha)";
            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(':tasa', $divisa['tasa']);
            $strExec->bindParam(':fecha', $divisa['fecha']);
            $strExec->bindParam(':cod_divisa', $divisa['cod_divisa']);

            $resul = $strExec->execute();
            if (!$resul) {
                return false;
            }
        }
        return true;
    }

    public function historial()
    {
        $registro = "SELECT * FROM cambio_divisa ORDER BY fecha DESC;";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return $res = 0;
        }
    }
}
