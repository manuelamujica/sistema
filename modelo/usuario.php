<?php
require_once "conexion.php";

class Usuario extends Conexion{

    private $conex;
    private $nombre;
    private $user;
    private $password;
    private $status;

    public function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

/*GETTER Y SETTER*/
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function getUser(){
        return $this->user;
    }
    public function setUser($user){
        $this->user = $user;
    }

    public function getPassword(){
        return $this->password;
    }
    public function setPassword($password){
        $this->password = $password;
    }
    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }

//Login
    public function mostrar($item, $valor){

            // Preparamos la consulta SQL usando un parámetro de tipo dinámico

            $resultado = [];

            $sql = "SELECT * FROM usuarios WHERE $item = :$item";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado === false) {
            return [];
            }else{
                return $resultado;
            }
    }

//Conocer los roles para seleccionarlos

public function roles(){
    $registro = "select * from tipo_usuario";
    $consulta = $this->conex->prepare($registro);
    $resul = $consulta->execute();

    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

    if($resul){
        return $datos;
    }else{
        return $r=[];
    }

}


/*==============================
REGISTRAR CATEGORIA
================================*/
private function registrar($rol){

    $sql = "INSERT INTO usuarios(nombre,user,password,cod_tipo_usuario,status) VALUES(:nombre,:user,:password,:cod_tipo_usuario,1)";

    $strExec = $this->conex->prepare($sql);

    $strExec->bindParam(":nombre", $this->nombre);
    $strExec->bindParam(":user", $this->user);
    $strExec->bindParam(":password", $this->password);
    $strExec->bindParam(":cod_tipo_usuario", $rol);

    $resul = $strExec->execute();

    if($resul){
        $r = 1;
    }else{
        $r = 0;
    }
    return $r;

}

public function getregistrar($rol){
    return $this->registrar($rol);
}

/*==============================
VALIDAR USUARIO
================================*/
public function buscar($valor){
    $this->user=$valor;
    $registro = "select * from usuarios where user='".$this->user."'";
    $resultado= "";
        $dato=$this->conex->prepare($registro);
        $resul=$dato->execute();
        $resultado=$dato->fetch(PDO::FETCH_ASSOC);  
        if ($resul) {
            return $resultado;
        }else{
            return false;
        }
    }

/*==============================
MOSTRAR USUARIOS
================================*/
public function listar(){
    $registro = "select * from usuarios";
    $consulta = $this->conex->prepare($registro);
    $resul = $consulta->execute();

    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if($resul){
        return $datos;
    }else{
        return $r=0;
    }

}
/*==============================
EDITAR USUARIO
================================*/
public function editar($valor,$rol){
    $sql="UPDATE usuarios SET nombre=:nombre,user=:user,password=:password,cod_tipo_usuario=:cod_tipo_usuario, status=:status WHERE cod_usuario=$valor";
    $strExec = $this->conex->prepare($sql);

    #Instanciar metodo BINDPARAM
    $strExec->bindParam(':nombre', $this->nombre);
    $strExec->bindParam(':user', $this->user);
    $strExec->bindParam(':password', $this->password);
    $strExec->bindParam(":cod_tipo_usuario", $rol);
    $strExec->bindParam(':status', $this->status);
    $resul = $strExec->execute();
    if($resul){
        $r = 1;
    }else{
        $r = 0;
    }
    return $r;
}

/*==============================
ELIMINAR USUARIO
================================*/
public function eliminar($valor) {

    // Verificar si el usuario a eliminar es administrador
    $sql = "SELECT cod_tipo_usuario FROM usuarios WHERE cod_usuario = :valor";
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':valor', $valor, PDO::PARAM_INT);
    $strExec->execute();
    $usuario = $strExec->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Si el usuario es administrador, verificar si es el último
        if ($usuario['cod_tipo_usuario'] == 1) {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE cod_tipo_usuario = 1";
            $strExec = $this->conex->prepare($sql);
            $strExec->execute();
            $resultado = $strExec->fetch(PDO::FETCH_ASSOC);

            if ($resultado['total'] == 1) {
                return 'error_ultimo'; // No se puede eliminar al último administrador
            }
        }

        // Proceder con la eliminación del usuario
        $sqlDelete = "DELETE FROM usuarios WHERE cod_usuario = :valor";
        $strExecDelete = $this->conex->prepare($sqlDelete);
        $strExecDelete->bindParam(':valor', $valor, PDO::PARAM_INT);
        $delete = $strExecDelete->execute();

        return $delete ? 'success' : 'error_delete'; // Resultado de la eliminación
        }
    }
}