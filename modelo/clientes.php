<?php
require_once "conexion.php";
class Clientes extends Conexion{
    private $conex;
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $email;
    private $direccion;
    private $status;


public function __construct(){
    $this -> conex = new Conexion();
    $this -> conex = $this->conex->conectar();
}

public function getNombre(){
    return $this->nombre;
}
public function setNombre($nombre){
    $this->nombre=$nombre;
}

public function getApellido(){
    return $this->apellido;
}
public function setApellido($apellido){
    $this->apellido=$apellido;
}

public function getCedula(){
    return $this->cedula;
}
public function setCedula($cedula){
    $this->cedula = $cedula;
}
public function getTelefono(){
    return $this->telefono;
}
public function setTelefono($telefono){
    $this->telefono=$telefono;
}

public function getEmail(){
    return $this->email;
}
public function setEmail($email){
    $this->email=$email;
}

public function getDireccion(){
    return $this->direccion;
}
public function setDireccion($direccion){
    $this->direccion=$direccion;
}

public function getstatus(){
    return $this->status;
}
public function setstatus($valor){
    $this->status=$valor;
}

/*==============================
REGISTRAR CLIENTE
================================*/

private function registrar(){ 

    $registro = "INSERT INTO clientes(nombre,apellido,cedula_rif,telefono,email,direccion,status) VALUES(:nombre, :apellido, :cedula_rif, :telefono,:email,:direccion,1)";
    
    #instanciar el metodo PREPARE no la ejecuta, sino que la inicializa
    $strExec = $this->conex->prepare($registro);

    #instanciar metodo bindparam
    $strExec->bindParam(':nombre', $this->nombre);
    $strExec->bindParam(':apellido', $this->apellido);
    $strExec->bindParam(':cedula_rif', $this->cedula);
    $strExec->bindParam(':telefono', $this->telefono);
    $strExec->bindParam(':email', $this->email);
    $strExec->bindParam(':direccion', $this->direccion);

    $resul = $strExec->execute();
    if($resul){
        $r = 1;
    }else{
        $r = 0;
    }
    return $r;
}

public function getRegistrar(){
    return $this->registrar();
}

public function consultar(){

    $registro = "select * from clientes";
    $consulta = $this->conex->prepare($registro);
    $resul = $consulta->execute();

    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if($resul){
        return $datos;
    }else{
        return $r=0;
    }
}

public function buscar($valor){
    $this->cedula=$valor;
    $registro = "select * from clientes where cedula_rif='".$this->cedula."'";
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

public function getactualizar($valor){
    return $this->actualizar($valor);
}

private function actualizar($valor){
    $cod=$valor;

    $registro="UPDATE clientes SET nombre=:nombre, apellido=:apellido, cedula_rif=:cedula_rif, telefono=:telefono, email=:email, direccion=:direccion, status=:status WHERE cod_cliente=$cod";

    $strExec = $this->conex->prepare($registro);

    #instanciar metodo bindparam
    $strExec->bindParam(':nombre', $this->nombre);
    $strExec->bindParam(':apellido', $this->apellido);
    $strExec->bindParam(':cedula_rif', $this->cedula);
    $strExec->bindParam(':telefono', $this->telefono);
    $strExec->bindParam(':email', $this->email);
    $strExec->bindParam(':direccion', $this->direccion);
    $strExec->bindParam(':status', $this->status);
    $resul = $strExec->execute();
    if($resul){
        $r = 1;
    }else{
        $r = 0;
    }
    return $r;
}

public function geteliminar($valor){
    return $this->eliminar($valor);
}

private function eliminar($valor){
    $registro="SELECT COUNT(*) AS n_ventas FROM ventas WHERE cod_cliente =$valor ";
    $strExec = $this->conex->prepare($registro);
    $resul = $strExec->execute();
    if($resul){
        $resultado=$strExec->fetch(PDO::FETCH_ASSOC); 
        if ($resultado['n_ventas']>0){
            $logico="UPDATE clientes SET status=2 WHERE cod_cliente=$valor";
            $strExec=$this->conex->prepare($logico);
            $strExec->execute();
        }else{
            $fisico="DELETE FROM clientes WHERE cod_cliente=$valor";
            $strExec=$this->conex->prepare($fisico);
            $strExec->execute();
        }
        $r='success';
    }else {
        $r='error_delete';
    }
    return $r;
}

}