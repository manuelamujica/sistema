<?php
require_once 'conexion.php';

class Tproveedor extends Conexion
{

    private $conex;
    private $cod_tlf;
    private $cod_prov;
    private $telefono;



    public function __construct()
    {
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }

 // getter y setter

    public function setcod_tlf($cod_tlf)
    {
        $this->cod_tlf = $cod_tlf;
    }
    public function getcod_tlf()
    {
        return $this->cod_tlf;
    }


    public function setCod1($cod_prov)
    {
        $this->cod_prov = $cod_prov;
    }
    public function getCod1()
    {
        return $this->cod_prov;
    }


    public function gettelefono()
    {
        return $this->telefono;
    }

    public function settelefono($telefono)
    {
        $this->telefono = $telefono;
    }


     // registrar
    private function registra() {
        $sql = "INSERT INTO tlf_proveedores (cod_prov, cod_tlf, telefono) VALUES (:cod_prov, :cod_tlf, :telefono)";
        
        $strExec = $this->conex->prepare($sql);
    
        // Vincula los parámetros
        $strExec->bindParam(':cod_prov', $this->cod_prov);
        $strExec->bindParam(':cod_tlf', $this->cod_tlf); 
        $strExec->bindParam(':telefono', $this->telefono);
    
       
        // Ejecuta la consulta  
        $resul = $strExec->execute();

        if ($resul == 1) {
            return 1; // Éxito  
        } else {
            return 0; // Fallo  
        }
    }
    
    public function getregistra() {
        return $this->registra();
    }
    

  


    //inicio de actualizar//
    private function editar()
    {
        $sql = "UPDATE tlf_proveedores SET cod_tlf = :cod_tlf,telefono = :telefono, status = :status   
              WHERE cod_tlf = :cod_tlf";

        $strExec = $this->conex->prepare($sql);

        $strExec->bindParam(':cod_tlf', $this->cod_tlf);
        $strExec->bindParam(':telefono', $this->telefono);

        // Ejecuta la consulta  
        $resul = $strExec->execute();

        if ($resul == 1) {
            return 1; // Éxito  
        } else {
            return 0; // Fallo  
        }
    }

    public function geteditar()
    {
        return $this->editar();
    }
    //actualizar//


 // eliminar
    private function eliminar($valor)
    {
        $sql = "DELETE FROM tlf_proveedores WHERE cod_tlf = $valor";
        $strExec = $this->conex->prepare($sql);
        $resul = $strExec->execute();
        if ($resul) {
            $res = 1;
        } else {
            $res = 0;
        }
        return $res;
    }

    public function geteliminar($valor)
    {
        return $this->eliminar($valor);
    }

    //inicio de consultar  //
    private function consultar()
    {

        $registro = "select * from tlf_proveedores";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if ($resul) {
            return $datos;
        } else {
            return $res = 0;
        }
    }
    public function getconsulta()
    {
        return $this->consultar();
    }
    //fin de consultar//


    

  //metodo buscar
  private function busca($dato)
  {
    $this->telefono = $dato;
    $registro = "select * from tlf_proveedores where telefono='" . $this->telefono . "'";
    $resulado = "";
    $dato = $this->conex->prepare($registro);
    $resul = $dato->execute();
    $resultado = $dato->fetch(PDO::FETCH_ASSOC);
    if ($resul) {
      return $resultado;
    } else {
      return false;
    }
  }

  public function getbusca($valor)
  {
    return $this->busca($valor);
  }
}
