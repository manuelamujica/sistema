<?php
require_once "conexion.php";

class Usuario extends Conexion{

    //private $conex;
    private $nombre;
    private $user;
    private $password;
    private $status;

    public function __construct(){
        parent::__construct(_SEC_DB_HOST_, _SEC_DB_NAME_, _SEC_DB_USER_, _SEC_DB_PASS_);
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

//LOGIN
    public function mostrar($valor){
            $resultado = [];
            $sql = "SELECT * FROM usuarios WHERE user = :user";
            parent::conectarBD();
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(":user", $valor, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            parent::desconectarBD();
            if ($resultado === false) {
            return [];
            }else{
                return $resultado;
            }
    }

public function accesos($valor){
    /*$sql= "SELECT tp.cod_modulo, tp.cod_crud FROM usuarios u
        INNER JOIN tipo_usuario tu ON u.cod_tipo_usuario = tu.cod_tipo_usuario
        INNER JOIN tpu_permisos tp ON tu.cod_tipo_usuario = tp.cod_tipo_usuario
        INNER JOIN modulos p ON tp.cod_modulo = p.cod_modulo
        INNER JOIN permisos c ON tp.cod_crud=c.cod_crud
        WHERE u.cod_usuario = :valor;";*/
        $sql= "SELECT p.cod_modulo FROM usuarios u
        INNER JOIN tipo_usuario tu ON u.cod_tipo_usuario = tu.cod_tipo_usuario
        INNER JOIN tpu_permisos tp ON tu.cod_tipo_usuario = tp.cod_tipo_usuario
        INNER JOIN modulos p ON tp.cod_modulo = p.cod_modulo
        WHERE u.cod_usuario = :valor;";
    parent::conectarBD();
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':valor', $valor, PDO::PARAM_INT); 
    $resul=$strExec->execute();
    $datos=$strExec->fetchAll(PDO::FETCH_ASSOC);
    parent::desconectarBD();
    if($resul){
        return $datos;
    }else{
        return $res=[];
    }
}
//FIN LOGIN

/*==============================
REGISTRAR USUARIO
================================*/
private function registrar($rol){
    $this->conectarBD();    
    $sql = "INSERT INTO usuarios(nombre,user,password,cod_tipo_usuario,status) VALUES(:nombre,:user,:password,:cod_tipo_usuario,1)";
    parent::conectarBD();
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(":nombre", $this->nombre);
    $strExec->bindParam(":user", $this->user);
    $strExec->bindParam(":password", $this->password);
    $strExec->bindParam(":cod_tipo_usuario", $rol);
    $resul = $strExec->execute();
    parent::desconectarBD();
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
VALIDAR USUARIO (USER)
================================*/
public function buscar($valor){
    $this->user=$valor;
    $this->conectarBD();
    $registro = "select * from usuarios where user=:user"; 
    $resultado= "";
    parent::conectarBD();
        $dato=$this->conex->prepare($registro);
        $dato->bindParam(':user',$this->user); 
        $resul=$dato->execute();
        $resultado=$dato->fetch(PDO::FETCH_ASSOC);
    parent::desconectarBD();
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
    $this->conectarBD();
    $registro = "SELECT
    u.cod_usuario,
    u.nombre,
    u.user,
    u.password,
    u.cod_tipo_usuario,
    u.status,
    tp.rol
    FROM usuarios AS u JOIN tipo_usuario AS tp ON u.cod_tipo_usuario = tp.cod_tipo_usuario
    GROUP BY u.cod_usuario";
    parent::conectarBD();
    $consulta = $this->conex->prepare($registro);
    $resul = $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    parent::desconectarBD();
    if($resul){
        return $datos;
    }else{
        return $r=0;
    }

}

/*==============================
EDITAR USUARIO
================================*/
    public function editar($codigo, $rol){
        $this->conectarBD();
    $sql = "UPDATE usuarios 
        SET nombre=:nombre, user=:user, cod_tipo_usuario=:cod_tipo_usuario, status=:status 
        WHERE cod_usuario=:codigo";
        parent::conectarBD();
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':user', $this->user);
        $strExec->bindParam(':cod_tipo_usuario', $rol);
        $strExec->bindParam(':status', $this->status);
        $strExec->bindParam(':codigo', $codigo, PDO::PARAM_INT);
        $resul = $strExec->execute();
        parent::desconectarBD();
        return $resul ? 1 : 0;
    }
    
    public function editar2($codigo, $rol){
        $this->conectarBD();
    $sql = "UPDATE usuarios 
            SET nombre=:nombre, user=:user, password=:password, cod_tipo_usuario=:cod_tipo_usuario, status=:status 
            WHERE cod_usuario=:codigo";
            parent::conectarBD();
            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(':nombre', $this->nombre);
            $strExec->bindParam(':user', $this->user);
            $strExec->bindParam(':password', $this->password);
            $strExec->bindParam(':cod_tipo_usuario', $rol);
            $strExec->bindParam(':status', $this->status);
            $strExec->bindParam(':codigo', $codigo, PDO::PARAM_INT);
            $resul = $strExec->execute();
            parent::desconectarBD();
            return $resul ? 1 : 0;
    }

/*==============================
ELIMINAR USUARIO
================================*/
public function eliminar($valor) {
    $this->conectarBD();
    // el usuario a eliminar es administrador?
    $sql = "SELECT cod_tipo_usuario, status FROM usuarios WHERE cod_usuario = :valor";
    parent::conectarBD();
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':valor', $valor, PDO::PARAM_INT);
    $strExec->execute();
    $usuario = $strExec->fetch(PDO::FETCH_ASSOC);
    if ($usuario) {
        // Si el usuario tiene status activo, mostrar error
        if($usuario['status'] == 1){
            parent::desconectarBD();
            return 'error_status';
        }
        
        // Si el usuario es administrador, verificar si es el último
        if ($usuario['cod_tipo_usuario'] == 1) {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE cod_tipo_usuario = 1";
            $strExec = $this->conex->prepare($sql);
            $strExec->execute();
            $resultado = $strExec->fetch(PDO::FETCH_ASSOC);
            if ($resultado['total'] == 2) { // 2 porque por defecto, el admin de programadores estará en la bd
                parent::desconectarBD();
                return 'error_ultimo';
            }
        }
        $sqlDelete = "DELETE FROM usuarios WHERE cod_usuario = :valor";
        $strExecDelete = $this->conex->prepare($sqlDelete);
        $strExecDelete->bindParam(':valor', $valor, PDO::PARAM_INT);
        $delete = $strExecDelete->execute();
        parent::desconectarBD();
        return $delete ? 'success' : 'error_delete'; 
        }
        parent::desconectarBD();
    }

}