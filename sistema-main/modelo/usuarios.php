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

//LOGIN
    public function mostrar($valor){
            $resultado = [];

            $sql = "SELECT * FROM usuarios WHERE user = :user";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(":user", $valor, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado === false) {
            return [];
            }else{
                return $resultado;
            }
    }

public function accesos($valor){
    $sql= "SELECT p.cod_permiso FROM usuarios u
        INNER JOIN tipo_usuario tu ON u.cod_tipo_usuario = tu.cod_tipo_usuario
        INNER JOIN tpu_permisos tp ON tu.cod_tipo_usuario = tp.cod_tipo_usuario
        INNER JOIN permisos p ON tp.cod_permiso = p.cod_permiso
        WHERE u.cod_usuario = :valor;";
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':valor', $valor, PDO::PARAM_INT); 
    $resul=$strExec->execute();
    $datos=$strExec->fetchAll(PDO::FETCH_ASSOC);
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
VALIDAR USUARIO (USER)
================================*/
public function buscar($valor){
    $this->user=$valor;
    $registro = "select * from usuarios where user=:user"; 
    $resultado= "";
        $dato=$this->conex->prepare($registro);
        $dato->bindParam(':user',$this->user); 
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
    public function editar($codigo, $rol){
    $sql = "UPDATE usuarios 
        SET nombre=:nombre, user=:user, cod_tipo_usuario=:cod_tipo_usuario, status=:status 
        WHERE cod_usuario=:codigo";

        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':user', $this->user);
        $strExec->bindParam(':cod_tipo_usuario', $rol);
        $strExec->bindParam(':status', $this->status);
        $strExec->bindParam(':codigo', $codigo, PDO::PARAM_INT);

        $resul = $strExec->execute();

        return $resul ? 1 : 0;
    }
    
    public function editar2($codigo, $rol){
    $sql = "UPDATE usuarios 
            SET nombre=:nombre, user=:user, password=:password, cod_tipo_usuario=:cod_tipo_usuario, status=:status 
            WHERE cod_usuario=:codigo";

            $strExec = $this->conex->prepare($sql);
            $strExec->bindParam(':nombre', $this->nombre);
            $strExec->bindParam(':user', $this->user);
            $strExec->bindParam(':password', $this->password);
            $strExec->bindParam(':cod_tipo_usuario', $rol);
            $strExec->bindParam(':status', $this->status);
            $strExec->bindParam(':codigo', $codigo, PDO::PARAM_INT);

            // Ejecutar la consulta
            $resul = $strExec->execute();

            return $resul ? 1 : 0;
    }

/*==============================
ELIMINAR USUARIO
================================*/
public function eliminar($valor) {

    // el usuario a eliminar es administrador?
    $sql = "SELECT cod_tipo_usuario, status FROM usuarios WHERE cod_usuario = :valor";
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':valor', $valor, PDO::PARAM_INT);
    $strExec->execute();
    $usuario = $strExec->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Si el usuario tiene status activo, mostrar error
        if($usuario['status'] == 1){
            return 'error_status';
        }
        
        // Si el usuario es administrador, verificar si es el último
        if ($usuario['cod_tipo_usuario'] == 1) {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE cod_tipo_usuario = 1";
            $strExec = $this->conex->prepare($sql);
            $strExec->execute();
            $resultado = $strExec->fetch(PDO::FETCH_ASSOC);

            if ($resultado['total'] == 2) { // 2 porque por defecto, el admin de programadores estará en la bd
                return 'error_ultimo';
            }
        }


        $sqlDelete = "DELETE FROM usuarios WHERE cod_usuario = :valor";
        $strExecDelete = $this->conex->prepare($sqlDelete);
        $strExecDelete->bindParam(':valor', $valor, PDO::PARAM_INT);
        $delete = $strExecDelete->execute();

        return $delete ? 'success' : 'error_delete'; 
        }
    }

}