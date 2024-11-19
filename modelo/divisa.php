<?php
require_once 'conexion.php';

class Divisa extends Conexion{
    private $nombre;
    private $simbolo;
    private $conex;
    private $status;
    private $tasa;
    private $fecha;


    public function __construct(){
        $this->conex= new Conexion();
        $this->conex=$this->conex->conectar();
    }

    public function incluir(){
        $registro="INSERT INTO divisas(nombre, abreviatura, status) VALUES(:nombre, :abreviatura, 1)";

        $strExec=$this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':abreviatura', $this->simbolo);
        $resul=$strExec->execute();
        if ($resul){
            $ultimo_cod= $this->conex->lastInsertId();
            $sqlCambio = "INSERT INTO cambio_divisa (cod_divisa, tasa, fecha) VALUES (:cod_divisa, :tasa, :fecha)";
            $strExec=$this->conex->prepare($sqlCambio);
            $strExec->bindParam(':cod_divisa', $ultimo_cod);
            $strExec->bindParam(':tasa', $this->tasa);
            $strExec->bindParam(':fecha', $this->fecha);
            $strExec->execute();
            $res=1;
        }else{
            $res=0;
        }
        return $res;
    }

    public function consultar(){
        $registro="SELECT d.cod_divisa, d.nombre, d.abreviatura, d.status AS divisa_status, c.cod_cambio, c.tasa, c.fecha FROM divisas AS d 
        JOIN cambio_divisa AS c ON d.cod_divisa = c.cod_divisa ORDER BY d.cod_divisa;";
        $consulta=$this->conex->prepare($registro);
        $resul=$consulta->execute();
        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
        if($resul){
            return $datos;
        }else{
            return $res=0;
        }
    }

    public function buscar($valor){
        $this->nombre=$valor;
        $registro = "select * from divisas where nombre='".$this->nombre."'";
        $resutado= "";
            $dato=$this->conex->prepare($registro);
            $resul=$dato->execute();
            $resultado=$dato->fetch(PDO::FETCH_ASSOC);
            if ($resul) {
                return $resultado;
            }else{
                return false;
            }
    }

    public function editar($valor){
        $registro="UPDATE divisas SET nombre=:nombre, abreviatura=:abreviatura, status=:status WHERE cod_divisa=$valor";
        $strExec = $this->conex->prepare($registro);
        #instanciar metodo bindparam
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':abreviatura', $this->simbolo);
        $strExec->bindParam(':status', $this->status);
        $resul = $strExec->execute();
        if($resul){
            $sql2 = "UPDATE cambio_divisa SET tasa = :tasa, fecha = :fecha WHERE cod_divisa = $valor";
            $strExec = $this->conex->prepare($sql2);
            $strExec->bindParam(':tasa', $this->tasa);
            $strExec->bindParam(':fecha', $this->fecha);
            $strExec->execute();
            $r = 1;
        }else{
            $r = 0;
        }
        return $r;
    }

    public function eliminar($valor){
        $registro="SELECT COUNT(*) AS v_count FROM cambio_divisa cd JOIN tipo_pago tp ON cd.cod_cambio = tp.cod_cambio WHERE cd.cod_divisa = $valor";
        $strExec = $this->conex->prepare($registro);
        $resul = $strExec->execute();
        if($resul){
            $resultado=$strExec->fetch(PDO::FETCH_ASSOC); 
            if ($resultado['v_count']>0){
                $logico="UPDATE divisas SET status=2 WHERE cod_divisa=$valor";
                $strExec=$this->conex->prepare($logico);
                $strExec->execute();
                $r=1;
            }else{
                if($valor==1){
                    $r=0;
                }else{
                $fisico="DELETE FROM divisas WHERE cod_divisa=$valor";
                $strExec=$this->conex->prepare($fisico);
                $strExec->execute();
                $r=1;
                }
            }
        }else {
            $r=0;
        }
        return $r;
    }

    public function tasa($valor){
        foreach($valor as $divisa){
            $sql="UPDATE cambio_divisa SET tasa=:tasa, fecha=:fecha WHERE cod_divisa = :cod_divisa";
            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(':tasa', $divisa['tasa']);
            $strExec->bindParam(':fecha', $divisa['fecha']);
            $strExec->bindParam(':cod_divisa', $divisa['cod_divisa']);
            $resul=$strExec->execute();
            if(!$resul){
                return false;
            }
        }
    return true;
    }

    public function setnombre($valor){
        $this->nombre=$valor;
    }
    public function setsimbolo($valor){
        $this->simbolo=$valor;
    }
    public function setstatus($valor){
        $this->status = $valor;
    }
    public function set_tasa($valor){
        $this->tasa = $valor;
    }
    public function setfecha($valor){
        $this->fecha = $valor;
    }

    public function getnombre(){
        return $this->nombre;
    }
    public function getsimbolo(){
        return $this->simbolo;
    }
    public function getStatus(){
        return $this->status;
    }
    public function get_tasa(){
        return $this->tasa;
    }
    public function getfecha(){
        return $this->fecha;
    }
}