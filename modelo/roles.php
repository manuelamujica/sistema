<?php 
require_once "conexion.php";
class Rol extends Conexion{

    private $conex;
    private $rol;
    private $codigo;

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
    public function getcodigo(){
        return $this->codigo;
    }
    public function setcodigo($codigo){
        $this->codigo = $codigo;
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

    //Para usuario
    private function consultarUsuario(){
        $registro="SELECT * FROM tipo_usuario WHERE status=1";
        $consulta=$this->conex->prepare($registro);
        $resul=$consulta->execute();
        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
        if($resul){
            return $datos;
        }else{
            return $res=0;
        }
    }


    public function getconsultarUsuario(){
        return $this->consultarUsuario();
    }

    public function consultarLogin($cod){
        $registro="SELECT rol FROM tipo_usuario WHERE cod_tipo_usuario=:cod_tipo_usuario";
        $resul=$this->conex->prepare($registro);
        $resul->bindParam(':cod_tipo_usuario',$cod);
        $resul->execute();
        $rol=$resul->fetch(PDO::FETCH_ASSOC);
        if($resul){
            return $rol;
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

    public function buscarcod($valor){
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

    private function editar(){
        $registro = "UPDATE tipo_usuario SET rol = :rol, status = :status WHERE cod_tipo_usuario = :cod_tipo_usuario";

        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':cod_tipo_usuario',$this->codigo);
        $strExec->bindParam(':rol',$this->rol);
        $strExec->bindParam(':status', $this->status);
        $resul = $strExec->execute();
        if($resul == 1){
            $r = 1;
        }else{
            $r = 0;
        }
        return $r;
    }

    public function geteditar(){
        return $this->editar();
    }

    private function eliminar($valor) {
        // Verificar el status del rol
        $consultaStatus = "SELECT status FROM tipo_usuario WHERE cod_tipo_usuario = :valor";
        $strExec = $this->conex->prepare($consultaStatus);
        $strExec->bindParam(':valor', $valor, PDO::PARAM_INT);
        $strExec->execute();
        
        $status = $strExec->fetch(PDO::FETCH_ASSOC);
    
        if ($status && $status['status'] == 1) {
            return 'error_status'; // El rol tiene status activo, no se puede eliminar
        }
    
        // Verificar si hay usuarios asociados al rol
        $consultaUsuarios = "SELECT COUNT(*) AS n_usuario FROM usuarios WHERE cod_tipo_usuario = :valor";
        $strExec = $this->conex->prepare($consultaUsuarios);
        $strExec->bindParam(':valor', $valor, PDO::PARAM_INT);
        $strExec->execute();
        
        $usuarios = $strExec->fetch(PDO::FETCH_ASSOC);
    
        if ($usuarios && $usuarios['n_usuario'] > 0) {
            return 'error_associated'; // Hay usuarios asociados al rol, no se puede eliminar
        }
    
        // Eliminar el rol
        $deleteQuery = "DELETE FROM tipo_usuario WHERE cod_tipo_usuario = :valor";
        $strExec = $this->conex->prepare($deleteQuery);
        $strExec->bindParam(':valor', $valor, PDO::PARAM_INT);
        $result = $strExec->execute();
    
        return $result ? 'success' : 'error_delete';
    }
    

    public function geteliminar($valor){
        return $this->eliminar($valor);
    }

}