<?php 
require_once "conexion.php";
class Rol extends Conexion{

    private $conex;
    private $rol;
    private $status;

    public function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

#GETTER Y SETTER
    public function getRol(){
        return $this->rol;
    }
    public function setRol($rol){
        $this->rol = $rol;
    }
    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }

/*==============================
REGISTRAR TIPOS DE USUARIO
================================*/
    private function crearRol($permisos){

        $sql = "INSERT INTO tipo_usuario(rol,status) VALUES(:rol, 1)";

        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(":rol", $this->rol);

        $resul = $strExec->execute();

        if($resul){
            $nuevo_cod=$this->conex->lastInsertId();

            foreach($permisos as $cod_permisos){
            $sqlpermiso="INSERT INTO tpu_permisos (cod_tipo_usuario, cod_permiso) VALUES (:cod_tipo_usuario, :cod_permiso)";
            $strExec = $this->conex->prepare($sqlpermiso);
            $strExec->bindParam(":cod_tipo_usuario", $nuevo_cod);
            $strExec->bindParam(":cod_permiso", $cod_permisos);
            $strExec->execute();
            }
            $r = 1;
        }else{
            $r = 0;
        }
        return $r;

        

    }
    public function getcrearRol($valor){
        return $this->crearRol($valor);
    }

    public function consultar(){
        $registro="select * from tipo_usuario";
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
        $this->rol=$valor;
        $registro = "select * from tipo_usuario where rol='".$this->rol."'";
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

    public function permisos(){
        $registro = "select * from permisos";
        $accesos= "";
            $dato=$this->conex->prepare($registro);
            $resul=$dato->execute();
            $accesos=$dato->fetchAll(PDO::FETCH_ASSOC);
            if ($resul) {
                return $accesos;
            }else{
                return false;
            }
    }

}