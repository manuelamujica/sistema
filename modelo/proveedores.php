<?php 

require_once 'conexion.php';

class Proveedor extends Conexion{

private $conex;
private $rif;
private $razon_social;
private $correo;
private $direccion;
private $status;


  public function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

  public function getRif(){
    return $this->rif;
  }

    public function setRif($rif){
      $this->rif=$rif;
    }

    public function getRazon_social(){
    return $this->razon_social;
  }

    public function setRazon_social($razon_social){
      $this->razon_social=$razon_social;
    }
  
    public function get_Email(){
    return $this->correo;
  }
 
     public function setcorreo($valor){
      $this->correo=$valor;
    }



    public function getDireccion(){
     return $this->direccion;
  }
   
   public function setDireccion($direccion){
      $this->direccion=$direccion;
    }

  
     
     public function getStatus(){
        return $this->status;
    }
   
   public function setStatus($status){
        $this->status=$status;
    }


//metodos crud  //

public function registrar(){
    
  $sql = "INSERT INTO proveedores(rif,razon_social,email,direccion, status)  VALUES  (:rif,:razon_social,:email,:direccion, 1)";

    $strExec =$this->conex->prepare($sql);

    $strExec->bindParam(':rif',$this->rif);
    $strExec->bindParam(':razon_social',$this->razon_social);
    $strExec->bindParam(':email',$this->correo);
    $strExec->bindParam(':direccion',$this->direccion);
    
    $resul=$strExec->execute();
    if ($resul) {
      $res=1;
    }else{
      $res=0;
    }
    return $res;

 }//fin de registrar//


 //inicio de consultar//
public function consultar(){

  $registro="select * from proveedores";
    $consulta=$this->conex->prepare($registro);
    $resul=$consulta->execute();
   $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
   if($resul){
      return $datos;
   }else{
    return $res=0;
   }

 } //fin de consultar//

public function buscar($valor){
  $this->rif = $valor;
  $registro= "select * from proveedores where rif='".$this->rif."'";
  $resultado= "";
  $dato = $this->conex->prepare($registro);
  $resul = $dato->execute();
  $resultado=$dato->fetch(PDO::FETCH_ASSOC);
  if($resul){
      return $resultado;
  }else{
      return false;
  }
}


}

?>