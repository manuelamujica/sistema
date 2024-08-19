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



}